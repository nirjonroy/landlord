<?php

namespace Database\Seeders;

use App\Models\ContactPage;
use Illuminate\Database\Seeder;

class ContactPageSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        ContactPage::query()->updateOrCreate(
            ['id' => 1],
            ContactPage::defaultAttributes()
        );
    }
}
