<?php

namespace Tests\Feature;

use App\Models\SiteInfo;
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
            ->assertSee('Sign In');
    }
}
