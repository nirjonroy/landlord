<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PropertyType;
use App\Models\SiteInfo;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PropertyTypeController extends Controller
{
    public function index(): View
    {
        $siteInfo = $this->siteInfo();

        return view('admin.property-types.index', [
            'admin' => Auth::guard('admin')->user(),
            'siteInfo' => $siteInfo,
            'siteName' => $siteInfo->site_name ?: config('app.name', 'Land Site'),
            'siteLogoUrl' => $this->siteLogoUrl($siteInfo),
            'propertyTypes' => PropertyType::query()
                ->orderBy('display_order')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'filter_value' => ['required', 'string', 'max:255', 'unique:property_types,filter_value'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'icon_image' => ['nullable', 'file', 'mimes:svg,png,jpg,jpeg,webp', 'max:2048'],
        ]);

        $propertyType = PropertyType::query()->create([
            'name' => $validated['name'],
            'filter_value' => $validated['filter_value'],
            'display_order' => $validated['display_order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
            'show_on_home' => $request->boolean('show_on_home', true),
            'icon_path' => 'frontend-assets/img/icons/more_icon.svg',
            'icon_source' => 'asset',
        ]);

        $this->syncIcon($request, $propertyType);

        return redirect()
            ->route('admin.property-types.index')
            ->with('status', 'property-type-created');
    }

    public function update(Request $request, PropertyType $propertyType): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'filter_value' => ['required', 'string', 'max:255', Rule::unique('property_types', 'filter_value')->ignore($propertyType->id)],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'icon_image' => ['nullable', 'file', 'mimes:svg,png,jpg,jpeg,webp', 'max:2048'],
        ]);

        $propertyType->fill([
            'name' => $validated['name'],
            'filter_value' => $validated['filter_value'],
            'display_order' => $validated['display_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
            'show_on_home' => $request->boolean('show_on_home'),
        ]);

        if ($request->boolean('remove_icon')) {
            $this->deleteIcon($propertyType);
            $propertyType->icon_path = null;
            $propertyType->icon_source = 'asset';
        }

        $propertyType->save();
        $this->syncIcon($request, $propertyType);

        return redirect()
            ->route('admin.property-types.index')
            ->with('status', 'property-type-updated');
    }

    public function destroy(PropertyType $propertyType): RedirectResponse
    {
        $this->deleteIcon($propertyType);
        $propertyType->delete();

        return redirect()
            ->route('admin.property-types.index')
            ->with('status', 'property-type-deleted');
    }

    private function syncIcon(Request $request, PropertyType $propertyType): void
    {
        if (! $request->hasFile('icon_image')) {
            return;
        }

        $this->deleteIcon($propertyType);

        $propertyType->icon_path = $request->file('icon_image')->store('settings/property-types', 'public');
        $propertyType->icon_source = 'upload';
        $propertyType->save();
    }

    private function deleteIcon(PropertyType $propertyType): void
    {
        if (
            $propertyType->icon_source === 'upload' &&
            $propertyType->icon_path &&
            Storage::disk('public')->exists($propertyType->icon_path)
        ) {
            Storage::disk('public')->delete($propertyType->icon_path);
        }
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
