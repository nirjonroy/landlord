<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomepageProperty extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'location',
        'purpose',
        'price',
        'bedrooms',
        'bathrooms',
        'garages',
        'area_sqft',
        'image_path',
        'display_order',
    ];

    public function getPurposeLabelAttribute(): string
    {
        return $this->purpose === 'sale' ? 'For Sale' : 'For Rent';
    }

    public function getFormattedPriceAttribute(): string
    {
        $suffix = $this->purpose === 'rent' ? ' /month' : '';

        return '৳'.number_format($this->price).$suffix;
    }

    public function getGarageLabelAttribute(): string
    {
        if ((int) $this->garages === 0) {
            return 'No garage';
        }

        return $this->garages.' '.((int) $this->garages === 1 ? 'garage' : 'garages');
    }

    public function getBedsLabelAttribute(): string
    {
        return $this->bedrooms.' '.((int) $this->bedrooms === 1 ? 'bed' : 'beds');
    }

    public function getBathsLabelAttribute(): string
    {
        return $this->bathrooms.' '.((int) $this->bathrooms === 1 ? 'bath' : 'baths');
    }

    public function getAreaLabelAttribute(): string
    {
        return number_format($this->area_sqft).' sqft';
    }
}
