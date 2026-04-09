<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class BlogPage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'hero_title',
        'hero_background_path',
        'hero_background_source',
        'home_section_title',
        'categories_title',
        'recommendation_title',
        'latest_posts_title',
        'tags_title',
        'read_button_text',
        'article_tags_title',
        'comments_section_title',
    ];

    public static function defaultAttributes(): array
    {
        return [
            'hero_title' => 'News from Land Site',
            'hero_background_path' => 'frontend-assets/img/page_header_1.jpg',
            'hero_background_source' => 'asset',
            'home_section_title' => 'News from Land Site',
            'categories_title' => 'Categories',
            'recommendation_title' => 'Recommendation',
            'latest_posts_title' => 'Latest posts',
            'tags_title' => 'Popular Tags',
            'read_button_text' => 'Read Post',
            'article_tags_title' => 'Article Tags',
            'comments_section_title' => 'Comments',
        ];
    }

    public function imagePathFor(string $group = 'hero'): ?string
    {
        if ($group !== 'hero') {
            return null;
        }

        return $this->hero_background_path;
    }

    public function imageSourceFor(string $group = 'hero'): ?string
    {
        if ($group !== 'hero') {
            return null;
        }

        return $this->hero_background_source;
    }

    public function imageUrlFor(string $group = 'hero'): ?string
    {
        $path = $this->imagePathFor($group);
        $source = $this->imageSourceFor($group);

        if (! $path || ! $source) {
            return null;
        }

        if ($source === 'asset') {
            return asset($path);
        }

        if (
            $source === 'upload' &&
            Route::has('blog-page.image') &&
            Storage::disk('public')->exists($path)
        ) {
            return route('blog-page.image', [
                'blogPage' => $this,
                'v' => optional($this->updated_at)->timestamp,
            ]);
        }

        return null;
    }
}
