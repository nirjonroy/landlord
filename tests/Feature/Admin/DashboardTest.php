<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\SiteInfo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_can_be_rendered(): void
    {
        $admin = Admin::factory()->create([
            'name' => 'Template Admin',
        ]);

        $response = $this->actingAs($admin, 'admin')->get('/admin/dashboard');

        $response
            ->assertOk()
            ->assertSee('Admin Dashboard')
            ->assertSee('Template Admin')
            ->assertSee('Manage Site Info')
            ->assertSee('Open API Access')
            ->assertSee('Open Property Reviews')
            ->assertSee('Open Roles')
            ->assertSee('Open Permissions');
    }

    public function test_admin_site_info_page_can_be_rendered(): void
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'admin')
            ->get('/admin/site-info')
            ->assertOk()
            ->assertSee('Site Information')
            ->assertSee('Upload Logo')
            ->assertSee('Save Site Info');
    }

    public function test_admin_api_access_page_can_be_rendered(): void
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'admin')
            ->get('/admin/api-access')
            ->assertOk()
            ->assertSee('API Access For App')
            ->assertSee('/api/user/login')
            ->assertSee('/api/admin/login');
    }

    public function test_admin_can_update_site_information(): void
    {
        Storage::fake('public');

        $admin = Admin::factory()->create();
        $logo = UploadedFile::fake()->image('site-logo.png', 300, 300);

        $response = $this->actingAs($admin, 'admin')->put('/admin/site-info', [
            'site_name' => 'Prime Land Hub',
            'site_url' => 'https://primelandhub.test',
            'contact_email' => 'hello@primelandhub.test',
            'contact_phone' => '+8801700000000',
            'address' => 'Dhaka, Bangladesh',
            'short_description' => 'A central place to manage land listings and app data.',
            'logo' => $logo,
            'facebook_url' => 'https://facebook.com/primelandhub',
            'instagram_url' => 'https://instagram.com/primelandhub',
            'youtube_url' => 'https://youtube.com/@primelandhub',
        ]);

        $response->assertRedirect(route('admin.site-info.edit'));

        $this->assertDatabaseHas('site_infos', [
            'id' => 1,
            'site_name' => 'Prime Land Hub',
            'site_url' => 'https://primelandhub.test',
            'contact_email' => 'hello@primelandhub.test',
            'contact_phone' => '+8801700000000',
        ]);

        $siteInfo = SiteInfo::query()->findOrFail(1);

        $this->assertSame('Dhaka, Bangladesh', $siteInfo->address);
        $this->assertNotNull($siteInfo->logo_path);
        $this->assertSame('https://facebook.com/primelandhub', $siteInfo->facebook_url);
        Storage::disk('public')->assertExists($siteInfo->logo_path);

        $this->actingAs($admin, 'admin')
            ->get('/admin/site-info')
            ->assertOk()
            ->assertSee('Prime Land Hub')
            ->assertSee('hello@primelandhub.test');

        $this->actingAs($admin, 'admin')
            ->get(route('admin.site-info.logo'))
            ->assertOk();
    }
}
