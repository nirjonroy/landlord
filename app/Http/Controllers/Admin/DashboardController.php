<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\SiteInfo;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', $this->sharedViewData());
    }

    public function editSiteInfo(): View
    {
        return view('admin.site-info.edit', $this->sharedViewData());
    }

    public function apiAccess(): View
    {
        return view('admin.api-access.index', $this->sharedViewData());
    }

    public function updateSiteInfo(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'site_url' => ['nullable', 'url', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:1000'],
            'short_description' => ['nullable', 'string', 'max:2000'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'youtube_url' => ['nullable', 'url', 'max:255'],
        ]);

        $siteInfo = $this->siteInfo();
        $siteInfo->fill($validated);
        $siteInfo->save();

        return redirect()
            ->route('admin.site-info.edit')
            ->with('status', 'site-info-updated');
    }

    private function sharedViewData(): array
    {
        $siteInfo = $this->siteInfo();
        $siteName = $siteInfo->site_name ?: config('app.name', 'Land Site');
        $siteUrl = $siteInfo->site_url ?: config('app.url', url('/'));

        return [
            'admin' => Auth::guard('admin')->user(),
            'siteInfo' => $siteInfo,
            'siteName' => $siteName,
            'siteUrl' => rtrim($siteUrl, '/').'/',
            'userCount' => User::count(),
            'adminCount' => Admin::count(),
            'tokenCount' => PersonalAccessToken::count(),
            'passwordResetCount' => DB::table('password_resets')->count(),
            'apiEndpoints' => $this->apiEndpoints(),
        ];
    }

    private function apiEndpoints(): array
    {
        return [
            [
                'method' => 'POST',
                'title' => 'User Login',
                'path' => '/api/user/login',
                'description' => 'Use this endpoint for app users from the users table.',
                'payload' => [
                    'email' => 'user@landsite.test',
                    'password' => 'password',
                    'device_name' => 'android-app',
                ],
            ],
            [
                'method' => 'POST',
                'title' => 'Admin Login',
                'path' => '/api/admin/login',
                'description' => 'Use this endpoint for admin accounts from the admins table.',
                'payload' => [
                    'email' => 'admin@landsite.test',
                    'password' => 'password',
                    'device_name' => 'android-app',
                ],
            ],
            [
                'method' => 'GET',
                'title' => 'Authenticated User',
                'path' => '/api/user',
                'description' => 'Use this protected endpoint with a Sanctum bearer token to verify the authenticated account.',
                'headers' => [
                    'Authorization' => 'Bearer YOUR_TOKEN',
                    'Accept' => 'application/json',
                ],
            ],
        ];
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
}
