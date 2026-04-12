<?php

namespace Database\Seeders;

use App\Models\HomepageCity;
use Illuminate\Database\Seeder;

class HomepageCitySeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $cities = [
            [
                'name' => 'Dhaka',
                'property_count' => 245,
                'image_path' => 'frontend-assets/img/city_1.jpg',
                'image_source' => 'asset',
                'display_order' => 1,
            ],
            [
                'name' => 'Chattogram',
                'property_count' => 132,
                'image_path' => 'frontend-assets/img/city_2.jpg',
                'image_source' => 'asset',
                'display_order' => 2,
            ],
            [
                'name' => 'Sylhet',
                'property_count' => 96,
                'image_path' => 'frontend-assets/img/city_3.jpg',
                'image_source' => 'asset',
                'display_order' => 3,
            ],
            [
                'name' => 'Rajshahi',
                'property_count' => 74,
                'image_path' => 'frontend-assets/img/city_4.jpg',
                'image_source' => 'asset',
                'display_order' => 4,
            ],
            [
                'name' => "Cox's Bazar",
                'property_count' => 88,
                'image_path' => 'frontend-assets/img/city_5.jpg',
                'image_source' => 'asset',
                'display_order' => 5,
            ],
            [
                'name' => 'Khulna',
                'property_count' => 67,
                'image_path' => 'frontend-assets/img/city_6.jpg',
                'image_source' => 'asset',
                'display_order' => 6,
            ],
        ];

        foreach ($cities as $city) {
            HomepageCity::updateOrCreate(
                ['name' => $city['name']],
                $city
            );
        }
    }
}
