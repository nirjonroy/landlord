<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\SiteInfo;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BlogPostController extends Controller
{
    public function index(): View
    {
        $siteInfo = $this->siteInfo();

        return view('admin.blog-posts.index', [
            'admin' => Auth::guard('admin')->user(),
            'siteInfo' => $siteInfo,
            'siteName' => $siteInfo->site_name ?: config('app.name', 'Land Site'),
            'siteLogoUrl' => $this->siteLogoUrl($siteInfo),
            'posts' => BlogPost::query()->with('category')->latest('published_at')->latest('id')->get(),
            'postCount' => BlogPost::count(),
            'publishedPostCount' => BlogPost::query()->where('is_published', true)->count(),
            'homePostCount' => BlogPost::query()->where('show_on_home', true)->count(),
        ]);
    }

    public function create(): View
    {
        return $this->formView(new BlogPost([
            'author_name' => 'Land Site Team',
            'tags' => [],
            'comments' => [],
            'published_at' => now(),
            'is_published' => true,
            'show_on_home' => false,
        ]), 'Create Blog Post', 'Create Post', route('admin.blog-posts.store'), 'POST');
    }

    public function store(Request $request): RedirectResponse
    {
        $blogPost = new BlogPost();
        $payload = $this->validatedPayload($request, $blogPost);

        $blogPost->fill($payload);
        $blogPost->save();

        return redirect()
            ->route('admin.blog-posts.edit', $blogPost)
            ->with('status', 'blog-post-created');
    }

    public function edit(BlogPost $blogPost): View
    {
        return $this->formView($blogPost, 'Edit Blog Post', 'Save Post', route('admin.blog-posts.update', $blogPost), 'PUT');
    }

    public function update(Request $request, BlogPost $blogPost): RedirectResponse
    {
        $payload = $this->validatedPayload($request, $blogPost);
        $blogPost->fill($payload);
        $blogPost->save();

        return redirect()
            ->route('admin.blog-posts.edit', $blogPost)
            ->with('status', 'blog-post-updated');
    }

    public function destroy(BlogPost $blogPost): RedirectResponse
    {
        $this->deleteStoredUpload($blogPost->featured_image_path, $blogPost->featured_image_source);

        foreach ($blogPost->comments ?? [] as $comment) {
            $this->deleteStoredUpload($comment['avatar_path'] ?? null, $comment['avatar_source'] ?? null);
        }

        $blogPost->delete();

        return redirect()
            ->route('admin.blog-posts.index')
            ->with('status', 'blog-post-deleted');
    }

    private function formView(BlogPost $blogPost, string $pageTitle, string $submitLabel, string $action, string $method): View
    {
        $siteInfo = $this->siteInfo();

        return view('admin.blog-posts.form', [
            'admin' => Auth::guard('admin')->user(),
            'siteInfo' => $siteInfo,
            'siteName' => $siteInfo->site_name ?: config('app.name', 'Land Site'),
            'siteLogoUrl' => $this->siteLogoUrl($siteInfo),
            'blogPost' => $blogPost,
            'categories' => BlogCategory::query()->where('is_active', true)->orderBy('display_order')->orderBy('name')->get(),
            'commentSlots' => $this->commentSlots($blogPost->comments ?? []),
            'pageTitle' => $pageTitle,
            'submitLabel' => $submitLabel,
            'formAction' => $action,
            'formMethod' => $method,
        ]);
    }

    private function validatedPayload(Request $request, BlogPost $blogPost): array
    {
        $validated = $request->validate([
            'blog_category_id' => ['nullable', 'exists:blog_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('blog_posts', 'slug')->ignore($blogPost->id)],
            'author_name' => ['required', 'string', 'max:255'],
            'excerpt' => ['required', 'string', 'max:1000'],
            'content' => ['required', 'string', 'max:20000'],
            'quote' => ['nullable', 'string', 'max:1000'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'featured_image' => [$blogPost->exists ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'published_at' => ['nullable', 'date'],
            'tags_input' => ['nullable', 'string', 'max:1000'],
            'comments' => ['nullable', 'array', 'max:3'],
            'comments.*.name' => ['nullable', 'string', 'max:255'],
            'comments.*.date_label' => ['nullable', 'string', 'max:255'],
            'comments.*.body' => ['nullable', 'string', 'max:1200'],
            'comments.*.avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        if ($request->hasFile('featured_image')) {
            $this->deleteStoredUpload($blogPost->featured_image_path, $blogPost->featured_image_source);
            $featuredImagePath = $request->file('featured_image')->store('blog-posts/featured', 'public');
            $featuredImageSource = 'upload';
        } else {
            $featuredImagePath = $blogPost->featured_image_path;
            $featuredImageSource = $blogPost->featured_image_source;
        }

        $publishedAt = $validated['published_at'] ?? null;
        if ($request->boolean('is_published') && blank($publishedAt)) {
            $publishedAt = now();
        }

        return [
            'blog_category_id' => $validated['blog_category_id'] ?? null,
            'title' => $validated['title'],
            'slug' => $this->uniqueSlug($validated['slug'] ?? null, $validated['title'], $blogPost),
            'author_name' => $validated['author_name'],
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'quote' => $validated['quote'] ?? null,
            'featured_image_path' => $featuredImagePath,
            'featured_image_source' => $featuredImageSource,
            'meta_description' => $validated['meta_description'] ?? null,
            'tags' => $this->tagsFromInput($validated['tags_input'] ?? ''),
            'comments' => $this->syncComments($validated['comments'] ?? [], $blogPost->comments ?? []),
            'published_at' => $publishedAt,
            'is_published' => $request->boolean('is_published'),
            'show_on_home' => $request->boolean('show_on_home'),
        ];
    }

    private function tagsFromInput(string $tagsInput): array
    {
        return collect(explode(',', $tagsInput))
            ->map(fn (string $tag) => trim($tag))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    private function syncComments(array $comments, array $currentComments): array
    {
        return collect($comments)
            ->values()
            ->map(function (array $comment, int $index) use ($currentComments) {
                $current = $currentComments[$index] ?? [];
                $name = trim((string) ($comment['name'] ?? ''));
                $dateLabel = trim((string) ($comment['date_label'] ?? ''));
                $body = trim((string) ($comment['body'] ?? ''));
                $file = $comment['avatar'] ?? null;
                $hasText = filled($name) || filled($dateLabel) || filled($body);

                if (! $hasText && ! ($file instanceof UploadedFile)) {
                    $this->deleteStoredUpload($current['avatar_path'] ?? null, $current['avatar_source'] ?? null);

                    return null;
                }

                if ($file instanceof UploadedFile) {
                    $this->deleteStoredUpload($current['avatar_path'] ?? null, $current['avatar_source'] ?? null);
                    $avatarPath = $file->store('blog-posts/comments', 'public');
                    $avatarSource = 'upload';
                } else {
                    $avatarPath = $current['avatar_path'] ?? null;
                    $avatarSource = $current['avatar_source'] ?? null;
                }

                return [
                    'name' => $name,
                    'date_label' => $dateLabel,
                    'body' => $body,
                    'avatar_path' => $avatarPath,
                    'avatar_source' => $avatarSource,
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    private function commentSlots(array $comments): Collection
    {
        return collect(range(0, 2))
            ->map(fn (int $index) => $comments[$index] ?? [
                'name' => null,
                'date_label' => null,
                'body' => null,
                'avatar_path' => null,
                'avatar_source' => null,
            ]);
    }

    private function uniqueSlug(?string $slugInput, string $title, BlogPost $blogPost): string
    {
        $baseSlug = Str::slug($slugInput ?: $title) ?: 'blog-post';
        $slug = $baseSlug;
        $counter = 2;

        while (
            BlogPost::query()
                ->when($blogPost->exists, fn ($query) => $query->whereKeyNot($blogPost->getKey()))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    private function deleteStoredUpload(?string $path, ?string $source): void
    {
        if ($source === 'upload' && $path) {
            Storage::disk('public')->delete($path);
        }
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
