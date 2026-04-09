<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPage;
use App\Models\BlogPost;
use App\Models\SiteInfo;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BlogPageController extends Controller
{
    public function edit(): View
    {
        $siteInfo = $this->siteInfo();

        return view('admin.blog-page.edit', [
            'admin' => Auth::guard('admin')->user(),
            'siteInfo' => $siteInfo,
            'siteName' => $siteInfo->site_name ?: config('app.name', 'Land Site'),
            'siteLogoUrl' => $this->siteLogoUrl($siteInfo),
            'blogPage' => $this->blogPage(),
            'categoryCount' => BlogCategory::count(),
            'postCount' => BlogPost::count(),
            'publishedPostCount' => BlogPost::query()->where('is_published', true)->count(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $blogPage = $this->blogPage();
        $section = $request->input('section');

        match ($section) {
            'page-header' => $this->updatePageHeader($request, $blogPage),
            'labels' => $this->updateLabels($request, $blogPage),
            default => abort(404),
        };

        $blogPage->save();

        return redirect()
            ->route('admin.blog-page.edit')
            ->withFragment($section)
            ->with('status', 'blog-page-updated')
            ->with('updated_section', $section);
    }

    private function updatePageHeader(Request $request, BlogPage $blogPage): void
    {
        $validated = $request->validate([
            'hero_title' => ['required', 'string', 'max:255'],
            'hero_background' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $blogPage->hero_title = $validated['hero_title'];

        if ($request->hasFile('hero_background')) {
            $this->deleteStoredUpload($blogPage->hero_background_path, $blogPage->hero_background_source);
            $blogPage->hero_background_path = $request->file('hero_background')->store('blog-page/header', 'public');
            $blogPage->hero_background_source = 'upload';
        }
    }

    private function updateLabels(Request $request, BlogPage $blogPage): void
    {
        $validated = $request->validate([
            'home_section_title' => ['required', 'string', 'max:255'],
            'categories_title' => ['required', 'string', 'max:255'],
            'recommendation_title' => ['required', 'string', 'max:255'],
            'latest_posts_title' => ['required', 'string', 'max:255'],
            'tags_title' => ['required', 'string', 'max:255'],
            'read_button_text' => ['required', 'string', 'max:80'],
            'article_tags_title' => ['required', 'string', 'max:255'],
            'comments_section_title' => ['required', 'string', 'max:255'],
        ]);

        $blogPage->fill($validated);
    }

    private function deleteStoredUpload(?string $path, ?string $source): void
    {
        if ($source === 'upload' && $path) {
            Storage::disk('public')->delete($path);
        }
    }

    private function blogPage(): BlogPage
    {
        return BlogPage::query()->firstOrCreate(
            ['id' => 1],
            BlogPage::defaultAttributes()
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
