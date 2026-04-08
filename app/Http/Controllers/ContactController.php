<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\ContactPage;
use App\Models\SiteInfo;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ContactController extends Controller
{
    public function index(): View
    {
        $siteInfo = $this->siteInfo();
        $contactPage = $this->contactPage();

        return view('frontend.contact', [
            'siteInfo' => $siteInfo,
            'siteName' => $siteInfo->site_name ?: config('app.name', 'Land Site'),
            'siteLogoUrl' => $this->siteLogoUrl($siteInfo),
            'siteUrl' => rtrim($siteInfo->site_url ?: config('app.url', url('/')), '/').'/',
            'contactPage' => $contactPage,
            'testimonials' => collect($contactPage->testimonials ?? [])->values(),
            'brands' => collect($contactPage->brands ?? [])->values(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        ContactMessage::query()->create($validated);

        return redirect()
            ->route('contact')
            ->with('status', 'contact-message-sent');
    }

    public function image(ContactPage $contactPage, string $group, ?int $index = null): BinaryFileResponse
    {
        $path = $contactPage->imagePathFor($group, $index);
        $source = $contactPage->imageSourceFor($group, $index);

        abort_unless(
            $source === 'upload' &&
            $path &&
            Storage::disk('public')->exists($path),
            404
        );

        return response()->file(Storage::disk('public')->path($path));
    }

    private function contactPage(): ContactPage
    {
        if (! Schema::hasTable('contact_pages')) {
            return new ContactPage(ContactPage::defaultAttributes());
        }

        return ContactPage::query()->firstOrCreate(
            ['id' => 1],
            ContactPage::defaultAttributes()
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

        return route('site.logo', ['v' => optional($siteInfo->updated_at)->timestamp]);
    }
}
