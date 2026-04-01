<?php

namespace App\Providers;

use App\Models\SiteInfo;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(['auth.login', 'auth.register', 'profile.edit'], function ($view) {
            if (Schema::hasTable('site_infos')) {
                $siteInfo = SiteInfo::query()->firstOrCreate(
                    ['id' => 1],
                    [
                        'site_name' => config('app.name', 'Land Site'),
                        'site_url' => config('app.url', url('/')),
                        'short_description' => 'Manage land listings, user accounts, and app access from a single dashboard.',
                    ]
                );
            } else {
                $siteInfo = new SiteInfo([
                    'site_name' => config('app.name', 'Land Site'),
                    'site_url' => config('app.url', url('/')),
                    'short_description' => 'Manage land listings, user accounts, and app access from a single dashboard.',
                ]);
            }

            $siteLogoUrl = null;

            if (
                $siteInfo->logo_path &&
                Route::has('site.logo') &&
                Storage::disk('public')->exists($siteInfo->logo_path)
            ) {
                $siteLogoUrl = route('site.logo', ['v' => optional($siteInfo->updated_at)->timestamp]);
            }

            $view->with([
                'siteInfo' => $siteInfo,
                'siteName' => $siteInfo->site_name ?: config('app.name', 'Land Site'),
                'siteLogoUrl' => $siteLogoUrl,
            ]);
        });
    }
}
