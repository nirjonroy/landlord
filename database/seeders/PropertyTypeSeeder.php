<?php

namespace Database\Seeders;

use App\Models\PropertyType;
use Illuminate\Database\Seeder;

class PropertyTypeSeeder extends Seeder
{
    public function run(): void
    {
        $propertyTypes = [
            ['name' => 'House', 'filter_value' => 'House', 'icon_path' => 'frontend-assets/img/icons/house_icon.svg', 'display_order' => 1, 'show_on_home' => true],
            ['name' => 'Apartment', 'filter_value' => 'Apartment', 'icon_path' => 'frontend-assets/img/icons/apartment_icon.svg', 'display_order' => 2, 'show_on_home' => true],
            ['name' => 'Villa', 'filter_value' => 'Villa', 'icon_path' => 'frontend-assets/img/icons/villa_icon.svg', 'display_order' => 3, 'show_on_home' => true],
            ['name' => 'Bungalow', 'filter_value' => 'Bungalow', 'icon_path' => 'frontend-assets/img/icons/bunglo_icon.svg', 'display_order' => 4, 'show_on_home' => true],
            ['name' => 'Townhouse', 'filter_value' => 'Townhouse', 'icon_path' => 'frontend-assets/img/icons/townhouse_icon.svg', 'display_order' => 5, 'show_on_home' => true],
            ['name' => 'Loft', 'filter_value' => 'Loft', 'icon_path' => 'frontend-assets/img/icons/loft_icon.svg', 'display_order' => 6, 'show_on_home' => true],
            ['name' => 'Office', 'filter_value' => 'Office', 'icon_path' => 'frontend-assets/img/icons/office_icon.svg', 'display_order' => 7, 'show_on_home' => true],
            ['name' => 'Duplex', 'filter_value' => 'Duplex', 'icon_path' => 'frontend-assets/img/icons/more_icon.svg', 'display_order' => 8, 'show_on_home' => false],
            ['name' => 'Shop', 'filter_value' => 'Shop', 'icon_path' => 'frontend-assets/img/icons/more_icon.svg', 'display_order' => 9, 'show_on_home' => false],
            ['name' => 'Land', 'filter_value' => 'Land', 'icon_path' => 'frontend-assets/img/icons/more_icon.svg', 'display_order' => 10, 'show_on_home' => false],
            ['name' => 'Plot', 'filter_value' => 'Plot', 'icon_path' => 'frontend-assets/img/icons/more_icon.svg', 'display_order' => 11, 'show_on_home' => false],
            ['name' => 'Commercial Space', 'filter_value' => 'Commercial Space', 'icon_path' => 'frontend-assets/img/icons/more_icon.svg', 'display_order' => 12, 'show_on_home' => false],
        ];

        foreach ($propertyTypes as $propertyType) {
            PropertyType::query()->updateOrCreate(
                ['filter_value' => $propertyType['filter_value']],
                [
                    'name' => $propertyType['name'],
                    'icon_path' => $propertyType['icon_path'],
                    'icon_source' => 'asset',
                    'display_order' => $propertyType['display_order'],
                    'is_active' => true,
                    'show_on_home' => $propertyType['show_on_home'],
                ]
            );
        }
    }
}
