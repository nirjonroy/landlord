<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPage;
use App\Models\BlogPost;
use App\Models\SiteInfo;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $siteInfo = $this->siteInfo();
        $blogPage = $this->blogPage();
        $searchTerm = trim((string) $request->query('search', ''));
        $activeCategorySlug = trim((string) $request->query('category', ''));
        $activeTag = trim((string) $request->query('tag', ''));

        $postsQuery = $this->publishedPostsQuery()->with('category');

        if ($searchTerm !== '') {
            $postsQuery->where(function (Builder $query) use ($searchTerm): void {
                $query
                    ->where('title', 'like', '%'.$searchTerm.'%')
                    ->orWhere('excerpt', 'like', '%'.$searchTerm.'%')
                    ->orWhere('content', 'like', '%'.$searchTerm.'%')
                    ->orWhere('author_name', 'like', '%'.$searchTerm.'%');
            });
        }

        if ($activeCategorySlug !== '') {
            $postsQuery->whereHas('category', function (Builder $query) use ($activeCategorySlug): void {
                $query->where('slug', $activeCategorySlug);
            });
        }

        if ($activeTag !== '') {
            $postsQuery->where('tags', 'like', '%"'.str_replace('"', '\"', $activeTag).'"%');
        }

        $posts = $postsQuery
            ->latest('published_at')
            ->latest('id')
            ->paginate(6)
            ->withQueryString();

        return view('frontend.blog', [
            'siteInfo' => $siteInfo,
            'siteName' => $siteInfo->site_name ?: config('app.name', 'Land Site'),
            'siteLogoUrl' => $this->siteLogoUrl($siteInfo),
            'siteUrl' => rtrim($siteInfo->site_url ?: config('app.url', url('/')), '/').'/',
            'blogPage' => $blogPage,
            'posts' => $posts,
            'categories' => $this->categories(),
            'latestPosts' => $this->publishedPostsQuery()->with('category')->latest('published_at')->limit(3)->get(),
            'popularTags' => $this->popularTags(),
            'searchTerm' => $searchTerm,
            'activeCategorySlug' => $activeCategorySlug,
            'activeTag' => $activeTag,
        ]);
    }

    public function show(BlogPost $blogPost): View
    {
        abort_unless(
            $blogPost->is_published &&
            $blogPost->published_at &&
            $blogPost->published_at->lte(now()),
            404
        );

        $siteInfo = $this->siteInfo();
        $blogPage = $this->blogPage();
        $blogPost->load('category');

        return view('frontend.blog-show', [
            'siteInfo' => $siteInfo,
            'siteName' => $siteInfo->site_name ?: config('app.name', 'Land Site'),
            'siteLogoUrl' => $this->siteLogoUrl($siteInfo),
            'siteUrl' => rtrim($siteInfo->site_url ?: config('app.url', url('/')), '/').'/',
            'blogPage' => $blogPage,
            'blogPost' => $blogPost,
            'categories' => $this->categories(),
            'latestPosts' => $this->publishedPostsQuery()
                ->whereKeyNot($blogPost->getKey())
                ->with('category')
                ->latest('published_at')
                ->limit(3)
                ->get(),
            'popularTags' => $this->popularTags(),
            'articleTags' => collect($blogPost->tags ?? [])->filter(),
            'comments' => collect($blogPost->comments ?? [])
                ->filter(fn (array $comment) => filled($comment['name'] ?? null) && filled($comment['body'] ?? null))
                ->values(),
        ]);
    }

    public function pageImage(BlogPage $blogPage): BinaryFileResponse
    {
        abort_unless(
            $blogPage->hero_background_source === 'upload' &&
            $blogPage->hero_background_path &&
            Storage::disk('public')->exists($blogPage->hero_background_path),
            404
        );

        return response()->file(Storage::disk('public')->path($blogPage->hero_background_path));
    }

    public function postImage(BlogPost $blogPost, string $group, ?int $index = null): BinaryFileResponse
    {
        $path = $blogPost->imagePathFor($group, $index);
        $source = $blogPost->imageSourceFor($group, $index);

        abort_unless(
            $source === 'upload' &&
            $path &&
            Storage::disk('public')->exists($path),
            404
        );

        return response()->file(Storage::disk('public')->path($path));
    }

    private function publishedPostsQuery(): Builder
    {
        if (! Schema::hasTable('blog_posts')) {
            return BlogPost::query()->whereRaw('1 = 0');
        }

        return BlogPost::query()->published();
    }

    private function categories(): Collection
    {
        if (! Schema::hasTable('blog_categories')) {
            return collect();
        }

        return BlogCategory::query()
            ->where('is_active', true)
            ->withCount([
                'posts as published_posts_count' => fn (Builder $query) => $query->published(),
            ])
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();
    }

    private function popularTags(): Collection
    {
        return $this->publishedPostsQuery()
            ->get()
            ->flatMap(fn (BlogPost $post) => collect($post->tags ?? []))
            ->filter()
            ->countBy()
            ->sortDesc()
            ->take(9)
            ->keys()
            ->values();
    }

    private function blogPage(): BlogPage
    {
        if (! Schema::hasTable('blog_pages')) {
            return new BlogPage(BlogPage::defaultAttributes());
        }

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

        return route('site.logo', ['v' => optional($siteInfo->updated_at)->timestamp]);
    }
}
