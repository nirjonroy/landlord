@extends('layouts.admin')

@section('page_title', 'Permissions')
@section('page_heading', 'Permissions')
@section('breadcrumb_current', 'Permissions')

@section('content')
  <div class="card dashboard-hero mb-4">
    <div class="card-body p-4 p-lg-5 position-relative">
      <div class="row align-items-center g-4">
        <div class="col-lg-8">
          <span class="badge rounded-pill text-bg-light text-primary mb-3">Admin permission manager</span>
          <h1 class="h2 fw-bold mb-3">Manage admin permissions for {{ $siteName }}.</h1>
          <p class="hero-meta mb-0">
            Create permissions here, then attach them to roles and use the separate Staff page when you want to create admin accounts.
          </p>
        </div>
        <div class="col-lg-4">
          <div class="info-grid">
            <div class="info-tile bg-white bg-opacity-10 border-0 text-white">
              <span class="label text-white-50">Permissions</span>
              <div class="value text-white">{{ $permissions->count() }}</div>
            </div>
            <div class="info-tile bg-white bg-opacity-10 border-0 text-white">
              <span class="label text-white-50">Roles</span>
              <div class="value text-white">{{ $roles->count() }}</div>
            </div>
            <div class="info-tile bg-white bg-opacity-10 border-0 text-white">
              <span class="label text-white-50">Guard</span>
              <div class="value text-white">admin</div>
            </div>
            <div class="info-tile bg-white bg-opacity-10 border-0 text-white">
              <span class="label text-white-50">Route</span>
              <div class="value text-white">/admin/permissions</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @if (session('status') === 'permission-created')
    <div class="alert alert-success">Permission created successfully.</div>
  @endif

  @if ($errors->any())
    <div class="alert alert-danger">Please fix the highlighted fields and try again.</div>
  @endif

  <div class="row g-4 mb-4">
    <div class="col-xl-4">
      <div class="card card-outline card-primary h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h3 class="card-title fw-semibold">Create Permission</h3>
          <a href="{{ route('admin.staff.create') }}" class="btn btn-outline-warning btn-sm">
            <i class="bi bi-people me-1"></i>
            Open Staff Page
          </a>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('admin.roles-permissions.permissions.store') }}">
            @csrf
            <div class="mb-3">
              <label for="permission_name" class="form-label">Permission Name</label>
              <input id="permission_name" name="permission_name" type="text" class="form-control @error('permission_name') is-invalid @enderror" value="{{ old('permission_name') }}" placeholder="reports.view" required />
              @error('permission_name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-plus-circle me-1"></i>
              Create Permission
            </button>
          </form>
        </div>
      </div>
    </div>

    <div class="col-xl-8">
      <div class="card card-outline card-info h-100">
        <div class="card-header">
          <h3 class="card-title fw-semibold">Permission Usage</h3>
        </div>
        <div class="card-body">
          <div class="row g-4">
            <div class="col-md-6">
              <div class="info-tile h-100">
                <div class="small text-uppercase text-secondary fw-semibold mb-2">Where permissions are used</div>
                <p class="mb-3">Create permission names here, attach them to roles on the Roles page, and assign direct permissions while creating staff from the Staff page.</p>
                <div class="d-flex flex-wrap gap-2">
                  <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-shield-check me-1"></i>
                    Open Roles
                  </a>
                  <a href="{{ route('admin.staff.create') }}" class="btn btn-outline-warning btn-sm">
                    <i class="bi bi-people me-1"></i>
                    Open Staff
                  </a>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="info-grid">
                <div class="info-tile">
                  <span class="label">Create route</span>
                  <div class="value">POST /admin/roles-permissions/permissions</div>
                </div>
                <div class="info-tile">
                  <span class="label">Roles page</span>
                  <div class="value">/admin/roles</div>
                </div>
                <div class="info-tile">
                  <span class="label">Staff page</span>
                  <div class="value">/admin/staff/create</div>
                </div>
                <div class="info-tile">
                  <span class="label">Guard</span>
                  <div class="value">admin</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-xl-6">
      <div class="card card-outline card-info">
        <div class="card-header">
          <h3 class="card-title fw-semibold">Available Permissions</h3>
        </div>
        <div class="card-body">
          @forelse ($permissions as $permission)
            @php
              $usedByRoles = $roles->filter(fn ($role) => $role->permissions->contains('id', $permission->id));
            @endphp
            <div class="border rounded p-3 mb-3">
              <div class="fw-semibold">{{ $permission->name }}</div>
              <div class="text-secondary small mb-2">Guard: {{ $permission->guard_name }}</div>
              <div>
                @forelse ($usedByRoles as $role)
                  <span class="badge text-bg-light border me-1 mb-1">{{ $role->name }}</span>
                @empty
                  <span class="badge text-bg-secondary">Not assigned to any role</span>
                @endforelse
              </div>
            </div>
          @empty
            <div class="alert alert-light mb-0">No permissions created yet.</div>
          @endforelse
        </div>
      </div>
    </div>

    <div class="col-xl-6">
      <div class="card card-outline card-secondary">
        <div class="card-header">
          <h3 class="card-title fw-semibold">Role Summary</h3>
        </div>
        <div class="card-body">
          @forelse ($roles as $role)
            <div class="border rounded p-3 mb-3">
              <div class="fw-semibold">{{ $role->name }}</div>
              <div class="text-secondary small mb-2">{{ $role->permissions->count() }} permissions attached</div>
              <div>
                @forelse ($role->permissions as $permission)
                  <span class="badge text-bg-light border me-1 mb-1">{{ $permission->name }}</span>
                @empty
                  <span class="badge text-bg-secondary">No permissions yet</span>
                @endforelse
              </div>
            </div>
          @empty
            <div class="text-secondary">No roles created yet.</div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
@endsection
