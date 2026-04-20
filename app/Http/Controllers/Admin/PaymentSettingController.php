<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use App\Models\SiteInfo;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymentSettingController extends Controller
{
    public function edit(): View
    {
        $siteInfo = $this->siteInfo();
        $paymentSetting = PaymentSetting::current();

        return view('admin.payment-settings.edit', [
            'admin' => Auth::guard('admin')->user(),
            'siteInfo' => $siteInfo,
            'siteName' => $siteInfo->site_name ?: config('app.name', 'Land Site'),
            'siteLogoUrl' => $this->siteLogoUrl($siteInfo),
            'paymentSetting' => $paymentSetting,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'sslcommerz_enabled' => ['nullable', 'boolean'],
            'sslcommerz_mode' => ['required', 'in:sandbox,live'],
            'sslcommerz_store_id' => ['nullable', 'string', 'max:255'],
            'sslcommerz_store_password' => ['nullable', 'string', 'max:255'],
            'currency' => ['required', 'string', 'max:10'],
        ]);

        $paymentSetting = PaymentSetting::current();

        $paymentSetting->fill([
            'sslcommerz_enabled' => $request->boolean('sslcommerz_enabled'),
            'sslcommerz_mode' => $validated['sslcommerz_mode'],
            'sslcommerz_store_id' => $validated['sslcommerz_store_id'] ?? null,
            'currency' => strtoupper($validated['currency']),
        ]);

        if ($request->filled('sslcommerz_store_password')) {
            $paymentSetting->sslcommerz_store_password = $validated['sslcommerz_store_password'];
        }

        if (
            $paymentSetting->sslcommerz_enabled
            && (
                blank($paymentSetting->sslcommerz_store_id)
                || blank($paymentSetting->sslcommerz_store_password)
            )
        ) {
            return redirect()
                ->route('admin.payment-settings.edit')
                ->withErrors([
                    'sslcommerz_store_id' => 'Store ID and Store Password are required before enabling SSLCommerz payments.',
                ])
                ->withInput();
        }

        $paymentSetting->save();

        return redirect()
            ->route('admin.payment-settings.edit')
            ->with('status', 'payment-settings-updated');
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
