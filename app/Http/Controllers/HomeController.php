<?php

namespace App\Http\Controllers;

use App\Models\AboutPage;
use App\Models\BlogPage;
use App\Models\BlogPost;
use App\Models\HomepageBanner;
use App\Models\HomepageCity;
use App\Models\HomepageCitySection;
use App\Models\HomepageProperty;
use App\Models\PropertyType;
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
            'homePropertyTypes' => $this->homePropertyTypes(),
            'homepageCitySection' => $this->homepageCitySection(),
            'popularCities' => $this->homepageCities(),
            'homepageBanners' => $this->homepageBanners(),
            'blogPage' => $this->blogPage(),
            'homeBlogPosts' => $this->homeBlogPosts(),
        ]);
    }

    public function about(): View
    {
        $siteInfo = $this->siteInfo();
        $aboutPage = $this->aboutPage();

        return view('frontend.about', [
            'siteInfo' => $siteInfo,
            'siteName' => $siteInfo->site_name ?: config('app.name', 'Land Site'),
            'siteLogoUrl' => $this->siteLogoUrl($siteInfo),
            'siteUrl' => rtrim($siteInfo->site_url ?: config('app.url', url('/')), '/').'/',
            'aboutPage' => $aboutPage,
            'stats' => collect($aboutPage->stats ?? [])->values(),
            'teamMembers' => collect($aboutPage->team_members ?? [])->values(),
            'services' => collect($aboutPage->services ?? [])->values(),
            'testimonials' => collect($aboutPage->testimonials ?? [])->values(),
            'brands' => collect($aboutPage->brands ?? [])->values(),
            'faqs' => collect($aboutPage->faqs ?? [])->values(),
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

    public function homepageCityImage(HomepageCity $homepageCity): BinaryFileResponse
    {
        abort_unless(
            $homepageCity->image_source === 'upload' &&
            $homepageCity->image_path &&
            Storage::disk('public')->exists($homepageCity->image_path),
            404
        );

        return response()->file(Storage::disk('public')->path($homepageCity->image_path));
    }

    public function propertyTypeIcon(PropertyType $propertyType): BinaryFileResponse
    {
        abort_unless(
            $propertyType->icon_source === 'upload' &&
            $propertyType->icon_path &&
            Storage::disk('public')->exists($propertyType->icon_path),
            404
        );

        return response()->file(Storage::disk('public')->path($propertyType->icon_path));
    }

    public function aboutPageImage(AboutPage $aboutPage, string $group, ?int $index = null): BinaryFileResponse
    {
        $path = $aboutPage->imagePathFor($group, $index);
        $source = $aboutPage->imageSourceFor($group, $index);

        abort_unless(
            $source === 'upload' &&
            $path &&
            Storage::disk('public')->exists($path),
            404
        );

        return response()->file(Storage::disk('public')->path($path));
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

    private function homepageCitySection(): HomepageCitySection
    {
        if (! Schema::hasTable('homepage_city_sections')) {
            return new HomepageCitySection(HomepageCitySection::defaultAttributes());
        }

        return HomepageCitySection::query()->firstOrCreate(
            ['id' => 1],
            HomepageCitySection::defaultAttributes()
        );
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

    private function homePropertyTypes(): Collection
    {
        if (! Schema::hasTable('property_types')) {
            return collect();
        }

        return PropertyType::query()
            ->active()
            ->homeVisible()
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();
    }

    private function aboutPage(): AboutPage
    {
        if (! Schema::hasTable('about_pages')) {
            return new AboutPage(AboutPage::defaultAttributes());
        }

        return AboutPage::query()->firstOrCreate(
            ['id' => 1],
            AboutPage::defaultAttributes()
        );
    }

    private function blogPage(): BlogPage
    {
        if (! Schema::hasTable('blog_pages')) {
            return new BlogPage(BlogPage::defaultAttributes());
        }

        return BlogPage::query()->firstOrCreate(
            ['id' => 1],
            BlogPage::defaultAttributes()
        );
    }

    private function homeBlogPosts(): Collection
    {
        if (! Schema::hasTable('blog_posts')) {
            return collect();
        }

        return BlogPost::query()
            ->published()
            ->where('show_on_home', true)
            ->latest('published_at')
            ->latest('id')
            ->limit(3)
            ->get();
    }
}
