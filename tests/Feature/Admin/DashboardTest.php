<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
            ->assertSee('Template Admin');
    }
}
