<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class ContactPage extends Model
{
    use HasFactory;

    public const TESTIMONIAL_COUNT = 5;
    public const BRAND_COUNT = 5;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'hero_title',
        'hero_background_path',
        'hero_background_source',
        'form_title',
        'form_intro',
        'submit_button_text',
        'success_message',
        'testimonial_section_title',
        'testimonials',
        'brands',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'testimonials' => 'array',
        'brands' => 'array',
    ];

    public static function defaultAttributes(): array
    {
        return [
            'hero_title' => 'We are eager to hear from you.',
            'hero_background_path' => 'frontend-assets/img/page_header_2.jpg',
            'hero_background_source' => 'asset',
            'form_title' => 'Any Question? Catch Us Up!',
            'form_intro' => 'Ask about rent listings, sale listings, owner onboarding, verification, or general support across Bangladesh.',
            'submit_button_text' => 'Submit',
            'success_message' => 'Your message has been sent successfully. Our team will get back to you soon.',
            'testimonial_section_title' => 'What Bangladesh Clients Say',
            'testimonials' => [
                ['name' => 'Farzana Sultana', 'location' => 'Banani, Dhaka', 'quote' => 'The team answered quickly when I needed help understanding how to publish a verified property listing.', 'rating' => 5, 'avatar_path' => 'frontend-assets/img/avatar_1.jpg', 'avatar_source' => 'asset'],
                ['name' => 'Rezaul Karim', 'location' => 'Panchlaish, Chattogram', 'quote' => 'I used the contact form to ask about account setup and got a clear response that matched our local property needs.', 'rating' => 5, 'avatar_path' => 'frontend-assets/img/avatar_2.jpg', 'avatar_source' => 'asset'],
                ['name' => 'Nusrat Jahan', 'location' => 'Amberkhana, Sylhet', 'quote' => 'The platform felt organized, and the support team explained the verification steps in a practical way.', 'rating' => 5, 'avatar_path' => 'frontend-assets/img/avatar_3.jpg', 'avatar_source' => 'asset'],
                ['name' => 'Imran Hossain', 'location' => 'Khulna Sadar, Khulna', 'quote' => 'I contacted support about a pending property review and received a useful update without any confusion.', 'rating' => 4.5, 'avatar_path' => 'frontend-assets/img/avatar_4.jpg', 'avatar_source' => 'asset'],
                ['name' => 'Mariya Akter', 'location' => 'Boalia, Rajshahi', 'quote' => 'It was much easier to get help here than managing listing questions across multiple separate channels.', 'rating' => 5, 'avatar_path' => 'frontend-assets/img/avatar_5.jpg', 'avatar_source' => 'asset'],
            ],
            'brands' => [
                ['name' => 'Brand 1', 'image_path' => 'frontend-assets/img/brand_1.svg', 'image_source' => 'asset'],
                ['name' => 'Brand 2', 'image_path' => 'frontend-assets/img/brand_2.svg', 'image_source' => 'asset'],
                ['name' => 'Brand 3', 'image_path' => 'frontend-assets/img/brand_3.svg', 'image_source' => 'asset'],
                ['name' => 'Brand 4', 'image_path' => 'frontend-assets/img/brand_4.svg', 'image_source' => 'asset'],
                ['name' => 'Brand 5', 'image_path' => 'frontend-assets/img/brand_5.svg', 'image_source' => 'asset'],
            ],
        ];
    }

    public function imagePathFor(string $group, ?int $index = null): ?string
    {
        return $this->imageDataFor($group, $index)['path'] ?? null;
    }

    public function imageSourceFor(string $group, ?int $index = null): ?string
    {
        return $this->imageDataFor($group, $index)['source'] ?? null;
    }

    public function imageUrlFor(string $group, ?int $index = null): ?string
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
            Route::has('contact.image') &&
            Storage::disk('public')->exists($path)
        ) {
            return route('contact.image', [
                'contactPage' => $this,
                'group' => $group,
                'index' => $index,
                'v' => optional($this->updated_at)->timestamp,
            ]);
        }

        return null;
    }

    private function imageDataFor(string $group, ?int $index = null): array
    {
        return match ($group) {
            'hero' => [
                'path' => $this->hero_background_path,
                'source' => $this->hero_background_source,
            ],
            'testimonials' => [
                'path' => Arr::get($this->testimonials ?? [], $index.'.avatar_path'),
                'source' => Arr::get($this->testimonials ?? [], $index.'.avatar_source'),
            ],
            'brands' => [
                'path' => Arr::get($this->brands ?? [], $index.'.image_path'),
                'source' => Arr::get($this->brands ?? [], $index.'.image_source'),
            ],
            default => [],
        };
    }
}
