<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\SiteInfo;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('admin.roles.index');
    }

    public function staffIndex(): RedirectResponse
    {
        return redirect()->route('admin.staff.create');
    }

    public function createStaff(): View
    {
        return view('admin.staff.create', [
            'admin' => Auth::guard('admin')->user(),
            'siteName' => $this->siteName(),
            'staffMembers' => Admin::query()->with('roles')->orderBy('name')->get(),
            'roles' => $this->rolesQuery()->get(),
        ]);
    }

    public function roles(): View
    {
        return view('admin.roles.index', [
            'admin' => Auth::guard('admin')->user(),
            'siteName' => $this->siteName(),
            'admins' => Admin::query()->with('roles')->orderBy('name')->get(),
            'roles' => $this->rolesQuery()->with('permissions')->get(),
            'permissions' => $this->permissionsQuery()->get(),
        ]);
    }

    public function permissions(): View
    {
        return view('admin.permissions.index', [
            'admin' => Auth::guard('admin')->user(),
            'siteName' => $this->siteName(),
            'permissions' => $this->permissionsQuery()->get(),
            'roles' => $this->rolesQuery()->with('permissions')->get(),
        ]);
    }

    public function storePermission(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'permission_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('permissions')->where(fn ($query) => $query->where('guard_name', 'admin')),
            ],
        ]);

        Permission::create([
            'name' => $validated['permission_name'],
            'guard_name' => 'admin',
        ]);

        return redirect()
            ->route('admin.permissions.index')
            ->with('status', 'permission-created');
    }

    public function storeStaff(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'staff_name' => ['required', 'string', 'max:255'],
            'staff_email' => ['required', 'string', 'email', 'max:255', 'unique:admins,email'],
            'staff_password' => ['required', 'string', 'min:8', 'confirmed'],
            'staff_role' => [
                'required',
                'integer',
                Rule::exists('roles', 'id')->where(fn ($query) => $query->where('guard_name', 'admin')),
            ],
        ]);

        $staff = Admin::create([
            'name' => $validated['staff_name'],
            'email' => $validated['staff_email'],
            'password' => Hash::make($validated['staff_password']),
        ]);

        $staffRole = $this->rolesQuery()
            ->where('id', $validated['staff_role'])
            ->firstOrFail();

        $staff->syncRoles([$staffRole]);

        return redirect()
            ->route('admin.staff.create')
            ->with('status', 'staff-created');
    }

    public function storeRole(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'role_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles')->where(fn ($query) => $query->where('guard_name', 'admin')),
            ],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => [
                'integer',
                Rule::exists('permissions', 'id')->where(fn ($query) => $query->where('guard_name', 'admin')),
            ],
        ]);

        $role = Role::create([
            'name' => $validated['role_name'],
            'guard_name' => 'admin',
        ]);

        $role->syncPermissions($this->permissionsByIds($validated['permissions'] ?? []));

        return redirect()
            ->route('admin.roles.index')
            ->with('status', 'role-created');
    }

    public function updateRolePermissions(Request $request, Role $role): RedirectResponse
    {
        abort_unless($role->guard_name === 'admin', 404);

        $validated = $request->validate([
            'permissions' => ['nullable', 'array'],
            'permissions.*' => [
                'integer',
                Rule::exists('permissions', 'id')->where(fn ($query) => $query->where('guard_name', 'admin')),
            ],
        ]);

        $role->syncPermissions($this->permissionsByIds($validated['permissions'] ?? []));

        return redirect()
            ->route('admin.roles.index')
            ->with('status', 'role-permissions-updated');
    }

    public function updateAdminRoles(Request $request, Admin $admin): RedirectResponse
    {
        $validated = $request->validate([
            'roles' => ['nullable', 'array'],
            'roles.*' => [
                'integer',
                Rule::exists('roles', 'id')->where(fn ($query) => $query->where('guard_name', 'admin')),
            ],
        ]);

        $roles = Role::query()
            ->where('guard_name', 'admin')
            ->whereIn('id', $validated['roles'] ?? [])
            ->get();

        $admin->syncRoles($roles);

        return redirect()
            ->route('admin.roles.index')
            ->with('status', 'admin-roles-updated');
    }

    private function rolesQuery()
    {
        return Role::query()
            ->where('guard_name', 'admin')
            ->orderBy('name');
    }

    private function permissionsQuery()
    {
        return Permission::query()
            ->where('guard_name', 'admin')
            ->orderBy('name');
    }

    private function permissionsByIds(array $permissionIds)
    {
        return $this->permissionsQuery()
            ->whereIn('id', $permissionIds)
            ->get();
    }

    private function siteName(): string
    {
        return SiteInfo::query()->value('site_name') ?: config('app.name', 'Land Site');
    }
}
