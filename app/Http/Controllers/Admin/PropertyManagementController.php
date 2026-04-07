<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\SiteInfo;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PropertyManagementController extends Controller
{
    public function index(): View
    {
        $properties = Property::query()
            ->with(['user', 'reviewedBy'])
            ->orderByRaw("case when status = 'pending' then 0 else 1 end")
            ->orderByDesc('created_at')
            ->get();

        $siteInfo = $this->siteInfo();

        return view('admin.properties.index', [
            'admin' => Auth::guard('admin')->user(),
            'siteInfo' => $siteInfo,
            'siteName' => $siteInfo->site_name ?: config('app.name', 'Land Site'),
            'siteLogoUrl' => $this->siteLogoUrl($siteInfo),
            'properties' => $properties,
            'pendingProperties' => $properties->where('status', 'pending')->values(),
            'reviewedProperties' => $properties->where('status', '!=', 'pending')->values(),
            'propertyCount' => $properties->count(),
            'pendingCount' => $properties->where('status', 'pending')->count(),
            'approvedCount' => $properties->where('status', 'approved')->count(),
            'rejectedCount' => $properties->where('status', 'rejected')->count(),
        ]);
    }

    public function updateReview(Request $request, Property $property): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['approved', 'rejected'])],
            'review_note' => [
                Rule::requiredIf($request->input('status') === 'rejected'),
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $property->status = $validated['status'];
        $property->review_note = $validated['status'] === 'rejected'
            ? trim((string) ($validated['review_note'] ?? ''))
            : ($validated['review_note'] ?? null);
        $property->reviewed_at = now();
        $property->reviewed_by_admin_id = Auth::guard('admin')->id();
        $property->save();

        return redirect()
            ->route('admin.properties.index')
            ->with('status', 'property-reviewed');
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
