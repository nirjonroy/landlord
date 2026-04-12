<?php

namespace Database\Seeders;

use App\Models\HomepageProperty;
use Illuminate\Database\Seeder;

class HomepagePropertySeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $properties = [
            [
                'title' => 'Bashundhara Lake View Apartment',
                'location' => 'Bashundhara R/A, Dhaka',
                'purpose' => 'rent',
                'property_type' => 'Apartment',
                'price' => 45000,
                'bedrooms' => 3,
                'bathrooms' => 3,
                'garages' => 1,
                'area_sqft' => 1750,
                'image_path' => 'frontend-assets/img/card_img_1.jpg',
                'display_order' => 1,
            ],
            [
                'title' => 'Dhanmondi Family Flat',
                'location' => 'Dhanmondi, Dhaka',
                'purpose' => 'rent',
                'property_type' => 'Apartment',
                'price' => 38000,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'garages' => 0,
                'area_sqft' => 1450,
                'image_path' => 'frontend-assets/img/card_img_2.jpg',
                'display_order' => 2,
            ],
            [
                'title' => 'Uttara Modern Apartment',
                'location' => 'Sector 7, Uttara, Dhaka',
                'purpose' => 'rent',
                'property_type' => 'Apartment',
                'price' => 52000,
                'bedrooms' => 4,
                'bathrooms' => 3,
                'garages' => 1,
                'area_sqft' => 1980,
                'image_path' => 'frontend-assets/img/card_img_3.jpg',
                'display_order' => 3,
            ],
            [
                'title' => 'Sylhet Garden Home',
                'location' => 'Zindabazar, Sylhet',
                'purpose' => 'rent',
                'property_type' => 'House',
                'price' => 30000,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'garages' => 1,
                'area_sqft' => 1620,
                'image_path' => 'frontend-assets/img/card_img_4.jpg',
                'display_order' => 4,
            ],
            [
                'title' => 'Purbachal Residential Plot',
                'location' => 'Sector 12, Purbachal, Narayanganj',
                'purpose' => 'sale',
                'property_type' => 'Plot',
                'price' => 8500000,
                'bedrooms' => 0,
                'bathrooms' => 0,
                'garages' => 0,
                'area_sqft' => 3600,
                'image_path' => 'frontend-assets/img/card_img_5.jpg',
                'display_order' => 5,
            ],
            [
                'title' => 'Chattogram Hillside Duplex',
                'location' => 'Khulshi, Chattogram',
                'purpose' => 'sale',
                'property_type' => 'Duplex',
                'price' => 14500000,
                'bedrooms' => 4,
                'bathrooms' => 4,
                'garages' => 2,
                'area_sqft' => 2800,
                'image_path' => 'frontend-assets/img/card_img_6.jpg',
                'display_order' => 6,
            ],
            [
                'title' => 'Rajshahi Mango Garden House',
                'location' => 'Padma Residential Area, Rajshahi',
                'purpose' => 'sale',
                'property_type' => 'House',
                'price' => 7200000,
                'bedrooms' => 3,
                'bathrooms' => 3,
                'garages' => 1,
                'area_sqft' => 2100,
                'image_path' => 'frontend-assets/img/card_img_7.jpg',
                'display_order' => 7,
            ],
            [
                'title' => "Cox's Bazar Beachfront Apartment",
                'location' => "Kolatoli, Cox's Bazar",
                'purpose' => 'sale',
                'property_type' => 'Apartment',
                'price' => 11000000,
                'bedrooms' => 3,
                'bathrooms' => 3,
                'garages' => 1,
                'area_sqft' => 1850,
                'image_path' => 'frontend-assets/img/card_img_8.jpg',
                'display_order' => 8,
            ],
        ];

        foreach ($properties as $property) {
            HomepageProperty::updateOrCreate(
                ['title' => $property['title']],
                $property
            );
        }
    }
}
