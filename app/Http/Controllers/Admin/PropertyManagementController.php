<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\SiteInfo;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PropertyManagementController extends Controller
{
    public function index(): View
    {
        $properties = Property::query()
            ->with(['user', 'reviewedBy'])
            ->orderByRaw("case when status = 'pending' then 0 else 1 end")
            ->orderByDesc('updated_at')
            ->orderByDesc('created_at')
            ->get();

        $siteInfo = $this->siteInfo();

        return view('admin.properties.index', [
            'admin' => Auth::guard('admin')->user(),
            'siteInfo' => $siteInfo,
            'siteName' => $siteInfo->site_name ?: config('app.name', 'Land Site'),
            'siteLogoUrl' => $this->siteLogoUrl($siteInfo),
            'properties' => $properties,
            'pendingProperties' => $properties->where('status', 'pending')->values(),
            'reviewedProperties' => $properties->where('status', '!=', 'pending')->values(),
            'propertyCount' => $properties->count(),
            'pendingCount' => $properties->where('status', 'pending')->count(),
            'approvedCount' => $properties->where('status', 'approved')->count(),
            'rejectedCount' => $properties->where('status', 'rejected')->count(),
        ]);
    }

    public function show(Property $property): View
    {
        $siteInfo = $this->siteInfo();

        return view('admin.properties.show', [
            'admin' => Auth::guard('admin')->user(),
            'siteInfo' => $siteInfo,
            'siteName' => $siteInfo->site_name ?: config('app.name', 'Land Site'),
            'siteLogoUrl' => $this->siteLogoUrl($siteInfo),
            'property' => $property->loadMissing(['user', 'reviewedBy']),
            'reviewTone' => $this->reviewTone((string) $property->status),
            'reviewLabel' => $this->reviewLabel((string) $property->status),
            'availabilityTone' => $this->availabilityTone((string) $property->availability_status),
            'availabilityLabel' => $this->availabilityLabel((string) $property->availability_status, (string) $property->purpose),
            'thumbnailUrl' => $this->thumbnailUrl($property),
            'galleryUrls' => $this->galleryUrls($property),
        ]);
    }

    public function updateReview(Request $request, Property $property): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['approved', 'rejected'])],
            'review_note' => [
                Rule::requiredIf($request->input('status') === 'rejected'),
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $property->status = $validated['status'];
        $property->review_note = $validated['status'] === 'rejected'
            ? trim((string) ($validated['review_note'] ?? ''))
            : ($validated['review_note'] ?? null);
        $property->reviewed_at = now();
        $property->reviewed_by_admin_id = Auth::guard('admin')->id();
        $property->save();

        if ($request->input('return_to') === 'show') {
            return redirect()
                ->route('admin.properties.show', $property)
                ->with('status', 'property-reviewed');
        }

        return redirect()
            ->route('admin.properties.index')
            ->with('status', 'property-reviewed');
    }

    private function thumbnailUrl(Property $property): ?string
    {
        if (! $property->thumbnail_path || ! Storage::disk('public')->exists($property->thumbnail_path)) {
            return null;
        }

        return route('properties.image', ['property' => $property, 'v' => optional($property->updated_at)->timestamp]);
    }

    private function galleryUrls(Property $property): array
    {
        return collect($property->gallery_paths ?? [])
            ->filter(fn (?string $path) => $path && Storage::disk('public')->exists($path))
            ->values()
            ->map(fn (string $path, int $index) => route('properties.gallery.image', [
                'property' => $property,
                'index' => $index,
                'v' => optional($property->updated_at)->timestamp,
            ]))
            ->all();
    }

    private function reviewLabel(string $status): string
    {
        $normalized = trim(str_replace(['-', '_'], ' ', strtolower($status)));

        return $normalized === '' ? 'Pending' : ucwords($normalized);
    }

    private function reviewTone(string $status): string
    {
        return match (strtolower(trim($status))) {
            'approved' => 'success',
            'rejected' => 'danger',
            default => 'warning',
        };
    }

    private function availabilityLabel(string $availabilityStatus, string $purpose): string
    {
        return match (strtolower(trim($availabilityStatus))) {
            'sold' => 'Sold',
            'rented' => strtolower($purpose) === 'rent' ? 'Rented' : 'Rented',
            default => 'Still Available',
        };
    }

    private function availabilityTone(string $availabilityStatus): string
    {
        return match (strtolower(trim($availabilityStatus))) {
            'sold', 'rented' => 'danger',
            default => 'success',
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

        return route('admin.site-info.logo', ['v' => optional($siteInfo->updated_at)->timestamp]);
    }
}
