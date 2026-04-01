<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteInfo extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'site_name',
        'site_url',
        'contact_email',
        'contact_phone',
        'address',
        'short_description',
        'logo_path',
        'facebook_url',
        'instagram_url',
        'youtube_url',
    ];
}
