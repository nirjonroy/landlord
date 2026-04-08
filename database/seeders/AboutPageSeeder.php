<?php

namespace Database\Seeders;

use App\Models\AboutPage;
use Illuminate\Database\Seeder;

class AboutPageSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        AboutPage::query()->updateOrCreate(
            ['id' => 1],
            AboutPage::defaultAttributes()
        );
    }
}
