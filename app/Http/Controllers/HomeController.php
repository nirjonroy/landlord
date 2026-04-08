<?php

namespace App\Http\Controllers;

use App\Models\HomepageBanner;
use App\Models\HomepageCity;
use App\Models\HomepageProperty;
use App\Models\SiteInfo;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class HomeController extends Controller
{
    public function index(): View
    {
        $siteInfo = $this->siteInfo();
        $featuredProperties = $this->homepageProperties();

        return view('frontend.home', [
            'siteInfo' => $siteInfo,
            'siteName' => $siteInfo->site_name ?: config('app.name', 'Land Site'),
            'siteLogoUrl' => $this->siteLogoUrl($siteInfo),
            'siteUrl' => rtrim($siteInfo->site_url ?: config('app.url', url('/')), '/').'/',
            'featuredProperties' => $featuredProperties,
            'rentProperties' => $featuredProperties->where('purpose', 'rent')->values(),
            'saleProperties' => $featuredProperties->where('purpose', 'sale')->values(),
            'popularCities' => $this->homepageCities(),
            'homepageBanners' => $this->homepageBanners(),
        ]);
    }

    public function siteLogo(): BinaryFileResponse
    {
        $siteInfo = $this->siteInfo();

        abort_unless(
            $siteInfo->logo_path && Storage::disk('public')->exists($siteInfo->logo_path),
            404
        );

        return response()->file(Storage::disk('public')->path($siteInfo->logo_path));
    }

    public function homepageBannerImage(HomepageBanner $homepageBanner): BinaryFileResponse
    {
        abort_unless(
            $homepageBanner->image_source === 'upload' &&
            $homepageBanner->image_path &&
            Storage::disk('public')->exists($homepageBanner->image_path),
            404
        );

        return response()->file(Storage::disk('public')->path($homepageBanner->image_path));
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

    private function homepageProperties(): Collection
    {
        if (! Schema::hasTable('homepage_properties')) {
            return collect();
        }

        return HomepageProperty::query()
            ->orderBy('display_order')
            ->get();
    }

    private function homepageCities(): Collection
    {
        if (! Schema::hasTable('homepage_cities')) {
            return collect();
        }

        return HomepageCity::query()
            ->orderBy('display_order')
            ->get();
    }

    private function homepageBanners(): Collection
    {
        if (! Schema::hasTable('homepage_banners')) {
            return collect();
        }

        return HomepageBanner::query()
            ->where('is_active', true)
            ->orderBy('display_order')
            ->orderBy('id')
            ->get();
    }
}
