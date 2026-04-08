<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class AboutPage extends Model
{
    use HasFactory;

    public const STAT_COUNT = 4;
    public const TEAM_MEMBER_COUNT = 6;
    public const SERVICE_COUNT = 6;
    public const TESTIMONIAL_COUNT = 5;
    public const BRAND_COUNT = 5;
    public const FAQ_COUNT = 3;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'hero_title',
        'hero_background_path',
        'hero_background_source',
        'mission_section_title',
        'mission_section_intro',
        'mission_heading',
        'mission_body',
        'mission_image_path',
        'mission_image_source',
        'vision_section_title',
        'vision_section_intro',
        'vision_heading',
        'vision_body',
        'vision_image_path',
        'vision_image_source',
        'team_section_title',
        'stats',
        'team_members',
        'services_section_title',
        'services',
        'testimonial_section_title',
        'testimonials',
        'faq_section_title',
        'faqs',
        'brands',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'stats' => 'array',
        'team_members' => 'array',
        'services' => 'array',
        'testimonials' => 'array',
        'faqs' => 'array',
        'brands' => 'array',
    ];

    public static function defaultAttributes(): array
    {
        return [
            'hero_title' => 'Built for Bangladesh landlords, owners, and property seekers.',
            'hero_background_path' => 'frontend-assets/img/page_header_3.jpg',
            'hero_background_source' => 'asset',
            'mission_section_title' => 'Our Mission',
            'mission_section_intro' => 'We simplify verified property discovery and owner onboarding across Bangladesh.',
            'mission_heading' => 'Our mission is to help landlords present trusted properties with clarity, speed, and stronger local credibility.',
            'mission_body' => "Land Site connects landlords, owners, and seekers through structured listings, document-backed profiles, and a workflow that is easier to manage from mobile and web.\n\nWe focus on practical Bangladesh needs: rent and sale visibility, area-based discovery, identity-ready profiles, and admin review tools that keep the marketplace more reliable.",
            'mission_image_path' => 'frontend-assets/img/about_img_4.png',
            'mission_image_source' => 'asset',
            'vision_section_title' => 'Our Vision',
            'vision_section_intro' => 'We are building a practical property platform for Bangladesh-first real estate journeys.',
            'vision_heading' => 'Our vision is to become a dependable digital bridge between verified property owners and serious buyers or tenants.',
            'vision_body' => "We want every owner, landlord, and local property seeker to manage listing, inquiry, and document visibility from one reliable place.\n\nFrom Dhaka apartments to district land opportunities, our goal is a marketplace where quality information, review workflows, and local context reduce uncertainty for both sides.",
            'vision_image_path' => 'frontend-assets/img/about_img_5.jpg',
            'vision_image_source' => 'asset',
            'team_section_title' => 'Meet Our Team',
            'stats' => [
                ['value' => 4200, 'label' => 'Properties Reviewed'],
                ['value' => 190, 'label' => 'Active Districts Covered'],
                ['value' => 860, 'label' => 'Owner Profiles Verified'],
                ['value' => 145, 'label' => 'Monthly Rent Matches'],
            ],
            'team_members' => [
                ['name' => 'Nabila Rahman', 'role' => 'Property Advisor', 'image_path' => 'frontend-assets/img/team_1.jpg', 'image_source' => 'asset'],
                ['name' => 'Arif Mahmud', 'role' => 'Land Sales Lead', 'image_path' => 'frontend-assets/img/team_2.jpg', 'image_source' => 'asset'],
                ['name' => 'Tasnim Chowdhury', 'role' => 'Tenant Support Manager', 'image_path' => 'frontend-assets/img/team_3.jpg', 'image_source' => 'asset'],
                ['name' => 'Sabbir Hossain', 'role' => 'Documentation Specialist', 'image_path' => 'frontend-assets/img/team_4.jpg', 'image_source' => 'asset'],
                ['name' => 'Maliha Karim', 'role' => 'Verification Officer', 'image_path' => 'frontend-assets/img/team_5.jpg', 'image_source' => 'asset'],
                ['name' => 'Fahim Kabir', 'role' => 'Owner Success Manager', 'image_path' => 'frontend-assets/img/team_3.jpg', 'image_source' => 'asset'],
            ],
            'services_section_title' => 'We Will Help You In',
            'services' => [
                ['title' => 'Landlord Onboarding', 'subtitle' => 'Services', 'image_path' => 'frontend-assets/img/city_11.jpg', 'image_source' => 'asset'],
                ['title' => 'Tenant Lead Screening', 'subtitle' => 'Services', 'image_path' => 'frontend-assets/img/city_12.jpg', 'image_source' => 'asset'],
                ['title' => 'Title Deed Guidance', 'subtitle' => 'Services', 'image_path' => 'frontend-assets/img/city_13.jpg', 'image_source' => 'asset'],
                ['title' => 'Rent Listing Support', 'subtitle' => 'Services', 'image_path' => 'frontend-assets/img/city_15.jpg', 'image_source' => 'asset'],
                ['title' => 'Sale Listing Promotion', 'subtitle' => 'Services', 'image_path' => 'frontend-assets/img/city_14.jpg', 'image_source' => 'asset'],
                ['title' => 'Property Management', 'subtitle' => 'Services', 'image_path' => 'frontend-assets/img/city_10.jpg', 'image_source' => 'asset'],
            ],
            'testimonial_section_title' => 'What Bangladesh Clients Say',
            'testimonials' => [
                ['name' => 'Rowan Jacobson', 'location' => 'Dhanmondi, Dhaka', 'quote' => 'I needed to rent out my family apartment quickly. The verification steps and admin review made the listing feel more trustworthy to serious tenants.', 'rating' => 5, 'avatar_path' => 'frontend-assets/img/avatar_1.jpg', 'avatar_source' => 'asset'],
                ['name' => 'Liliana Christ', 'location' => 'Agrabad, Chattogram', 'quote' => 'Land Site made it easier to present ownership details and get quality interest without repeating the same information everywhere.', 'rating' => 5, 'avatar_path' => 'frontend-assets/img/avatar_2.jpg', 'avatar_source' => 'asset'],
                ['name' => 'Dani Crowford', 'location' => 'Zindabazar, Sylhet', 'quote' => 'The platform felt local and practical. I could describe the area clearly and reach people who were actually looking in Sylhet.', 'rating' => 5, 'avatar_path' => 'frontend-assets/img/avatar_3.jpg', 'avatar_source' => 'asset'],
                ['name' => 'Micky Peso', 'location' => 'Sonadanga, Khulna', 'quote' => 'Listing for sale was much simpler once the dashboard and approval flow were in place. It gave me more confidence in the process.', 'rating' => 4.5, 'avatar_path' => 'frontend-assets/img/avatar_4.jpg', 'avatar_source' => 'asset'],
                ['name' => 'Kyle Jacson', 'location' => 'Shaheb Bazar, Rajshahi', 'quote' => 'As a landlord, I wanted a system that looked professional and kept my information organized. This solved that well.', 'rating' => 5, 'avatar_path' => 'frontend-assets/img/avatar_5.jpg', 'avatar_source' => 'asset'],
            ],
            'faq_section_title' => 'Frequently Asked Questions',
            'faqs' => [
                ['question' => 'How does Land Site review property submissions?', 'answer' => 'Each submitted property is marked pending first. Admins can review the owner information, listing details, and media before approving the property for broader visibility.'],
                ['question' => 'Can landlords use the same account for rent and sale listings?', 'answer' => 'Yes. A verified landlord account can submit both rent and sale properties from the same dashboard, and each listing keeps its own status.'],
                ['question' => 'What documents help build trust on the platform?', 'answer' => 'Profile photo, NID or passport details, ownership proof, and clear building or elevation images help make a landlord profile more reliable during review.'],
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
            Route::has('about.image') &&
            Storage::disk('public')->exists($path)
        ) {
            return route('about.image', [
                'aboutPage' => $this,
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
            'mission' => [
                'path' => $this->mission_image_path,
                'source' => $this->mission_image_source,
            ],
            'vision' => [
                'path' => $this->vision_image_path,
                'source' => $this->vision_image_source,
            ],
            'team' => [
                'path' => Arr::get($this->team_members ?? [], $index.'.image_path'),
                'source' => Arr::get($this->team_members ?? [], $index.'.image_source'),
            ],
            'services' => [
                'path' => Arr::get($this->services ?? [], $index.'.image_path'),
                'source' => Arr::get($this->services ?? [], $index.'.image_source'),
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
