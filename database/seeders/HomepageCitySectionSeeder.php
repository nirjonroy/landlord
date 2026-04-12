<?php

namespace Database\Seeders;

use App\Models\HomepageCitySection;
use Illuminate\Database\Seeder;

class HomepageCitySectionSeeder extends Seeder
{
    public function run(): void
    {
        HomepageCitySection::query()->updateOrCreate(
            ['id' => 1],
            HomepageCitySection::defaultAttributes()
        );
    }
}
