<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class BlogPost extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'blog_category_id',
        'title',
        'slug',
        'author_name',
        'excerpt',
        'content',
        'quote',
        'featured_image_path',
        'featured_image_source',
        'meta_description',
        'tags',
        'comments',
        'published_at',
        'is_published',
        'show_on_home',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tags' => 'array',
        'comments' => 'array',
        'published_at' => 'datetime',
        'is_published' => 'boolean',
        'show_on_home' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function imagePathFor(string $group = 'featured', ?int $index = null): ?string
    {
        return match ($group) {
            'featured' => $this->featured_image_path,
            'comments' => Arr::get($this->comments ?? [], $index.'.avatar_path'),
            default => null,
        };
    }

    public function imageSourceFor(string $group = 'featured', ?int $index = null): ?string
    {
        return match ($group) {
            'featured' => $this->featured_image_source,
            'comments' => Arr::get($this->comments ?? [], $index.'.avatar_source'),
            default => null,
        };
    }

    public function imageUrlFor(string $group = 'featured', ?int $index = null): ?string
    {
        $path = $this->imagePathFor($group, $index);
        $source = $this->imageSourceFor($group, $index);

        if (! $path || ! $source) {
            return null;
        }

        if ($source === 'asset') {
            return asset($path);
        }

        if (
            $source === 'upload' &&
            Route::has('blog-post.image') &&
            Storage::disk('public')->exists($path)
        ) {
            return route('blog-post.image', [
                'blogPost' => $this,
                'group' => $group,
                'index' => $index,
                'v' => optional($this->updated_at)->timestamp,
            ]);
        }

        return null;
    }
}
