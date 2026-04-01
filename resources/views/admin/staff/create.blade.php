@extends('layouts.admin')

@section('page_title', 'Staff')
@section('page_heading', 'Create Staff')
@section('breadcrumb_current', 'Staff')

@section('content')
  @php
    $selectedRoleId = old('staff_role');
  @endphp

  <div class="card dashboard-hero mb-4">
    <div class="card-body p-4 p-lg-5 position-relative">
      <div class="row align-items-center g-4">
        <div class="col-lg-8">
          <span class="badge rounded-pill text-bg-light text-primary mb-3">Staff access manager</span>
          <h1 class="h2 fw-bold mb-3">Create staff accounts for {{ $siteName }}.</h1>
          <p class="hero-meta mb-0">
            Add new staff in a separate workspace and assign an initial role for admin access.
          </p>
        </div>
        <div class="col-lg-4">
          <div class="info-grid">
            <div class="info-tile bg-white bg-opacity-10 border-0 text-white">
              <span class="label text-white-50">Staff</span>
              <div class="value text-white">{{ $staffMembers->count() }}</div>
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
              <div class="value text-white">/admin/staff/create</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @if (session('status') === 'staff-created')
    <div class="alert alert-success">Staff account created successfully.</div>
  @endif

  @if ($errors->any())
    <div class="alert alert-danger">Please fix the highlighted fields and try again.</div>
  @endif

  <div class="row g-4 mb-4">
    <div class="col-12">
      <div class="card card-outline card-success">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
          <h3 class="card-title fw-semibold mb-0">Staff Information</h3>
          <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-shield-check me-1"></i>
            Open Roles
          </a>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('admin.staff.store') }}">
            @csrf

            <div class="row g-4">
              <div class="col-md-6">
                <label for="staff_name" class="form-label">Name</label>
                <input id="staff_name" name="staff_name" type="text" class="form-control @error('staff_name') is-invalid @enderror" value="{{ old('staff_name') }}" placeholder="Support Manager" required />
                @error('staff_name')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6">
                <label for="staff_email" class="form-label">Email</label>
                <input id="staff_email" name="staff_email" type="email" class="form-control @error('staff_email') is-invalid @enderror" value="{{ old('staff_email') }}" placeholder="staff@example.com" required />
                @error('staff_email')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6">
                <label for="staff_role" class="form-label">Role</label>
                <select id="staff_role" name="staff_role" class="form-select @error('staff_role') is-invalid @enderror" required>
                  <option value="">Select role</option>
                  @foreach ($roles as $role)
                    <option value="{{ $role->id }}" @selected((string) $selectedRoleId === (string) $role->id)>{{ $role->name }}</option>
                  @endforeach
                </select>
                @error('staff_role')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6">
                <label for="staff_password" class="form-label">Password</label>
                <input id="staff_password" name="staff_password" type="password" class="form-control @error('staff_password') is-invalid @enderror" placeholder="Minimum 8 characters" required />
                @error('staff_password')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6">
                <label for="staff_password_confirmation" class="form-label">Confirm Password</label>
                <input id="staff_password_confirmation" name="staff_password_confirmation" type="password" class="form-control" placeholder="Repeat the password" required />
              </div>
            </div>

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-4">
              <p class="text-secondary mb-0">Staff accounts are created in the <code>admins</code> table, authenticated with the <code>admin</code> guard, and inherit access from the selected role.</p>
              <button type="submit" class="btn btn-success">
                <i class="bi bi-person-plus me-1"></i>
                Create Staff
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-xl-7">
      <div class="card card-outline card-warning">
        <div class="card-header">
          <h3 class="card-title fw-semibold">Staff Directory</h3>
        </div>
        <div class="card-body">
          @forelse ($staffMembers as $staffMember)
            <div class="border rounded p-3 mb-3">
              <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                <div>
                  <div class="fw-semibold">{{ $staffMember->name }}</div>
                  <div class="text-secondary small">{{ $staffMember->email }}</div>
                </div>
                <span class="badge text-bg-light border">admin guard</span>
              </div>

              <div>
                <div class="small text-uppercase text-secondary fw-semibold mb-2">Assigned Roles</div>
                @forelse ($staffMember->roles as $role)
                  <span class="badge text-bg-light border me-1 mb-1">{{ $role->name }}</span>
                @empty
                  <span class="badge text-bg-secondary">No roles assigned</span>
                @endforelse
              </div>
            </div>
          @empty
            <div class="alert alert-light mb-0">No staff accounts found.</div>
          @endforelse
        </div>
      </div>
    </div>

    <div class="col-xl-5">
      <div class="card card-outline card-info mb-4">
        <div class="card-header">
          <h3 class="card-title fw-semibold">Access Summary</h3>
        </div>
        <div class="card-body">
          <div class="info-grid">
            <div class="info-tile">
              <span class="label">Create route</span>
              <div class="value">/admin/staff/create</div>
            </div>
            <div class="info-tile">
              <span class="label">Save route</span>
              <div class="value">POST /admin/staff</div>
            </div>
            <div class="info-tile">
              <span class="label">Role source</span>
              <div class="value">/admin/roles</div>
            </div>
            <div class="info-tile">
              <span class="label">Role access</span>
              <div class="value">Inherited from selected role</div>
            </div>
          </div>
        </div>
      </div>

      <div class="card card-outline card-secondary">
        <div class="card-header">
          <h3 class="card-title fw-semibold">Create Notes</h3>
        </div>
        <div class="card-body">
          <p class="mb-3"><strong>Role:</strong><br />Choose the initial role for the staff account. You can update role mappings later from the Roles page.</p>
          <p class="mb-3"><strong>Access:</strong><br />Staff access is controlled through roles and the permissions attached to those roles.</p>
          <p class="mb-0"><strong>Login:</strong><br />Staff use the same admin login at {{ url('/admin/login') }}.</p>
        </div>
      </div>
    </div>
  </div>
@endsection
