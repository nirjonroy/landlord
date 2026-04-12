<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePropertyRequest;
use App\Models\Property;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PropertyController extends Controller
{
    public function store(StorePropertyRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->safe()->except([
            'property_form',
            'thumbnail_image',
            'gallery_images',
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
}
