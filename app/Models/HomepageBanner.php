<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class HomepageBanner extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'heading',
        'sub_text',
        'image_path',
        'image_source',
        'display_order',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'display_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image_path) {
            return null;
        }

        if ($this->image_source === 'asset') {
            return asset($this->image_path);
        }

        if (
            $this->image_source === 'upload' &&
            Route::has('homepage-banners.image') &&
            Storage::disk('public')->exists($this->image_path)
        ) {
            return route('homepage-banners.image', [
                'homepageBanner' => $this,
                'v' => optional($this->updated_at)->timestamp,
            ]);
        }

        return null;
    }
}
