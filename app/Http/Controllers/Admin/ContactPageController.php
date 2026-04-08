<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\ContactPage;
use App\Models\SiteInfo;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ContactPageController extends Controller
{
    public function edit(): View
    {
        $siteInfo = $this->siteInfo();

        return view('admin.contact-page.edit', [
            'admin' => Auth::guard('admin')->user(),
            'siteInfo' => $siteInfo,
            'siteName' => $siteInfo->site_name ?: config('app.name', 'Land Site'),
            'siteLogoUrl' => $this->siteLogoUrl($siteInfo),
            'contactPage' => $this->contactPage(),
            'messages' => ContactMessage::query()->latest()->limit(20)->get(),
            'messageCount' => ContactMessage::count(),
            'unreadMessageCount' => ContactMessage::query()->where('is_read', false)->count(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $contactPage = $this->contactPage();
        $section = $request->input('section');

        match ($section) {
            'page-header' => $this->updatePageHeader($request, $contactPage),
            'contact-form' => $this->updateContactForm($request, $contactPage),
            'testimonials' => $this->updateTestimonials($request, $contactPage),
            'brands' => $this->updateBrands($request, $contactPage),
            default => abort(404),
        };

        $contactPage->save();

        return redirect()
            ->route('admin.contact-page.edit')
            ->withFragment($section)
            ->with('status', 'contact-page-updated')
            ->with('updated_section', $section);
    }

    private function updatePageHeader(Request $request, ContactPage $contactPage): void
    {
        $validated = $request->validate([
            'hero_title' => ['required', 'string', 'max:255'],
            'hero_background' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $contactPage->hero_title = $validated['hero_title'];
        $this->replaceSingleImage($request, $contactPage, 'hero_background', 'hero_background_path', 'hero_background_source', 'contact-page/header');
    }

    private function updateContactForm(Request $request, ContactPage $contactPage): void
    {
        $validated = $request->validate([
            'form_title' => ['required', 'string', 'max:255'],
            'form_intro' => ['nullable', 'string', 'max:1000'],
            'submit_button_text' => ['required', 'string', 'max:80'],
            'success_message' => ['required', 'string', 'max:255'],
        ]);

        $contactPage->form_title = $validated['form_title'];
        $contactPage->form_intro = $validated['form_intro'] ?? null;
        $contactPage->submit_button_text = $validated['submit_button_text'];
        $contactPage->success_message = $validated['success_message'];
    }

    private function updateTestimonials(Request $request, ContactPage $contactPage): void
    {
        $validated = $request->validate([
            'testimonial_section_title' => ['required', 'string', 'max:255'],
            'testimonials' => ['required', 'array', 'size:'.ContactPage::TESTIMONIAL_COUNT],
            'testimonials.*.name' => ['required', 'string', 'max:255'],
            'testimonials.*.location' => ['required', 'string', 'max:255'],
            'testimonials.*.quote' => ['required', 'string', 'max:1200'],
            'testimonials.*.rating' => ['required', 'numeric', 'min:0', 'max:5'],
            'testimonials.*.avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $contactPage->testimonial_section_title = $validated['testimonial_section_title'];
        $contactPage->testimonials = $this->syncImageItems(
            $validated['testimonials'],
            $contactPage->testimonials ?? [],
            'contact-page/testimonials',
            ['name', 'location', 'quote', 'rating'],
            'avatar',
            'avatar_path',
            'avatar_source'
        );
    }

    private function updateBrands(Request $request, ContactPage $contactPage): void
    {
        $validated = $request->validate([
            'brands' => ['required', 'array', 'size:'.ContactPage::BRAND_COUNT],
            'brands.*.name' => ['required', 'string', 'max:255'],
            'brands.*.image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg,webp', 'max:4096'],
        ]);

        $contactPage->brands = $this->syncImageItems(
            $validated['brands'],
            $contactPage->brands ?? [],
            'contact-page/brands',
            ['name']
        );
    }

    private function syncImageItems(
        array $items,
        array $currentItems,
        string $directory,
        array $valueKeys,
        string $fileKey = 'image',
        string $pathKey = 'image_path',
        string $sourceKey = 'image_source'
    ): array {
        return collect($items)
            ->values()
            ->map(function (array $item, int $index) use ($currentItems, $directory, $valueKeys, $fileKey, $pathKey, $sourceKey) {
                $current = $currentItems[$index] ?? [];
                $payload = [];

                foreach ($valueKeys as $valueKey) {
                    $payload[$valueKey] = $item[$valueKey] ?? null;
                }

                $file = $item[$fileKey] ?? null;

                if ($file instanceof UploadedFile) {
                    $this->deleteStoredUpload($current[$pathKey] ?? null, $current[$sourceKey] ?? null);
                    $payload[$pathKey] = $file->store($directory, 'public');
                    $payload[$sourceKey] = 'upload';
                } else {
                    $payload[$pathKey] = $current[$pathKey] ?? null;
                    $payload[$sourceKey] = $current[$sourceKey] ?? null;
                }

                return $payload;
            })
            ->all();
    }

    private function replaceSingleImage(
        Request $request,
        ContactPage $contactPage,
        string $inputName,
        string $pathField,
        string $sourceField,
        string $directory
    ): void {
        if (! $request->hasFile($inputName)) {
            return;
        }

        $this->deleteStoredUpload($contactPage->{$pathField}, $contactPage->{$sourceField});
        $contactPage->{$pathField} = $request->file($inputName)->store($directory, 'public');
        $contactPage->{$sourceField} = 'upload';
    }

    private function deleteStoredUpload(?string $path, ?string $source): void
    {
        if ($source === 'upload' && $path) {
            Storage::disk('public')->delete($path);
        }
    }

    private function contactPage(): ContactPage
    {
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

        return route('admin.site-info.logo', ['v' => optional($siteInfo->updated_at)->timestamp]);
    }
}
