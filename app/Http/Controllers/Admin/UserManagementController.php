<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteInfo;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class UserManagementController extends Controller
{
    public function index(): View
    {
        [$users, $listingAnalytics] = $this->usersWithAnalytics();
        $siteInfo = $this->siteInfo();

        return view('admin.users.index', [
            'admin' => Auth::guard('admin')->user(),
            'siteInfo' => $siteInfo,
            'siteName' => $siteInfo->site_name ?: config('app.name', 'Land Site'),
            'siteLogoUrl' => $this->siteLogoUrl($siteInfo),
            'users' => $users,
            'userCount' => $users->count(),
            'listingDataAvailable' => $listingAnalytics['available'],
            'listingDataMessage' => $listingAnalytics['message'],
            'listingTable' => $listingAnalytics['table'],
            'listingTypeColumn' => $listingAnalytics['type_column'],
            'totalPostsCount' => $listingAnalytics['total_posts'],
            'usersWithPostsCount' => $listingAnalytics['users_with_posts'],
            'rentPostsCount' => $listingAnalytics['rent_posts'],
            'salePostsCount' => $listingAnalytics['sale_posts'],
        ]);
    }

    private function usersWithAnalytics(): array
    {
        $users = User::query()
            ->orderByDesc('created_at')
            ->get();

        $defaultAnalytics = [
            'available' => false,
            'message' => 'No posts or listings table exists yet. User totals are live now, and post, rent, and sale metrics will start filling automatically after the listing module is added.',
            'table' => null,
            'type_column' => null,
            'total_posts' => 0,
            'users_with_posts' => 0,
            'rent_posts' => 0,
            'sale_posts' => 0,
        ];

        $listingTable = $this->detectListingTable();

        if ($listingTable === null) {
            return [$this->attachUserPostCounts($users, collect()), $defaultAnalytics];
        }

        $columns = Schema::getColumnListing($listingTable);

        if (! in_array('user_id', $columns, true)) {
            $defaultAnalytics['message'] = 'A listing-style table exists, but it does not contain a user relation yet. User totals are live, while post analytics will stay at 0 until a user_id column is added.';
            $defaultAnalytics['table'] = $listingTable;

            return [$this->attachUserPostCounts($users, collect()), $defaultAnalytics];
        }

        $typeColumn = collect(['type', 'purpose', 'listing_type', 'property_for', 'ad_type', 'post_type'])
            ->first(fn (string $column) => in_array($column, $columns, true));

        $perUserCountsQuery = DB::table($listingTable)
            ->select('user_id')
            ->selectRaw('COUNT(*) as posts_count');

        $analytics = [
            'available' => true,
            'message' => 'User and listing metrics are being read from the '.$listingTable.' table.',
            'table' => $listingTable,
            'type_column' => $typeColumn,
            'total_posts' => (int) DB::table($listingTable)->count(),
            'users_with_posts' => (int) DB::table($listingTable)->distinct()->count('user_id'),
            'rent_posts' => 0,
            'sale_posts' => 0,
        ];

        if ($typeColumn !== null) {
            $wrappedColumn = DB::getQueryGrammar()->wrap($typeColumn);

            $perUserCountsQuery
                ->selectRaw("SUM(CASE WHEN lower({$wrappedColumn}) = 'rent' THEN 1 ELSE 0 END) as rent_posts_count")
                ->selectRaw("SUM(CASE WHEN lower({$wrappedColumn}) = 'sale' THEN 1 ELSE 0 END) as sale_posts_count");

            $analytics['rent_posts'] = (int) DB::table($listingTable)
                ->whereRaw("lower({$wrappedColumn}) = 'rent'")
                ->count();

            $analytics['sale_posts'] = (int) DB::table($listingTable)
                ->whereRaw("lower({$wrappedColumn}) = 'sale'")
                ->count();
        } else {
            $perUserCountsQuery
                ->selectRaw('0 as rent_posts_count')
                ->selectRaw('0 as sale_posts_count');
        }

        $perUserCounts = $perUserCountsQuery
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');

        return [$this->attachUserPostCounts($users, $perUserCounts), $analytics];
    }

    private function attachUserPostCounts(Collection $users, Collection $perUserCounts): Collection
    {
        return $users->map(function (User $user) use ($perUserCounts) {
            $counts = $perUserCounts->get($user->id);

            $user->setAttribute('posts_count', (int) ($counts->posts_count ?? 0));
            $user->setAttribute('rent_posts_count', (int) ($counts->rent_posts_count ?? 0));
            $user->setAttribute('sale_posts_count', (int) ($counts->sale_posts_count ?? 0));

            return $user;
        });
    }

    private function detectListingTable(): ?string
    {
        foreach (['posts', 'listings', 'properties', 'property_posts', 'land_posts'] as $candidateTable) {
            if (Schema::hasTable($candidateTable)) {
                return $candidateTable;
            }
        }

        return null;
    }

    private function siteInfo(): SiteInfo
    {
        return SiteInfo::query()->firstOrCreate(
            ['id' => 1],
            [
                'site_name' => config('app.name', 'Land Site'),
                'site_url' => config('app.url', url('/')),
                'short_description' => 'Manage land listings, user accounts, and app access from a single dashboard.',
            ]
        );
    }

    private function siteLogoUrl(SiteInfo $siteInfo): ?string
    {
        if (! $siteInfo->logo_path || ! Storage::disk('public')->exists($siteInfo->logo_path)) {
            return null;
        }

        return route('admin.site-info.logo', ['v' => optional($siteInfo->updated_at)->timestamp]);
    }
}
