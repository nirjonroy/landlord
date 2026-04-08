<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\SiteInfo;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_can_be_rendered(): void
    {
        SiteInfo::query()->create([
            'site_name' => 'Land Site',
            'site_url' => 'http://127.0.0.1:8000',
            'contact_email' => 'admin@landsite.test',
            'short_description' => 'Find land for rent and sale from one place.',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Land Site')
            ->assertSee('News from Land Site')
            ->assertSee('Sign In')
            ->assertSee('Create Account')
            ->assertDontSee('Logout');
    }

    public function test_home_page_shows_dashboard_and_logout_for_logged_in_user(): void
    {
        SiteInfo::query()->create([
            'site_name' => 'Land Site',
            'site_url' => 'http://127.0.0.1:8000',
            'contact_email' => 'admin@landsite.test',
            'short_description' => 'Find land for rent and sale from one place.',
        ]);

        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/')
            ->assertOk()
            ->assertSee('Dashboard')
            ->assertSee('Logout')
            ->assertDontSee('Create Account');
    }

    public function test_home_page_shows_dashboard_and_logout_for_logged_in_admin(): void
    {
        SiteInfo::query()->create([
            'site_name' => 'Land Site',
            'site_url' => 'http://127.0.0.1:8000',
            'contact_email' => 'admin@landsite.test',
            'short_description' => 'Find land for rent and sale from one place.',
        ]);

        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'admin')
            ->get('/')
            ->assertOk()
            ->assertSee('Dashboard')
            ->assertSee('Logout')
            ->assertDontSee('Create Account');
    }

    public function test_home_page_can_show_seeded_bangladesh_demo_content(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->get('/')
            ->assertOk()
            ->assertSee('Dhaka')
            ->assertSee('Chattogram')
            ->assertSee('Find verified homes across Bangladesh')
            ->assertSee('Bashundhara Lake View Apartment')
            ->assertSee('৳45,000 /month')
            ->assertSee('We Are Available Across Bangladesh');
    }
}
