<?php

namespace App\View\Components;

use App\Models\SiteInfo;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Component;
use Illuminate\View\View;

class GuestLayout extends Component
{
    public string $siteName;

    public ?string $siteLogoUrl;

    public function __construct(?string $siteName = null, ?string $siteLogoUrl = null)
    {
        $siteInfo = null;

        if (Schema::hasTable('site_infos')) {
            $siteInfo = SiteInfo::query()->firstOrCreate(
                ['id' => 1],
                [
                    'site_name' => config('app.name', 'Land Site'),
                    'site_url' => config('app.url', url('/')),
                    'short_description' => 'Manage land listings, user accounts, and app access from a single dashboard.',
                ]
            );
        }

        $this->siteName = $siteName ?: ($siteInfo?->site_name ?: config('app.name', 'Land Site'));

        $this->siteLogoUrl = $siteLogoUrl;

        if (
            ! $this->siteLogoUrl &&
            $siteInfo?->logo_path &&
            Route::has('site.logo') &&
            Storage::disk('public')->exists($siteInfo->logo_path)
        ) {
            $this->siteLogoUrl = route('site.logo', ['v' => optional($siteInfo->updated_at)->timestamp]);
        }
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.guest');
    }
}
