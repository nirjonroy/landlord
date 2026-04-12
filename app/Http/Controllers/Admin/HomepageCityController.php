<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageCity;
use App\Models\HomepageCitySection;
use App\Models\SiteInfo;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HomepageCityController extends Controller
{
    public function index(): View
    {
        $siteInfo = $this->siteInfo();

        return view('admin.homepage-cities.index', [
            'admin' => Auth::guard('admin')->user(),
            'siteInfo' => $siteInfo,
            'siteName' => $siteInfo->site_name ?: config('app.name', 'Land Site'),
            'siteLogoUrl' => $this->siteLogoUrl($siteInfo),
            'citySection' => $this->citySection(),
            'cities' => HomepageCity::query()
                ->orderBy('display_order')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function updateSection(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:1000'],
        ]);

        $section = $this->citySection();
        $section->update($validated);

        return redirect()
            ->route('admin.homepage-cities.index')
            ->with('status', 'homepage-city-section-updated');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'property_count' => ['nullable', 'integer', 'min:0'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $city = HomepageCity::query()->create([
            'name' => $validated['name'],
            'property_count' => $validated['property_count'] ?? 0,
            'display_order' => $validated['display_order'] ?? 0,
            'image_path' => 'frontend-assets/img/city_1.jpg',
            'image_source' => 'asset',
        ]);

        $this->syncImage($request, $city);

        return redirect()
            ->route('admin.homepage-cities.index')
            ->with('status', 'homepage-city-created');
    }

    public function update(Request $request, HomepageCity $homepageCity): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'property_count' => ['nullable', 'integer', 'min:0'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $homepageCity->fill([
            'name' => $validated['name'],
            'property_count' => $validated['property_count'] ?? 0,
            'display_order' => $validated['display_order'] ?? 0,
        ])->save();

        if ($request->boolean('remove_image')) {
            $this->deleteImage($homepageCity);
            $homepageCity->image_path = null;
            $homepageCity->image_source = 'asset';
            $homepageCity->save();
        }

        $this->syncImage($request, $homepageCity);

        return redirect()
            ->route('admin.homepage-cities.index')
            ->with('status', 'homepage-city-updated');
    }

    public function destroy(HomepageCity $homepageCity): RedirectResponse
    {
        $this->deleteImage($homepageCity);
        $homepageCity->delete();

        return redirect()
            ->route('admin.homepage-cities.index')
            ->with('status', 'homepage-city-deleted');
    }

    private function syncImage(Request $request, HomepageCity $homepageCity): void
    {
        if (! $request->hasFile('image')) {
            return;
        }

        $this->deleteImage($homepageCity);

        $homepageCity->image_path = $request->file('image')->store('settings/homepage-cities', 'public');
        $homepageCity->image_source = 'upload';
        $homepageCity->save();
    }

    private function deleteImage(HomepageCity $homepageCity): void
    {
        if (
            $homepageCity->image_source === 'upload' &&
            $homepageCity->image_path &&
            Storage::disk('public')->exists($homepageCity->image_path)
        ) {
            Storage::disk('public')->delete($homepageCity->image_path);
        }
    }

    private function citySection(): HomepageCitySection
    {
        return HomepageCitySection::query()->firstOrCreate(
            ['id' => 1],
            HomepageCitySection::defaultAttributes()
        );
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
