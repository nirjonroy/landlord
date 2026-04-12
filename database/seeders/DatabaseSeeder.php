<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\SiteInfo;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        SiteInfo::updateOrCreate(
            ['id' => 1],
            [
                'site_name' => 'Land Site',
                'site_url' => config('app.url', 'http://127.0.0.1:8000'),
                'contact_email' => 'hello@landsite.com.bd',
                'contact_phone' => '+8801712345678',
                'address' => 'House 12, Road 7, Dhanmondi, Dhaka 1205, Bangladesh',
                'short_description' => 'Find verified landlord, owner, flat, and land listings across Bangladesh with prices in BDT.',
            ]
        );

        $permissions = collect([
            'dashboard.view',
            'about-page.manage',
            'blog-page.manage',
            'blog-categories.manage',
            'blog-posts.manage',
            'contact-page.manage',
            'homepage-banners.manage',
            'homepage-cities.manage',
            'property-types.manage',
            'site-info.manage',
            'api-access.view',
            'roles-permissions.manage',
        ])->map(fn ($permissionName) => Permission::findOrCreate($permissionName, 'admin'));

        $superAdminRole = Role::findOrCreate('super-admin', 'admin');
        $superAdminRole->syncPermissions($permissions);

        $admin = Admin::updateOrCreate(
            ['email' => 'admin@landsite.test'],
            [
                'name' => 'Site Admin',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]
        );

        $admin->syncRoles([$superAdminRole]);

        User::updateOrCreate(
            ['email' => 'user@landsite.test'],
            [
                'name' => 'Nirjon Roy',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]
        );

        $this->call([
            PropertyTypeSeeder::class,
            AboutPageSeeder::class,
            BlogPageSeeder::class,
            BlogCategorySeeder::class,
            BlogPostSeeder::class,
            ContactPageSeeder::class,
            HomepageBannerSeeder::class,
            HomepagePropertySeeder::class,
            HomepageCitySectionSeeder::class,
            HomepageCitySeeder::class,
            PropertySeeder::class,
        ]);
    }
}
