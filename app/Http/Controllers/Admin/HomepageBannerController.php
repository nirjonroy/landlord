<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageBanner;
use App\Models\SiteInfo;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class HomepageBannerController extends Controller
{
    public function index(): View
    {
        $siteInfo = $this->siteInfo();

        return view('admin.homepage-banners.index', [
            'admin' => Auth::guard('admin')->user(),
            'siteInfo' => $siteInfo,
            'siteName' => $siteInfo->site_name ?: config('app.name', 'Land Site'),
            'siteLogoUrl' => $this->siteLogoUrl($siteInfo),
            'banners' => HomepageBanner::query()
                ->orderBy('display_order')
                ->orderBy('id')
                ->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateBanner($request, true);

        HomepageBanner::create([
            'heading' => $validated['heading'],
            'sub_text' => $validated['sub_text'] ?? null,
            'image_path' => $request->file('image')->store('homepage-banners', 'public'),
            'image_source' => 'upload',
            'display_order' => (int) ($validated['display_order'] ?? 0),
            'is_active' => (bool) $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.homepage-banners.index')
            ->with('status', 'banner-created');
    }

    public function update(Request $request, HomepageBanner $homepageBanner): RedirectResponse
    {
        $validated = $this->validateBanner($request, false);

        $homepageBanner->heading = $validated['heading'];
        $homepageBanner->sub_text = $validated['sub_text'] ?? null;
        $homepageBanner->display_order = (int) ($validated['display_order'] ?? 0);
        $homepageBanner->is_active = (bool) $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $this->deleteBannerImage($homepageBanner);
            $homepageBanner->image_path = $request->file('image')->store('homepage-banners', 'public');
            $homepageBanner->image_source = 'upload';
        }

        $homepageBanner->save();

        return redirect()
            ->route('admin.homepage-banners.index')
            ->with('status', 'banner-updated');
    }

    public function destroy(HomepageBanner $homepageBanner): RedirectResponse
    {
        $this->deleteBannerImage($homepageBanner);
        $homepageBanner->delete();

        return redirect()
            ->route('admin.homepage-banners.index')
            ->with('status', 'banner-deleted');
    }

    private function validateBanner(Request $request, bool $isCreate): array
    {
        return $request->validate([
            'heading' => ['required', 'string', 'max:255'],
            'sub_text' => ['nullable', 'string', 'max:500'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'image' => array_filter([
                $isCreate ? 'required' : 'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ]),
        ]);
    }

    private function deleteBannerImage(HomepageBanner $banner): void
    {
        if ($banner->image_source === 'upload' && $banner->image_path) {
            Storage::disk('public')->delete($banner->image_path);
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
