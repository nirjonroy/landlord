<?php

namespace Database\Seeders;

use App\Models\HomepageBanner;
use Illuminate\Database\Seeder;

class HomepageBannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banners = [
            [
                'heading' => 'Find verified homes across Bangladesh',
                'sub_text' => 'Search rent and sale properties from trusted landlords, owners, and verified listings in Dhaka, Chattogram, Sylhet, and beyond.',
                'image_path' => 'frontend-assets/img/hero_img_1.jpg',
                'image_source' => 'asset',
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'heading' => 'Post your flat, house, or land with confidence',
                'sub_text' => 'Landlords and owners can add properties, track approval status, and manage listing activity from one Bangladesh-focused platform.',
                'image_path' => 'frontend-assets/img/page_header_1.jpg',
                'image_source' => 'asset',
                'display_order' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($banners as $banner) {
            HomepageBanner::updateOrCreate(
                ['heading' => $banner['heading']],
                $banner
            );
        }
    }
}
