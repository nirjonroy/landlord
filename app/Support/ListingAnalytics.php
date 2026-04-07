<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ListingAnalytics
{
    public function analyticsForUsers(Collection $users): array
    {
        $defaultAnalytics = $this->defaultAdminAnalytics();
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

        $typeColumn = $this->resolveColumn($columns, ['type', 'purpose', 'listing_type', 'property_for', 'ad_type', 'post_type']);

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

        if ($analytics['total_posts'] === 0) {
            $analytics['message'] = 'The '.$listingTable.' table is ready, but no user has posted a property yet.';
        }

        return [$this->attachUserPostCounts($users, $perUserCounts), $analytics];
    }

    public function analyticsForUser(User $user, int $limit = 10): array
    {
        $listingTable = $this->detectListingTable();
        $defaultAnalytics = [
            'available' => false,
            'message' => 'No property module exists yet. Your listed properties will show here after the first property table is added.',
            'table' => null,
            'type_column' => null,
            'status_column' => null,
            'total_posts' => 0,
            'rent_posts' => 0,
            'sale_posts' => 0,
            'listings' => collect(),
        ];

        if ($listingTable === null) {
            return $defaultAnalytics;
        }

        $columns = Schema::getColumnListing($listingTable);

        if (! in_array('user_id', $columns, true)) {
            $defaultAnalytics['message'] = 'A property-style table exists, but it is not connected to user accounts yet. Property status will appear here after a user_id column is added.';
            $defaultAnalytics['table'] = $listingTable;

            return $defaultAnalytics;
        }

        $typeColumn = $this->resolveColumn($columns, ['type', 'purpose', 'listing_type', 'property_for', 'ad_type', 'post_type']);
        $statusColumn = $this->resolveColumn($columns, ['status', 'approval_status', 'listing_status', 'publish_status', 'post_status']);
        $titleColumn = $this->resolveColumn($columns, ['title', 'name', 'property_title', 'post_title', 'headline']);
        $locationColumn = $this->resolveColumn($columns, ['location', 'address', 'property_address', 'area_name', 'district', 'city']);
        $priceColumn = $this->resolveColumn($columns, ['price', 'amount', 'sale_price', 'rent_price', 'expected_price']);
        $orderColumn = in_array('created_at', $columns, true)
            ? 'created_at'
            : (in_array('id', $columns, true) ? 'id' : 'user_id');

        $baseQuery = DB::table($listingTable)->where('user_id', $user->id);

        $analytics = [
            'available' => true,
            'message' => 'Your property activity is being read from the '.$listingTable.' table.',
            'table' => $listingTable,
            'type_column' => $typeColumn,
            'status_column' => $statusColumn,
            'total_posts' => (int) (clone $baseQuery)->count(),
            'rent_posts' => 0,
            'sale_posts' => 0,
            'listings' => collect(),
        ];

        if ($typeColumn !== null) {
            $wrappedColumn = DB::getQueryGrammar()->wrap($typeColumn);

            $analytics['rent_posts'] = (int) (clone $baseQuery)
                ->whereRaw("lower({$wrappedColumn}) = 'rent'")
                ->count();

            $analytics['sale_posts'] = (int) (clone $baseQuery)
                ->whereRaw("lower({$wrappedColumn}) = 'sale'")
                ->count();
        }

        $analytics['listings'] = (clone $baseQuery)
            ->orderByDesc($orderColumn)
            ->limit($limit)
            ->get()
            ->map(function (object $listing) use ($locationColumn, $priceColumn, $statusColumn, $titleColumn, $typeColumn) {
                return [
                    'id' => $listing->id ?? null,
                    'title' => $this->listingTitle($listing, $titleColumn),
                    'purpose' => $this->listingPurpose($listing, $typeColumn),
                    'status' => $this->listingStatus($listing, $statusColumn),
                    'status_tone' => $this->listingStatusTone($listing, $statusColumn),
                    'location' => $locationColumn ? $this->stringValue($listing->{$locationColumn} ?? null) : null,
                    'price' => $priceColumn ? $this->listingPrice($listing->{$priceColumn} ?? null) : null,
                    'submitted_at' => $this->listingDate($listing->created_at ?? null),
                ];
            });

        if ($analytics['total_posts'] === 0) {
            $analytics['message'] = 'Your property module is ready. Add your first sale or rent property to see it here.';
        }

        return $analytics;
    }

    public function detectListingTable(): ?string
    {
        foreach (['properties', 'listings', 'property_posts', 'land_posts', 'posts'] as $candidateTable) {
            if (Schema::hasTable($candidateTable)) {
                return $candidateTable;
            }
        }

        return null;
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

    private function defaultAdminAnalytics(): array
    {
        return [
            'available' => false,
            'message' => 'No property table exists yet. User totals are live now, and sale and rent metrics will start filling automatically after the listing module is added.',
            'table' => null,
            'type_column' => null,
            'total_posts' => 0,
            'users_with_posts' => 0,
            'rent_posts' => 0,
            'sale_posts' => 0,
        ];
    }

    private function resolveColumn(array $columns, array $candidates): ?string
    {
        foreach ($candidates as $candidate) {
            if (in_array($candidate, $columns, true)) {
                return $candidate;
            }
        }

        return null;
    }

    private function listingTitle(object $listing, ?string $titleColumn): string
    {
        $value = $titleColumn ? $this->stringValue($listing->{$titleColumn} ?? null) : null;

        if ($value !== null) {
            return $value;
        }

        return 'Property #'.($listing->id ?? 'Draft');
    }

    private function listingPurpose(object $listing, ?string $typeColumn): string
    {
        $value = $typeColumn ? strtolower((string) ($listing->{$typeColumn} ?? '')) : '';

        return match ($value) {
            'sale' => 'For Sale',
            'rent' => 'For Rent',
            default => $value !== '' ? Str::title(str_replace(['-', '_'], ' ', $value)) : 'Not set',
        };
    }

    private function listingStatus(object $listing, ?string $statusColumn): string
    {
        $value = $statusColumn ? strtolower((string) ($listing->{$statusColumn} ?? '')) : '';

        if ($value === '') {
            return 'Listed';
        }

        return Str::title(str_replace(['-', '_'], ' ', $value));
    }

    private function listingStatusTone(object $listing, ?string $statusColumn): string
    {
        $value = $statusColumn ? strtolower((string) ($listing->{$statusColumn} ?? '')) : '';

        return match ($value) {
            'approved', 'published', 'active', 'available', 'live' => 'success',
            'pending', 'draft', 'review', 'processing' => 'warning',
            'rejected', 'inactive', 'sold', 'rented', 'closed' => 'danger',
            default => 'neutral',
        };
    }

    private function listingPrice(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return '৳'.number_format((float) $value, 0);
        }

        return $this->stringValue($value);
    }

    private function listingDate(mixed $value): string
    {
        if ($value === null || $value === '') {
            return 'Recently added';
        }

        try {
            return Carbon::parse($value)->format('d M Y');
        } catch (\Throwable) {
            return (string) $value;
        }
    }

    private function stringValue(mixed $value): ?string
    {
        $value = is_string($value) ? trim($value) : $value;

        return filled($value) ? (string) $value : null;
    }
}
