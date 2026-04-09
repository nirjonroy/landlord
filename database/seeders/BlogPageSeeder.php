<?php

namespace Database\Seeders;

use App\Models\BlogPage;
use Illuminate\Database\Seeder;

class BlogPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BlogPage::query()->updateOrCreate(
            ['id' => 1],
            BlogPage::defaultAttributes()
        );
    }
}
