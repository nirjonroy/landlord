<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HomepageCity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'property_count',
        'image_path',
        'image_source',
        'display_order',
    ];

    public function imageUrl(): string
    {
        if (
            $this->image_source === 'upload' &&
            $this->image_path &&
            Storage::disk('public')->exists($this->image_path)
        ) {
            return route('homepage-cities.image', [
                'homepageCity' => $this,
                'v' => optional($this->updated_at)->timestamp,
            ]);
        }

        return asset($this->image_path ?: 'frontend-assets/img/city_1.jpg');
    }
}
