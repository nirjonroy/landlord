<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Property extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'purpose',
        'property_type',
        'status',
        'review_note',
        'reviewed_at',
        'reviewed_by_admin_id',
        'price',
        'area_size',
        'bedrooms',
        'bathrooms',
        'garages',
        'location',
        'district',
        'division',
        'address',
        'description',
        'contact_phone',
        'thumbnail_path',
        'gallery_paths',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'user_id' => 'integer',
        'reviewed_by_admin_id' => 'integer',
        'price' => 'integer',
        'gallery_paths' => 'array',
        'area_size' => 'decimal:2',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
        'garages' => 'integer',
        'reviewed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'reviewed_by_admin_id');
    }
}
