<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutPage;
use App\Models\SiteInfo;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AboutPageController extends Controller
{
    public function edit(): View
    {
        $siteInfo = $this->siteInfo();

        return view('admin.about-page.edit', [
            'admin' => Auth::guard('admin')->user(),
            'siteInfo' => $siteInfo,
            'siteName' => $siteInfo->site_name ?: config('app.name', 'Land Site'),
            'siteLogoUrl' => $this->siteLogoUrl($siteInfo),
            'aboutPage' => $this->aboutPage(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $aboutPage = $this->aboutPage();
        $section = $request->input('section');

        match ($section) {
            'page-header' => $this->updatePageHeader($request, $aboutPage),
            'mission' => $this->updateMission($request, $aboutPage),
            'vision' => $this->updateVision($request, $aboutPage),
            'stats' => $this->updateStats($request, $aboutPage),
            'team' => $this->updateTeam($request, $aboutPage),
            'services' => $this->updateServices($request, $aboutPage),
            'testimonials' => $this->updateTestimonials($request, $aboutPage),
            'brands' => $this->updateBrands($request, $aboutPage),
            'faq' => $this->updateFaq($request, $aboutPage),
            default => abort(404),
        };

        $aboutPage->save();

        return redirect()
            ->route('admin.about-page.edit')
            ->withFragment($section)
            ->with('status', 'about-page-updated')
            ->with('updated_section', $section);
    }

    private function updatePageHeader(Request $request, AboutPage $aboutPage): void
    {
        $validated = $request->validate([
            'hero_title' => ['required', 'string', 'max:255'],
            'hero_background' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $aboutPage->hero_title = $validated['hero_title'];
        $this->replaceSingleImage($request, $aboutPage, 'hero_background', 'hero_background_path', 'hero_background_source', 'about-page/header');
    }

    private function updateMission(Request $request, AboutPage $aboutPage): void
    {
        $validated = $request->validate([
            'mission_section_title' => ['required', 'string', 'max:255'],
            'mission_section_intro' => ['nullable', 'string', 'max:500'],
            'mission_heading' => ['required', 'string', 'max:255'],
            'mission_body' => ['nullable', 'string', 'max:4000'],
            'mission_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $aboutPage->mission_section_title = $validated['mission_section_title'];
        $aboutPage->mission_section_intro = $validated['mission_section_intro'] ?? null;
        $aboutPage->mission_heading = $validated['mission_heading'];
        $aboutPage->mission_body = $validated['mission_body'] ?? null;
        $this->replaceSingleImage($request, $aboutPage, 'mission_image', 'mission_image_path', 'mission_image_source', 'about-page/mission');
    }

    private function updateVision(Request $request, AboutPage $aboutPage): void
    {
        $validated = $request->validate([
            'vision_section_title' => ['required', 'string', 'max:255'],
            'vision_section_intro' => ['nullable', 'string', 'max:500'],
            'vision_heading' => ['required', 'string', 'max:255'],
            'vision_body' => ['nullable', 'string', 'max:4000'],
            'vision_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $aboutPage->vision_section_title = $validated['vision_section_title'];
        $aboutPage->vision_section_intro = $validated['vision_section_intro'] ?? null;
        $aboutPage->vision_heading = $validated['vision_heading'];
        $aboutPage->vision_body = $validated['vision_body'] ?? null;
        $this->replaceSingleImage($request, $aboutPage, 'vision_image', 'vision_image_path', 'vision_image_source', 'about-page/vision');
    }

    private function updateStats(Request $request, AboutPage $aboutPage): void
    {
        $validated = $request->validate([
            'stats' => ['required', 'array', 'size:'.AboutPage::STAT_COUNT],
            'stats.*.value' => ['required', 'integer', 'min:0'],
            'stats.*.label' => ['required', 'string', 'max:255'],
        ]);

        $aboutPage->stats = collect($validated['stats'])
            ->values()
            ->map(fn (array $item) => [
                'value' => (int) $item['value'],
                'label' => $item['label'],
            ])
            ->all();
    }

    private function updateTeam(Request $request, AboutPage $aboutPage): void
    {
        $validated = $request->validate([
            'team_section_title' => ['required', 'string', 'max:255'],
            'team_members' => ['required', 'array', 'size:'.AboutPage::TEAM_MEMBER_COUNT],
            'team_members.*.name' => ['required', 'string', 'max:255'],
            'team_members.*.role' => ['required', 'string', 'max:255'],
            'team_members.*.image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $aboutPage->team_section_title = $validated['team_section_title'];
        $aboutPage->team_members = $this->syncImageItems(
            $validated['team_members'],
            $aboutPage->team_members ?? [],
            'about-page/team',
            ['name', 'role']
        );
    }

    private function updateServices(Request $request, AboutPage $aboutPage): void
    {
        $validated = $request->validate([
            'services_section_title' => ['required', 'string', 'max:255'],
            'services' => ['required', 'array', 'size:'.AboutPage::SERVICE_COUNT],
            'services.*.title' => ['required', 'string', 'max:255'],
            'services.*.subtitle' => ['nullable', 'string', 'max:255'],
            'services.*.image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $aboutPage->services_section_title = $validated['services_section_title'];
        $aboutPage->services = $this->syncImageItems(
            $validated['services'],
            $aboutPage->services ?? [],
            'about-page/services',
            ['title', 'subtitle']
        );
    }

    private function updateTestimonials(Request $request, AboutPage $aboutPage): void
    {
        $validated = $request->validate([
            'testimonial_section_title' => ['required', 'string', 'max:255'],
            'testimonials' => ['required', 'array', 'size:'.AboutPage::TESTIMONIAL_COUNT],
            'testimonials.*.name' => ['required', 'string', 'max:255'],
            'testimonials.*.location' => ['required', 'string', 'max:255'],
            'testimonials.*.quote' => ['required', 'string', 'max:1200'],
            'testimonials.*.rating' => ['required', 'numeric', 'min:0', 'max:5'],
            'testimonials.*.avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $aboutPage->testimonial_section_title = $validated['testimonial_section_title'];
        $aboutPage->testimonials = $this->syncImageItems(
            $validated['testimonials'],
            $aboutPage->testimonials ?? [],
            'about-page/testimonials',
            ['name', 'location', 'quote', 'rating'],
            'avatar',
            'avatar_path',
            'avatar_source'
        );
    }

    private function updateBrands(Request $request, AboutPage $aboutPage): void
    {
        $validated = $request->validate([
            'brands' => ['required', 'array', 'size:'.AboutPage::BRAND_COUNT],
            'brands.*.name' => ['required', 'string', 'max:255'],
            'brands.*.image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg,webp', 'max:4096'],
        ]);

        $aboutPage->brands = $this->syncImageItems(
            $validated['brands'],
            $aboutPage->brands ?? [],
            'about-page/brands',
            ['name']
        );
    }

    private function updateFaq(Request $request, AboutPage $aboutPage): void
    {
        $validated = $request->validate([
            'faq_section_title' => ['required', 'string', 'max:255'],
            'faqs' => ['required', 'array', 'size:'.AboutPage::FAQ_COUNT],
            'faqs.*.question' => ['required', 'string', 'max:255'],
            'faqs.*.answer' => ['required', 'string', 'max:2000'],
        ]);

        $aboutPage->faq_section_title = $validated['faq_section_title'];
        $aboutPage->faqs = collect($validated['faqs'])
            ->values()
            ->map(fn (array $item) => [
                'question' => $item['question'],
                'answer' => $item['answer'],
            ])
            ->all();
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
        AboutPage $aboutPage,
        string $inputName,
        string $pathField,
        string $sourceField,
        string $directory
    ): void {
        if (! $request->hasFile($inputName)) {
            return;
        }

        $this->deleteStoredUpload($aboutPage->{$pathField}, $aboutPage->{$sourceField});
        $aboutPage->{$pathField} = $request->file($inputName)->store($directory, 'public');
        $aboutPage->{$sourceField} = 'upload';
    }

    private function deleteStoredUpload(?string $path, ?string $source): void
    {
        if ($source === 'upload' && $path) {
            Storage::disk('public')->delete($path);
        }
    }

    private function aboutPage(): AboutPage
    {
        return AboutPage::query()->firstOrCreate(
            ['id' => 1],
            AboutPage::defaultAttributes()
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
