<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePropertyRequest;
use App\Models\Property;
use App\Models\PropertyType;
use App\Services\SubscriptionAccessService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PropertyController extends Controller
{
    private SubscriptionAccessService $subscriptionAccessService;

    public function __construct(SubscriptionAccessService $subscriptionAccessService)
    {
        $this->subscriptionAccessService = $subscriptionAccessService;
    }

    public function edit(Request $request, Property $property): View
    {
        abort_unless((int) $property->user_id === (int) $request->user()->id, 403);

        return view('frontend.properties.edit', [
            'user' => $request->user(),
            'property' => $property,
            'subscriptionSummary' => $this->subscriptionAccessService->summaryForUser($request->user()),
            'propertyTypes' => PropertyType::query()
                ->active()
                ->orderBy('display_order')
                ->orderBy('name')
                ->get(),
            'propertyThumbnailUrl' => $this->propertyThumbnailUrl($property),
            'propertyGalleryUrls' => $this->propertyGalleryUrls($property),
            'reviewStatusLabel' => $this->reviewStatusLabel((string) $property->status),
            'reviewStatusTone' => $this->reviewStatusTone((string) $property->status),
            'availabilityLabel' => $this->availabilityLabel((string) $property->availability_status, (string) $property->purpose),
            'availabilityTone' => $this->availabilityTone((string) $property->availability_status),
        ]);
    }

    public function store(StorePropertyRequest $request): RedirectResponse
    {
        $user = $request->user();
        $access = $this->subscriptionAccessService->canCreateProperty($user);

        if (! $access['allowed']) {
            return Redirect::to(route('profile.edit', ['tab' => 'subscription']).'#subscription')
                ->with('status', $access['status']);
        }

        $validated = $request->safe()->except([
            'property_form',
            'thumbnail_image',
            'gallery_images',
            'remove_thumbnail_image',
            'reset_gallery_images',
        ]);

        $property = new Property($validated);
        $property->user_id = $user->id;
        $property->status = 'pending';
        $property->availability_status = 'available';
        $property->contact_phone = $validated['contact_phone'] ?? $user->phone;

        if ($request->hasFile('thumbnail_image')) {
            $property->thumbnail_path = $request->file('thumbnail_image')->store('users/'.$user->id.'/properties/thumbnails', 'public');
        }

        if ($request->hasFile('gallery_images')) {
            $property->gallery_paths = collect($request->file('gallery_images'))
                ->map(fn ($image) => $image->store('users/'.$user->id.'/properties/gallery', 'public'))
                ->all();
        }

        $property->save();

        return Redirect::to(route('profile.edit', ['tab' => 'my_property']).'#my_property')
            ->with('status', 'property-created');
    }

    public function update(StorePropertyRequest $request, Property $property): RedirectResponse
    {
        abort_unless((int) $property->user_id === (int) $request->user()->id, 403);

        $user = $request->user();
        $access = $this->subscriptionAccessService->canUpdateProperty($user);

        if (! $access['allowed']) {
            return Redirect::to(route('profile.edit', ['tab' => 'subscription']).'#subscription')
                ->with('status', $access['status']);
        }

        $validated = $request->safe()->except([
            'property_form',
            'thumbnail_image',
            'gallery_images',
            'remove_thumbnail_image',
            'reset_gallery_images',
        ]);

        $property->fill($validated);
        $property->contact_phone = $validated['contact_phone'] ?? $user->phone;
        $property->status = 'pending';
        $property->review_note = null;
        $property->reviewed_at = null;
        $property->reviewed_by_admin_id = null;
        $property->availability_status = $this->normalizedAvailabilityStatus(
            (string) ($validated['purpose'] ?? $property->purpose),
            (string) $property->availability_status
        );

        if ($request->boolean('remove_thumbnail_image') && $property->thumbnail_path) {
            $this->deleteFile($property->thumbnail_path);
            $property->thumbnail_path = null;
        }

        if ($request->hasFile('thumbnail_image')) {
            $this->deleteFile($property->thumbnail_path);
            $property->thumbnail_path = $request->file('thumbnail_image')->store('users/'.$user->id.'/properties/thumbnails', 'public');
        }

        if ($request->boolean('reset_gallery_images') || $request->hasFile('gallery_images')) {
            $this->deleteFiles($property->gallery_paths ?? []);
            $property->gallery_paths = null;
        }

        if ($request->hasFile('gallery_images')) {
            $property->gallery_paths = collect($request->file('gallery_images'))
                ->map(fn ($image) => $image->store('users/'.$user->id.'/properties/gallery', 'public'))
                ->all();
        }

        $property->save();

        return Redirect::to(route('properties.show', $property).'#management-panel')
            ->with('status', 'property-updated');
    }

    public function updateAvailability(Request $request, Property $property): RedirectResponse
    {
        abort_unless((int) $property->user_id === (int) $request->user()->id, 403);

        $allowedStatuses = strtolower((string) $property->purpose) === 'rent'
            ? ['available', 'rented']
            : ['available', 'sold'];

        $validated = $request->validate([
            'availability_status' => ['required', Rule::in($allowedStatuses)],
        ]);

        $property->availability_status = $validated['availability_status'];
        $property->save();

        return Redirect::to(route('properties.show', $property).'#management-panel')
            ->with('status', 'property-availability-updated');
    }

    public function destroy(Request $request, Property $property): RedirectResponse
    {
        abort_unless((int) $property->user_id === (int) $request->user()->id, 403);

        $this->deleteFile($property->thumbnail_path);
        $this->deleteFiles($property->gallery_paths ?? []);

        $property->delete();

        return Redirect::to(route('profile.edit', ['tab' => 'my_property']).'#my_property')
            ->with('status', 'property-deleted');
    }

    private function deleteFiles(array $paths): void
    {
        foreach ($paths as $path) {
            $this->deleteFile($path);
        }
    }

    private function deleteFile(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    private function normalizedAvailabilityStatus(string $purpose, string $availabilityStatus): string
    {
        $purpose = strtolower(trim($purpose));
        $availabilityStatus = strtolower(trim($availabilityStatus));

        if ($purpose === 'rent') {
            return in_array($availabilityStatus, ['available', 'rented'], true)
                ? $availabilityStatus
                : 'available';
        }

        return in_array($availabilityStatus, ['available', 'sold'], true)
            ? $availabilityStatus
            : 'available';
    }

    private function propertyThumbnailUrl(Property $property): ?string
    {
        if (! $property->thumbnail_path || ! Storage::disk('public')->exists($property->thumbnail_path)) {
            return null;
        }

        return route('properties.image', ['property' => $property, 'v' => optional($property->updated_at)->timestamp]);
    }

    private function propertyGalleryUrls(Property $property): array
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

    private function availabilityLabel(string $availabilityStatus, string $purpose): string
    {
        return match (strtolower(trim($availabilityStatus))) {
            'sold' => 'Sold',
            'rented' => strtolower(trim($purpose)) === 'rent' ? 'Rented' : 'Rented',
            default => 'Still Available',
        };
    }

    private function availabilityTone(string $availabilityStatus): string
    {
        return match (strtolower(trim($availabilityStatus))) {
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
}
