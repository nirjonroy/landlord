<?php

namespace Tests\Feature;

use App\Models\HomepageCity;
use App\Models\HomepageCitySection;
use App\Models\SiteInfo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageCitySectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_city_section_uses_database_content(): void
    {
        SiteInfo::query()->create([
            'site_name' => 'Land Site',
            'site_url' => 'http://127.0.0.1:8000',
            'contact_email' => 'admin@landsite.test',
            'short_description' => 'Find land for rent and sale from one place.',
        ]);

        HomepageCitySection::query()->create([
            'title' => 'Available in Major Bangladesh Cities',
            'subtitle' => 'Edit this slider from admin.',
        ]);

        HomepageCity::query()->create([
            'name' => 'Cumilla',
            'property_count' => 24,
            'image_path' => 'frontend-assets/img/city_1.jpg',
            'image_source' => 'asset',
            'display_order' => 1,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Available in Major Bangladesh Cities')
            ->assertSee('Edit this slider from admin.')
            ->assertSee('Cumilla')
            ->assertSee('24 properties');
    }
}
