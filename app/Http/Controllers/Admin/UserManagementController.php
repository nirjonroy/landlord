<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteInfo;
use App\Models\User;
use App\Support\ListingAnalytics;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserManagementController extends Controller
{
    private ListingAnalytics $listingAnalytics;

    public function __construct(ListingAnalytics $listingAnalytics)
    {
        $this->listingAnalytics = $listingAnalytics;
    }

    public function index(): View
    {
        $users = User::query()
            ->orderByDesc('created_at')
            ->get();

        [$users, $listingAnalytics] = $this->listingAnalytics->analyticsForUsers($users);
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
