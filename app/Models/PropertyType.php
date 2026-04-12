<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PropertyType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'filter_value',
        'icon_path',
        'icon_source',
        'display_order',
        'is_active',
        'show_on_home',
    ];

    protected $casts = [
        'display_order' => 'integer',
        'is_active' => 'boolean',
        'show_on_home' => 'boolean',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeHomeVisible(Builder $query): Builder
    {
        return $query->where('show_on_home', true);
    }

    public function iconUrl(): string
    {
        if (
            $this->icon_source === 'upload' &&
            $this->icon_path &&
            Storage::disk('public')->exists($this->icon_path)
        ) {
            return route('property-types.icon', [
                'propertyType' => $this,
                'v' => optional($this->updated_at)->timestamp,
            ]);
        }

        return asset($this->icon_path ?: 'frontend-assets/img/icons/more_icon.svg');
    }
}
