<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\SiteInfo;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        SiteInfo::updateOrCreate(
            ['id' => 1],
            [
                'site_name' => 'Land Site',
                'site_url' => config('app.url', 'http://127.0.0.1:8000'),
                'contact_email' => 'admin@landsite.test',
                'short_description' => 'Manage land listings, user accounts, and app access from a single dashboard.',
            ]
        );

        Admin::updateOrCreate(
            ['email' => 'admin@landsite.test'],
            [
                'name' => 'Site Admin',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@landsite.test'],
            [
                'name' => 'Demo User',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]
        );
    }
}
