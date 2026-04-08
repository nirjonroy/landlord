<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\HomepageBanner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HomepageBannerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_homepage_banner_page_can_be_rendered(): void
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'admin')
            ->get('/admin/homepage-banners')
            ->assertOk()
            ->assertSee('Homepage Hero Banners')
            ->assertSee('Add New Banner')
            ->assertSee('Existing Banners');
    }

    public function test_admin_can_create_homepage_banner(): void
    {
        Storage::fake('public');

        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'admin')
            ->post(route('admin.homepage-banners.store'), [
                'heading' => 'Own property across Dhaka and beyond',
                'sub_text' => 'A Bangladesh-first banner message.',
                'display_order' => 1,
                'is_active' => 1,
                'image' => UploadedFile::fake()->image('hero-banner.jpg', 1600, 900),
            ])
            ->assertRedirect(route('admin.homepage-banners.index'));

        $banner = HomepageBanner::query()->first();

        $this->assertNotNull($banner);
        $this->assertSame('Own property across Dhaka and beyond', $banner->heading);
        $this->assertSame('upload', $banner->image_source);
        $this->assertTrue($banner->is_active);
        $this->assertNotNull($banner->image_path);
        Storage::disk('public')->assertExists($banner->image_path);
    }

    public function test_admin_can_update_homepage_banner(): void
    {
        Storage::fake('public');

        $admin = Admin::factory()->create();
        $banner = HomepageBanner::query()->create([
            'heading' => 'Original heading',
            'sub_text' => 'Original sub text',
            'image_path' => 'homepage-banners/original.jpg',
            'image_source' => 'upload',
            'display_order' => 1,
            'is_active' => true,
        ]);

        Storage::disk('public')->put('homepage-banners/original.jpg', 'old-image');

        $this->actingAs($admin, 'admin')
            ->put(route('admin.homepage-banners.update', $banner), [
                'heading' => 'Updated heading',
                'sub_text' => 'Updated sub text',
                'display_order' => 3,
                'image' => UploadedFile::fake()->image('new-banner.jpg', 1600, 900),
            ])
            ->assertRedirect(route('admin.homepage-banners.index'));

        $banner->refresh();

        $this->assertSame('Updated heading', $banner->heading);
        $this->assertSame('Updated sub text', $banner->sub_text);
        $this->assertSame(3, $banner->display_order);
        $this->assertFalse($banner->is_active);
        Storage::disk('public')->assertMissing('homepage-banners/original.jpg');
        Storage::disk('public')->assertExists($banner->image_path);
    }
}
