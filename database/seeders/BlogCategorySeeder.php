<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use Illuminate\Database\Seeder;

class BlogCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Property Tips',
                'slug' => 'property-tips',
                'description' => 'Practical guidance for buyers, tenants, and landlords in Bangladesh.',
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Landlord Management',
                'slug' => 'landlord-management',
                'description' => 'Operational advice for owner and landlord account management.',
                'display_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Documentation',
                'slug' => 'documentation',
                'description' => 'NID, ownership proof, and verification workflow content.',
                'display_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Market Updates',
                'slug' => 'market-updates',
                'description' => 'Local market trends for Dhaka and other Bangladesh cities.',
                'display_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Home Styling',
                'slug' => 'home-styling',
                'description' => 'Presentation and staging ideas for property listings.',
                'display_order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            BlogCategory::query()->updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
