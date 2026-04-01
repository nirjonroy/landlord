@extends('layouts.admin')

@section('page_title', 'Roles')
@section('page_heading', 'Roles')
@section('breadcrumb_current', 'Roles')

@section('content')
  <div class="card dashboard-hero mb-4">
    <div class="card-body p-4 p-lg-5 position-relative">
      <div class="row align-items-center g-4">
        <div class="col-lg-8">
          <span class="badge rounded-pill text-bg-light text-primary mb-3">Admin role manager</span>
          <h1 class="h2 fw-bold mb-3">Manage admin roles for {{ $siteName }}.</h1>
          <p class="hero-meta mb-0">
            Create roles, attach permissions to each role, and assign roles to admin accounts from this page. Permission creation lives on the separate Permissions page.
          </p>
        </div>
        <div class="col-lg-4">
          <div class="info-grid">
            <div class="info-tile bg-white bg-opacity-10 border-0 text-white">
              <span class="label text-white-50">Roles</span>
              <div class="value text-white">{{ $roles->count() }}</div>
            </div>
            <div class="info-tile bg-white bg-opacity-10 border-0 text-white">
              <span class="label text-white-50">Permissions</span>
              <div class="value text-white">{{ $permissions->count() }}</div>
            </div>
            <div class="info-tile bg-white bg-opacity-10 border-0 text-white">
              <span class="label text-white-50">Admins</span>
              <div class="value text-white">{{ $admins->count() }}</div>
            </div>
            <div class="info-tile bg-white bg-opacity-10 border-0 text-white">
              <span class="label text-white-50">Guard</span>
              <div class="value text-white">admin</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @if (session('status') === 'role-created')
    <div class="alert alert-success">Role created successfully.</div>
  @endif

  @if (session('status') === 'role-permissions-updated')
    <div class="alert alert-success">Role permissions updated successfully.</div>
  @endif

  @if (session('status') === 'admin-roles-updated')
    <div class="alert alert-success">Admin roles updated successfully.</div>
  @endif

  @if ($errors->any())
    <div class="alert alert-danger">Please fix the highlighted fields and try again.</div>
  @endif

  <div class="row g-4 mb-4">
    <div class="col-xl-8">
      <div class="card card-outline card-success h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h3 class="card-title fw-semibold">Create Role</h3>
          <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-key me-1"></i>
            Open Permissions Page
          </a>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('admin.roles-permissions.roles.store') }}">
            @csrf
            <div class="mb-3">
              <label for="role_name" class="form-label">Role Name</label>
              <input id="role_name" name="role_name" type="text" class="form-control @error('role_name') is-invalid @enderror" value="{{ old('role_name') }}" placeholder="manager" required />
              @error('role_name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="small text-uppercase text-secondary fw-semibold mb-2">Attach Permissions</div>
            <div class="row g-2">
              @forelse ($permissions as $permission)
                <div class="col-md-6 col-lg-4">
                  <label class="form-check info-tile h-100 mb-0">
                    <input class="form-check-input me-2" type="checkbox" name="permissions[]" value="{{ $permission->id }}" @checked(in_array($permission->id, old('permissions', []), true)) />
                    <span class="form-check-label fw-semibold">{{ $permission->name }}</span>
                  </label>
                </div>
              @empty
                <div class="col-12">
                  <div class="alert alert-light mb-0">No permissions yet. Create them on the Permissions page first.</div>
                </div>
              @endforelse
            </div>

            <div class="mt-4">
              <button type="submit" class="btn btn-success">
                <i class="bi bi-shield-plus me-1"></i>
                Create Role
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-xl-4">
      <div class="card card-outline card-primary h-100">
        <div class="card-header">
          <h3 class="card-title fw-semibold">Role Snapshot</h3>
        </div>
        <div class="card-body">
          <div class="info-grid">
            <div class="info-tile">
              <span class="label">Default role</span>
              <div class="value">super-admin</div>
            </div>
            <div class="info-tile">
              <span class="label">Permission source</span>
              <div class="value">Separate Permissions page</div>
            </div>
            <div class="info-tile">
              <span class="label">Role route</span>
              <div class="value">/admin/roles</div>
            </div>
            <div class="info-tile">
              <span class="label">Compatibility route</span>
              <div class="value">/admin/roles-permissions</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-xl-7">
      <div class="card card-outline card-info">
        <div class="card-header">
          <h3 class="card-title fw-semibold">Existing Roles</h3>
        </div>
        <div class="card-body">
          @forelse ($roles as $role)
            @php
              $rolePermissionIds = $role->permissions->pluck('id')->all();
            @endphp
            <div class="border rounded p-3 mb-3">
              <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <div>
                  <div class="fw-semibold">{{ $role->name }}</div>
                  <div class="text-secondary small">{{ $role->permissions->count() }} permissions attached</div>
                </div>
                <div>
                  @forelse ($role->permissions as $permission)
                    <span class="badge text-bg-light border me-1 mb-1">{{ $permission->name }}</span>
                  @empty
                    <span class="badge text-bg-secondary">No permissions</span>
                  @endforelse
                </div>
              </div>

              <form method="POST" action="{{ route('admin.roles-permissions.roles.permissions.update', $role) }}">
                @csrf
                @method('PUT')
                <div class="row g-2">
                  @foreach ($permissions as $permission)
                    <div class="col-md-6">
                      <label class="form-check info-tile h-100 mb-0">
                        <input class="form-check-input me-2" type="checkbox" name="permissions[]" value="{{ $permission->id }}" @checked(in_array($permission->id, $rolePermissionIds, true)) />
                        <span class="form-check-label">{{ $permission->name }}</span>
                      </label>
                    </div>
                  @endforeach
                </div>
                <button type="submit" class="btn btn-outline-info btn-sm mt-3">
                  Save Role Permissions
                </button>
              </form>
            </div>
          @empty
            <div class="alert alert-light mb-0">No roles created yet.</div>
          @endforelse
        </div>
      </div>
    </div>

    <div class="col-xl-5">
      <div class="card card-outline card-warning mb-4">
        <div class="card-header">
          <h3 class="card-title fw-semibold">Admin Role Assignment</h3>
        </div>
        <div class="card-body">
          @foreach ($admins as $managedAdmin)
            @php
              $adminRoleIds = $managedAdmin->roles->pluck('id')->all();
            @endphp
            <div class="border rounded p-3 mb-3">
              <div class="fw-semibold">{{ $managedAdmin->name }}</div>
              <div class="text-secondary small mb-3">{{ $managedAdmin->email }}</div>

              <div class="mb-3">
                @forelse ($managedAdmin->roles as $role)
                  <span class="badge text-bg-light border me-1 mb-1">{{ $role->name }}</span>
                @empty
                  <span class="badge text-bg-secondary">No roles assigned</span>
                @endforelse
              </div>

              <form method="POST" action="{{ route('admin.roles-permissions.admins.roles.update', $managedAdmin) }}">
                @csrf
                @method('PUT')
                <div class="row g-2">
                  @foreach ($roles as $role)
                    <div class="col-12">
                      <label class="form-check info-tile h-100 mb-0">
                        <input class="form-check-input me-2" type="checkbox" name="roles[]" value="{{ $role->id }}" @checked(in_array($role->id, $adminRoleIds, true)) />
                        <span class="form-check-label">{{ $role->name }}</span>
                      </label>
                    </div>
                  @endforeach
                </div>
                <button type="submit" class="btn btn-outline-warning btn-sm mt-3">
                  Update Admin Roles
                </button>
              </form>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
@endsection