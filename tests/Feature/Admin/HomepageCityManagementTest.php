<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\HomepageCity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HomepageCityManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_cities_page_can_be_rendered(): void
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'admin')
            ->get(route('admin.homepage-cities.index'))
            ->assertOk()
            ->assertSee('Coverage Cities')
            ->assertSee('Section Content')
            ->assertSee('Add Coverage City');
    }

    public function test_admin_can_update_homepage_city_section(): void
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'admin')
            ->put(route('admin.homepage-cities.section.update'), [
                'title' => 'Coverage Across Bangladesh',
                'subtitle' => 'Manage local demand and city cards from admin.',
            ])
            ->assertRedirect(route('admin.homepage-cities.index'));

        $this->assertDatabaseHas('homepage_city_sections', [
            'id' => 1,
            'title' => 'Coverage Across Bangladesh',
        ]);
    }

    public function test_admin_can_create_homepage_city(): void
    {
        Storage::fake('public');
        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'admin')
            ->post(route('admin.homepage-cities.store'), [
                'name' => 'Cumilla',
                'property_count' => 24,
                'display_order' => 8,
                'image' => UploadedFile::fake()->image('cumilla.jpg', 400, 300),
            ])
            ->assertRedirect(route('admin.homepage-cities.index'));

        $this->assertDatabaseHas('homepage_cities', [
            'name' => 'Cumilla',
            'property_count' => 24,
            'display_order' => 8,
            'image_source' => 'upload',
        ]);
    }

    public function test_admin_can_update_homepage_city(): void
    {
        $admin = Admin::factory()->create();
        $city = HomepageCity::query()->create([
            'name' => 'Barishal',
            'property_count' => 10,
            'image_path' => 'frontend-assets/img/city_1.jpg',
            'image_source' => 'asset',
            'display_order' => 0,
        ]);

        $this->actingAs($admin, 'admin')
            ->put(route('admin.homepage-cities.update', $city), [
                'name' => 'Barishal City',
                'property_count' => 18,
                'display_order' => 2,
            ])
            ->assertRedirect(route('admin.homepage-cities.index'));

        $this->assertDatabaseHas('homepage_cities', [
            'id' => $city->id,
            'name' => 'Barishal City',
            'property_count' => 18,
            'display_order' => 2,
        ]);
    }
}
