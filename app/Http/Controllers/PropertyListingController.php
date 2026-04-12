<?php

namespace App\Http\Controllers;

use App\Models\HomepageProperty;
use App\Models\Property;
use App\Models\PropertyType;
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
        $activePropertyTypes = $this->activePropertyTypes();
        $usesApprovedListings = $this->hasApprovedListings();
        $listingPayload = $usesApprovedListings
            ? $this->approvedListingPayload($request, $activePropertyTypes)
            : $this->demoListingPayload($request, $activePropertyTypes);
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

    public function show(Property $property): View
    {
        $this->ensurePropertyVisible($property);

        $siteInfo = $this->siteInfo();
        $galleryItems = $this->galleryItems($property);

        return view('frontend.properties.show', [
            'siteInfo' => $siteInfo,
            'siteName' => $siteInfo->site_name ?: config('app.name', 'Land Site'),
            'siteLogoUrl' => $this->siteLogoUrl($siteInfo),
            'siteUrl' => rtrim($siteInfo->site_url ?: config('app.url', url('/')), '/').'/',
            'property' => $property->loadMissing('user', 'reviewedBy'),
            'galleryItems' => $galleryItems,
            'propertyLocation' => $this->propertyLocation($property),
            'propertyPriceLabel' => $this->formattedPrice((int) $property->price, (string) $property->purpose),
            'propertyPurposeLabel' => strtolower((string) $property->purpose) === 'sale' ? 'For Sale' : 'For Rent',
            'reviewStatusLabel' => $this->reviewStatusLabel((string) $property->status),
            'reviewStatusTone' => $this->reviewStatusTone((string) $property->status),
            'availabilityLabel' => $this->availabilityLabel((string) $property->availability_status, (string) $property->purpose),
            'availabilityTone' => $this->availabilityTone((string) $property->availability_status),
            'availabilityOptions' => $this->availabilityOptions($property),
            'ownerCanManage' => auth()->check() && (int) auth()->id() === (int) $property->user_id,
            'adminCanManage' => auth('admin')->check(),
            'mapEmbedUrl' => $this->propertyMapEmbedUrl($property),
        ]);
    }

    public function image(Property $property): BinaryFileResponse
    {
        $this->ensurePropertyVisible($property);

        abort_unless(
            $property->thumbnail_path &&
            Storage::disk('public')->exists($property->thumbnail_path),
            404
        );

        return response()->file(Storage::disk('public')->path($property->thumbnail_path));
    }

    public function galleryImage(Property $property, int $index): BinaryFileResponse
    {
        $this->ensurePropertyVisible($property);

        $path = ($property->gallery_paths ?? [])[$index] ?? null;

        abort_unless($path && Storage::disk('public')->exists($path), 404);

        return response()->file(Storage::disk('public')->path($path));
    }

    private function approvedListingPayload(Request $request, Collection $activePropertyTypes): array
    {
        $baseQuery = $this->applyApprovedFilters(
            Property::query()
                ->with('user')
                ->where('status', 'approved')
                ->where('availability_status', 'available'),
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
                ? 'Showing approved and currently available landlord listings that are ready for the public marketplace.'
                : 'No approved available properties matched the current filters.',
            'supports_property_type_filter' => $activePropertyTypes->isNotEmpty(),
            'property_types' => $activePropertyTypes,
        ];
    }

    private function demoListingPayload(Request $request, Collection $activePropertyTypes): array
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
            'supports_property_type_filter' => $activePropertyTypes->isNotEmpty(),
            'property_types' => $activePropertyTypes,
        ];
    }

    private function applyApprovedFilters(Builder $query, Request $request): Builder
    {
        $search = trim((string) $request->query('search', ''));
        $location = trim((string) $request->query('location', ''));
        $postalCode = trim((string) $request->query('postal_code', ''));
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
                    ->orWhere('postal_code', 'like', $like)
                    ->orWhere('address', 'like', $like)
                    ->orWhereHas('user', function (Builder $userQuery) use ($like) {
                        $userQuery->where('name', 'like', $like);
                    });
            });
        }

        if ($location !== '') {
            $like = '%'.$location.'%';

            $query->where(function (Builder $builder) use ($like) {
                $builder
                    ->where('location', 'like', $like)
                    ->orWhere('district', 'like', $like)
                    ->orWhere('division', 'like', $like)
                    ->orWhere('address', 'like', $like);
            });
        }

        if ($postalCode !== '') {
            $query->where('postal_code', 'like', '%'.$postalCode.'%');
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
        $location = trim((string) $request->query('location', ''));
        $postalCode = trim((string) $request->query('postal_code', ''));
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
                    ->orWhere('postal_code', 'like', $like);
            });
        }

        if ($location !== '') {
            $query->where('location', 'like', '%'.$location.'%');
        }

        if ($postalCode !== '') {
            $query->where('postal_code', 'like', '%'.$postalCode.'%');
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

    private function approvedListingCard(Property $property): array
    {
        return [
            'id' => 'property-'.$property->id,
            'title' => $property->title,
            'location' => $this->propertyLocation($property),
            'property_type' => $property->property_type ?: 'Property',
            'purpose_label' => strtolower((string) $property->purpose) === 'sale' ? 'For Sale' : 'For Rent',
            'price_label' => $this->formattedPrice((int) $property->price, (string) $property->purpose),
            'beds_label' => $this->countLabel($property->bedrooms, 'bed', 'No beds'),
            'garage_label' => $this->garageLabel($property->garages),
            'baths_label' => $this->countLabel($property->bathrooms, 'bath', 'No baths'),
            'area_label' => $this->areaLabel($property->area_size),
            'image_url' => $this->propertyImageUrl($property),
            'action_url' => route('properties.show', $property),
            'action_label' => 'Details',
            'badge' => 'Approved',
            'badge_tone' => 'success',
            'owner_name' => $property->user?->name ?: 'Verified Owner',
            'postal_code' => $property->postal_code,
        ];
    }

    private function demoListingCard(HomepageProperty $property): array
    {
        return [
            'id' => 'demo-'.$property->id,
            'title' => $property->title,
            'location' => $property->location ?: 'Bangladesh',
            'property_type' => $property->property_type ?: 'Property',
            'purpose_label' => $property->purpose === 'sale' ? 'For Sale' : 'For Rent',
            'price_label' => $this->formattedPrice((int) $property->price, (string) $property->purpose),
            'beds_label' => $this->countLabel($property->bedrooms, 'bed', 'No beds'),
            'garage_label' => $this->garageLabel($property->garages),
            'baths_label' => $this->countLabel($property->bathrooms, 'bath', 'No baths'),
            'area_label' => number_format((int) $property->area_sqft).' sqft',
            'image_url' => asset($property->image_path),
            'action_url' => route('contact', ['property' => $property->title]),
            'action_label' => 'Contact',
            'badge' => 'Demo',
            'badge_tone' => 'info',
            'owner_name' => 'Bangladesh Demo Listing',
            'postal_code' => $property->postal_code,
        ];
    }

    private function hasApprovedListings(): bool
    {
        return Schema::hasTable('properties')
            && Property::query()
                ->where('status', 'approved')
                ->where('availability_status', 'available')
                ->exists();
    }

    private function activePropertyTypes(): Collection
    {
        if (! Schema::hasTable('property_types')) {
            return collect();
        }

        return PropertyType::query()
            ->active()
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();
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
        return 'BDT '.number_format($price).(strtolower($purpose) === 'rent' ? ' /month' : '');
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
            $focus = trim((string) $request->query('location', ''));
        }

        if ($focus === '') {
            $focus = trim((string) $request->query('postal_code', ''));
        }

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
            trim((string) $request->query('location', '')),
            trim((string) $request->query('postal_code', '')),
            trim((string) $request->query('purpose', '')),
            trim((string) $request->query('property_type', '')),
            $this->moneyInput($request->query('min_price')),
            $this->moneyInput($request->query('max_price')),
        ])->filter(fn ($value) => $value !== null && $value !== '')->count();
    }

    private function galleryItems(Property $property): array
    {
        $items = [];

        if ($property->thumbnail_path && Storage::disk('public')->exists($property->thumbnail_path)) {
            $items[] = [
                'url' => route('properties.image', ['property' => $property, 'v' => optional($property->updated_at)->timestamp]),
                'label' => 'Cover Image',
            ];
        }

        foreach (($property->gallery_paths ?? []) as $index => $path) {
            if (! $path || ! Storage::disk('public')->exists($path)) {
                continue;
            }

            $items[] = [
                'url' => route('properties.gallery.image', [
                    'property' => $property,
                    'index' => $index,
                    'v' => optional($property->updated_at)->timestamp,
                ]),
                'label' => 'Gallery Image '.($index + 1),
            ];
        }

        if ($items === []) {
            $items[] = [
                'url' => $this->defaultPropertyImage((string) $property->property_type, (string) $property->purpose),
                'label' => 'Property Image',
            ];
        }

        return $items;
    }

    private function propertyLocation(Property $property): string
    {
        return collect([
            $property->location,
            $property->district,
            $property->division,
            $property->postal_code,
        ])->filter()->unique()->implode(', ') ?: 'Bangladesh';
    }

    private function propertyMapEmbedUrl(Property $property): string
    {
        $focus = collect([
            $property->address,
            $property->location,
            $property->district,
            $property->division,
            $property->postal_code,
            'Bangladesh',
        ])->filter()->implode(', ');

        return 'https://maps.google.com/maps?q='.rawurlencode($focus).'&output=embed';
    }

    private function ensurePropertyVisible(Property $property): void
    {
        $canView = $property->status === 'approved'
            || (auth()->check() && (int) auth()->id() === (int) $property->user_id)
            || auth('admin')->check();

        abort_unless($canView, 404);
    }

    private function availabilityOptions(Property $property): array
    {
        if (strtolower((string) $property->purpose) === 'rent') {
            return [
                'available' => 'Still Available',
                'rented' => 'Mark as Rented',
            ];
        }

        return [
            'available' => 'Still Available',
            'sold' => 'Mark as Sold',
        ];
    }

    private function availabilityLabel(string $availabilityStatus, string $purpose): string
    {
        $normalized = strtolower(trim($availabilityStatus));

        return match ($normalized) {
            'sold' => 'Sold',
            'rented' => strtolower($purpose) === 'rent' ? 'Rented' : 'Rented',
            default => 'Still Available',
        };
    }

    private function availabilityTone(string $availabilityStatus): string
    {
        $normalized = strtolower(trim($availabilityStatus));

        return match ($normalized) {
            'sold', 'rented' => 'danger',
            'available' => 'success',
            default => 'neutral',
        };
    }

    private function reviewStatusLabel(string $status): string
    {
        $normalized = trim(str_replace(['-', '_'], ' ', strtolower($status)));

        return $normalized === '' ? 'Pending' : ucwords($normalized);
    }

    private function reviewStatusTone(string $status): string
    {
        return match (strtolower(trim($status))) {
            'approved' => 'success',
            'rejected' => 'danger',
            default => 'warning',
        };
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
