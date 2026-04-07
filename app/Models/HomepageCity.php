<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomepageCity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'property_count',
        'image_path',
        'display_order',
    ];
}
