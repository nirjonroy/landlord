<?php

namespace App\Http\Controllers;

use App\Models\HomepageProperty;
use App\Models\Property;
use App\Models\SiteInfo;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PropertyListingController extends Controller
{
    public function index(Request $request): View
    {
        $siteInfo = $this->siteInfo();
        $usesApprovedListings = $this->hasApprovedListings();
        $listingPayload = $usesApprovedListings
            ? $this->approvedListingPayload($request)
            : $this->demoListingPayload($request);
        $listings = $listingPayload['listings'];

        return view('frontend.properties.index', [
            'siteInfo' => $siteInfo,
            'siteName' => $siteInfo->site_name ?: config('app.name', 'Land Site'),
            'siteLogoUrl' => $this->siteLogoUrl($siteInfo),
            'siteUrl' => rtrim($siteInfo->site_url ?: config('app.url', url('/')), '/').'/',
            'listings' => $listings,
            'listingStats' => $listingPayload['stats'],
            'listingSource' => $listingPayload['source'],
            'listingSourceLabel' => $listingPayload['source_label'],
            'listingMessage' => $listingPayload['message'],
            'supportsPropertyTypeFilter' => $listingPayload['supports_property_type_filter'],
            'availablePropertyTypes' => $listingPayload['property_types'],
            'mapEmbedUrl' => $this->mapEmbedUrl($request, $listings),
            'activeFiltersCount' => $this->activeFiltersCount($request),
        ]);
    }

    public function image(Property $property): BinaryFileResponse
    {
        $canView = $property->status === 'approved'
            || (auth()->check() && (int) auth()->id() === (int) $property->user_id)
            || auth('admin')->check();

        abort_unless(
            $canView &&
            $property->thumbnail_path &&
            Storage::disk('public')->exists($property->thumbnail_path),
            404
        );

        return response()->file(Storage::disk('public')->path($property->thumbnail_path));
    }

    private function approvedListingPayload(Request $request): array
    {
        $baseQuery = $this->applyApprovedFilters(
            Property::query()
                ->with('user')
                ->where('status', 'approved'),
            $request
        );

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'rent' => (clone $baseQuery)->where('purpose', 'rent')->count(),
            'sale' => (clone $baseQuery)->where('purpose', 'sale')->count(),
        ];

        $listings = (clone $baseQuery)
            ->latest('created_at')
            ->latest('id')
            ->paginate(6)
            ->withQueryString();

        $listings->setCollection(
            $listings->getCollection()->map(
                fn (Property $property) => $this->approvedListingCard($property)
            )
        );

        return [
            'listings' => $listings,
            'stats' => $stats,
            'source' => 'approved',
            'source_label' => 'Approved Live Listings',
            'message' => $stats['total'] > 0
                ? 'Showing approved landlord listings that are ready for the public marketplace.'
                : 'No approved properties matched the current filters.',
            'supports_property_type_filter' => true,
            'property_types' => $this->approvedPropertyTypes(),
        ];
    }

    private function demoListingPayload(Request $request): array
    {
        $baseQuery = $this->applyDemoFilters(
            HomepageProperty::query(),
            $request
        );

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'rent' => (clone $baseQuery)->where('purpose', 'rent')->count(),
            'sale' => (clone $baseQuery)->where('purpose', 'sale')->count(),
        ];

        $listings = (clone $baseQuery)
            ->orderBy('display_order')
            ->orderBy('id')
            ->paginate(6)
            ->withQueryString();

        $listings->setCollection(
            $listings->getCollection()->map(
                fn (HomepageProperty $property) => $this->demoListingCard($property)
            )
        );

        return [
            'listings' => $listings,
            'stats' => $stats,
            'source' => 'demo',
            'source_label' => 'Seeded Bangladesh Demo',
            'message' => $stats['total'] > 0
                ? 'Showing seeded Bangladesh demo listings until approved user properties go live.'
                : 'No demo properties matched the current filters.',
            'supports_property_type_filter' => false,
            'property_types' => collect(),
        ];
    }

    private function applyApprovedFilters(Builder $query, Request $request): Builder
    {
        $search = trim((string) $request->query('search', ''));
        $purpose = trim((string) $request->query('purpose', ''));
        $propertyType = trim((string) $request->query('property_type', ''));
        $minPrice = $this->moneyInput($request->query('min_price'));
        $maxPrice = $this->moneyInput($request->query('max_price'));

        if ($search !== '') {
            $like = '%'.$search.'%';

            $query->where(function (Builder $builder) use ($like) {
                $builder
                    ->where('title', 'like', $like)
                    ->orWhere('location', 'like', $like)
                    ->orWhere('district', 'like', $like)
                    ->orWhere('division', 'like', $like)
                    ->orWhere('address', 'like', $like)
                    ->orWhereHas('user', function (Builder $userQuery) use ($like) {
                        $userQuery->where('name', 'like', $like);
                    });
            });
        }

        if (in_array($purpose, ['rent', 'sale'], true)) {
            $query->where('purpose', $purpose);
        }

        if ($propertyType !== '') {
            $query->where('property_type', $propertyType);
        }

        if ($minPrice !== null) {
            $query->where('price', '>=', $minPrice);
        }

        if ($maxPrice !== null) {
            $query->where('price', '<=', $maxPrice);
        }

        return $query;
    }

    private function applyDemoFilters(Builder $query, Request $request): Builder
    {
        $search = trim((string) $request->query('search', ''));
        $purpose = trim((string) $request->query('purpose', ''));
        $minPrice = $this->moneyInput($request->query('min_price'));
        $maxPrice = $this->moneyInput($request->query('max_price'));

        if ($search !== '') {
            $like = '%'.$search.'%';

            $query->where(function (Builder $builder) use ($like) {
                $builder
                    ->where('title', 'like', $like)
                    ->orWhere('location', 'like', $like);
            });
        }

        if (in_array($purpose, ['rent', 'sale'], true)) {
            $query->where('purpose', $purpose);
        }

        if ($minPrice !== null) {
            $query->where('price', '>=', $minPrice);
        }

        if ($maxPrice !== null) {
            $query->where('price', '<=', $maxPrice);
        }

        return $query;
    }

    private function approvedListingCard(Property $property): array
    {
        $location = collect([$property->location, $property->district, $property->division])
            ->filter()
            ->unique()
            ->implode(', ');

        return [
            'id' => 'property-'.$property->id,
            'title' => $property->title,
            'location' => $location ?: 'Bangladesh',
            'property_type' => $property->property_type ?: 'Property',
            'purpose_label' => strtolower((string) $property->purpose) === 'sale' ? 'For Sale' : 'For Rent',
            'price_label' => $this->formattedPrice((int) $property->price, (string) $property->purpose),
            'beds_label' => $this->countLabel($property->bedrooms, 'bed', 'No beds'),
            'garage_label' => $this->garageLabel($property->garages),
            'baths_label' => $this->countLabel($property->bathrooms, 'bath', 'No baths'),
            'area_label' => $this->areaLabel($property->area_size),
            'image_url' => $this->propertyImageUrl($property),
            'action_url' => route('contact', ['property' => $property->title]),
            'badge' => 'Approved',
            'badge_tone' => 'success',
            'owner_name' => $property->user?->name ?: 'Verified Owner',
        ];
    }

    private function demoListingCard(HomepageProperty $property): array
    {
        return [
            'id' => 'demo-'.$property->id,
            'title' => $property->title,
            'location' => $property->location ?: 'Bangladesh',
            'property_type' => 'Property',
            'purpose_label' => $property->purpose === 'sale' ? 'For Sale' : 'For Rent',
            'price_label' => $this->formattedPrice((int) $property->price, (string) $property->purpose),
            'beds_label' => $this->countLabel($property->bedrooms, 'bed', 'No beds'),
            'garage_label' => $this->garageLabel($property->garages),
            'baths_label' => $this->countLabel($property->bathrooms, 'bath', 'No baths'),
            'area_label' => number_format((int) $property->area_sqft).' sqft',
            'image_url' => asset($property->image_path),
            'action_url' => route('contact', ['property' => $property->title]),
            'badge' => 'Demo',
            'badge_tone' => 'info',
            'owner_name' => 'Bangladesh Demo Listing',
        ];
    }

    private function hasApprovedListings(): bool
    {
        return Schema::hasTable('properties')
            && Property::query()->where('status', 'approved')->exists();
    }

    private function approvedPropertyTypes(): Collection
    {
        if (! Schema::hasTable('properties')) {
            return collect();
        }

        return Property::query()
            ->where('status', 'approved')
            ->whereNotNull('property_type')
            ->where('property_type', '!=', '')
            ->select('property_type')
            ->distinct()
            ->orderBy('property_type')
            ->pluck('property_type');
    }

    private function propertyImageUrl(Property $property): string
    {
        if ($property->thumbnail_path && Storage::disk('public')->exists($property->thumbnail_path)) {
            return route('properties.image', ['property' => $property, 'v' => optional($property->updated_at)->timestamp]);
        }

        return $this->defaultPropertyImage((string) $property->property_type, (string) $property->purpose);
    }

    private function defaultPropertyImage(string $propertyType, string $purpose): string
    {
        $image = match (strtolower($propertyType)) {
            'house' => 'frontend-assets/img/card_img_21.jpg',
            'land' => 'frontend-assets/img/card_img_5.jpg',
            'office' => 'frontend-assets/img/card_img_4.jpg',
            'apartment' => 'frontend-assets/img/card_img_2.jpg',
            default => strtolower($purpose) === 'sale'
                ? 'frontend-assets/img/card_img_6.jpg'
                : 'frontend-assets/img/card_img_1.jpg',
        };

        return asset($image);
    }

    private function formattedPrice(int $price, string $purpose): string
    {
        return '৳'.number_format($price).(strtolower($purpose) === 'rent' ? ' /month' : '');
    }

    private function countLabel(mixed $value, string $singular, string $emptyLabel): string
    {
        $count = is_numeric($value) ? (int) $value : null;

        if ($count === null || $count < 1) {
            return $emptyLabel;
        }

        return $count.' '.($count === 1 ? $singular : $singular.'s');
    }

    private function garageLabel(mixed $value): string
    {
        $count = is_numeric($value) ? (int) $value : null;

        if ($count === null || $count < 1) {
            return 'No garage';
        }

        return $count.' '.($count === 1 ? 'garage' : 'garages');
    }

    private function areaLabel(mixed $value): string
    {
        if ($value === null || $value === '') {
            return 'Area on request';
        }

        return number_format((float) $value).' sqft';
    }

    private function mapEmbedUrl(Request $request, LengthAwarePaginator $listings): string
    {
        $focus = trim((string) $request->query('search', ''));

        if ($focus === '') {
            $firstListing = $listings->getCollection()->first();
            $focus = is_array($firstListing) ? (string) ($firstListing['location'] ?? '') : '';
        }

        if ($focus === '') {
            $focus = 'Bangladesh';
        } elseif (! str_contains(strtolower($focus), 'bangladesh')) {
            $focus .= ', Bangladesh';
        }

        return 'https://maps.google.com/maps?q='.rawurlencode($focus).'&output=embed';
    }

    private function moneyInput(mixed $value): ?int
    {
        $normalized = preg_replace('/[^\d]/', '', (string) $value);

        return $normalized === '' ? null : (int) $normalized;
    }

    private function activeFiltersCount(Request $request): int
    {
        return collect([
            trim((string) $request->query('search', '')),
            trim((string) $request->query('purpose', '')),
            trim((string) $request->query('property_type', '')),
            $this->moneyInput($request->query('min_price')),
            $this->moneyInput($request->query('max_price')),
        ])->filter(fn ($value) => $value !== null && $value !== '')->count();
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

        return route('site.logo', ['v' => optional($siteInfo->updated_at)->timestamp]);
    }
}
