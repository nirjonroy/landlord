<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomepageCitySection extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
    ];

    public static function defaultAttributes(): array
    {
        return [
            'title' => 'We Are Available Across Bangladesh',
            'subtitle' => 'Show city coverage, local demand, and where landlords are already listing properties.',
        ];
    }
}
