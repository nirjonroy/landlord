<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class RolePermissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function test_old_roles_permissions_route_redirects_to_roles_page(): void
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'admin')
            ->get('/admin/roles-permissions')
            ->assertRedirect(route('admin.roles.index'));
    }

    public function test_staff_index_route_redirects_to_staff_create_page(): void
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'admin')
            ->get('/admin/staff')
            ->assertRedirect(route('admin.staff.create'));
    }

    public function test_staff_create_page_can_be_rendered(): void
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'admin')
            ->get('/admin/staff/create')
            ->assertOk()
            ->assertSee('Create Staff')
            ->assertSee('Staff Information')
            ->assertSee('Staff Directory')
            ->assertDontSee('Assigned Permissions');
    }

    public function test_roles_page_can_be_rendered(): void
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'admin')
            ->get('/admin/roles')
            ->assertOk()
            ->assertSee('Roles')
            ->assertSee('Create Role')
            ->assertSee('Existing Roles');
    }

    public function test_permissions_page_can_be_rendered(): void
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'admin')
            ->get('/admin/permissions')
            ->assertOk()
            ->assertSee('Permissions')
            ->assertSee('Create Permission')
            ->assertSee('Permission Usage')
            ->assertSee('Open Staff Page')
            ->assertDontSee('Add Staff');
    }

    public function test_admin_can_create_a_permission(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->post('/admin/roles-permissions/permissions', [
            'permission_name' => 'reports.view',
        ]);

        $response->assertRedirect(route('admin.permissions.index'));

        $this->assertDatabaseHas('permissions', [
            'name' => 'reports.view',
            'guard_name' => 'admin',
        ]);
    }

    public function test_admin_can_create_staff_with_a_role(): void
    {
        $admin = Admin::factory()->create();

        $rolePermission = Permission::create([
            'name' => 'tickets.view',
            'guard_name' => 'admin',
        ]);
        $unusedDirectPermission = Permission::create([
            'name' => 'tickets.assign',
            'guard_name' => 'admin',
        ]);
        $role = Role::create([
            'name' => 'support-agent',
            'guard_name' => 'admin',
        ]);
        $role->givePermissionTo($rolePermission);

        $response = $this->actingAs($admin, 'admin')->post('/admin/staff', [
            'staff_name' => 'Support Staff',
            'staff_email' => 'support.staff@landsite.test',
            'staff_password' => 'secret123',
            'staff_password_confirmation' => 'secret123',
            'staff_role' => $role->id,
        ]);

        $response->assertRedirect(route('admin.staff.create'));

        $this->assertDatabaseHas('admins', [
            'email' => 'support.staff@landsite.test',
            'name' => 'Support Staff',
        ]);

        $staff = Admin::query()->where('email', 'support.staff@landsite.test')->firstOrFail();

        $this->assertTrue($staff->hasRole('support-agent'));
        $this->assertTrue($staff->hasPermissionTo('tickets.view'));
        $this->assertFalse($staff->hasPermissionTo($unusedDirectPermission));
    }

    public function test_admin_can_create_a_role_with_permissions(): void
    {
        $admin = Admin::factory()->create();
        $permission = Permission::create([
            'name' => 'reports.manage',
            'guard_name' => 'admin',
        ]);

        $response = $this->actingAs($admin, 'admin')->post('/admin/roles-permissions/roles', [
            'role_name' => 'manager',
            'permissions' => [$permission->id],
        ]);

        $response->assertRedirect(route('admin.roles.index'));

        $role = Role::query()
            ->where('name', 'manager')
            ->where('guard_name', 'admin')
            ->firstOrFail();

        $this->assertTrue($role->hasPermissionTo('reports.manage'));
    }

    public function test_admin_can_update_role_permissions(): void
    {
        $admin = Admin::factory()->create();
        $role = Role::create([
            'name' => 'editor',
            'guard_name' => 'admin',
        ]);
        $permission = Permission::create([
            'name' => 'posts.edit',
            'guard_name' => 'admin',
        ]);

        $response = $this->actingAs($admin, 'admin')->put('/admin/roles-permissions/roles/'.$role->id.'/permissions', [
            'permissions' => [$permission->id],
        ]);

        $response->assertRedirect(route('admin.roles.index'));

        $this->assertTrue($role->fresh()->hasPermissionTo('posts.edit'));
    }

    public function test_admin_can_assign_roles_to_an_admin_account(): void
    {
        $admin = Admin::factory()->create();
        $managedAdmin = Admin::factory()->create();
        $role = Role::create([
            'name' => 'editor',
            'guard_name' => 'admin',
        ]);

        $response = $this->actingAs($admin, 'admin')->put('/admin/roles-permissions/admins/'.$managedAdmin->id.'/roles', [
            'roles' => [$role->id],
        ]);

        $response->assertRedirect(route('admin.roles.index'));

        $this->assertTrue($managedAdmin->fresh()->hasRole('editor'));
    }
}
