<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Property;
use App\Models\SiteInfo;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\PersonalAccessToken;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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

    public function siteLogo(): BinaryFileResponse
    {
        $siteInfo = $this->siteInfo();

        abort_unless(
            $siteInfo->logo_path && Storage::disk('public')->exists($siteInfo->logo_path),
            404
        );

        return response()->file(Storage::disk('public')->path($siteInfo->logo_path));
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
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg,webp', 'max:2048'],
            'remove_logo' => ['nullable', 'boolean'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'youtube_url' => ['nullable', 'url', 'max:255'],
        ]);

        $siteInfo = $this->siteInfo();

        if ($request->boolean('remove_logo')) {
            $this->deleteSiteLogo($siteInfo);
            $siteInfo->logo_path = null;
        }

        if ($request->hasFile('logo')) {
            $this->deleteSiteLogo($siteInfo);
            $siteInfo->logo_path = $request->file('logo')->store('site-info/logos', 'public');
        }

        unset($validated['logo'], $validated['remove_logo']);

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
            'siteLogoUrl' => $this->siteLogoUrl($siteInfo),
            'siteUrl' => rtrim($siteUrl, '/').'/',
            'userCount' => User::count(),
            'adminCount' => Admin::count(),
            'tokenCount' => PersonalAccessToken::count(),
            'passwordResetCount' => DB::table('password_resets')->count(),
            'propertyCount' => Property::count(),
            'pendingPropertyCount' => Property::query()->where('status', 'pending')->count(),
            'approvedPropertyCount' => Property::query()->where('status', 'approved')->count(),
            'rejectedPropertyCount' => Property::query()->where('status', 'rejected')->count(),
            'apiEndpoints' => $this->apiEndpoints(),
            'roleCount' => Role::query()->where('guard_name', 'admin')->count(),
            'permissionCount' => Permission::query()->where('guard_name', 'admin')->count(),
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

    private function siteLogoUrl(SiteInfo $siteInfo): ?string
    {
        if (! $siteInfo->logo_path || ! Storage::disk('public')->exists($siteInfo->logo_path)) {
            return null;
        }

        return route('admin.site-info.logo', ['v' => optional($siteInfo->updated_at)->timestamp]);
    }

    private function deleteSiteLogo(SiteInfo $siteInfo): void
    {
        if ($siteInfo->logo_path) {
            Storage::disk('public')->delete($siteInfo->logo_path);
        }
    }
}
