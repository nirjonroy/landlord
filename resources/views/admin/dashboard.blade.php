@extends('layouts.admin')

@section('page_title', 'Dashboard')
@section('page_heading', 'Admin Dashboard')
@section('breadcrumb_current', 'Dashboard')

@section('content')
  <div class="card dashboard-hero mb-4">
    <div class="card-body p-4 p-lg-5 position-relative">
      <div class="row align-items-center g-4">
        <div class="col-lg-8">
          <span class="badge rounded-pill text-bg-light text-primary mb-3">Land management admin panel</span>
          <h1 class="h2 fw-bold mb-3">Manage {{ $siteName }} from one clean workspace.</h1>
          <p class="hero-meta mb-0">
            {{ $siteInfo->short_description ?: 'Update your public site details on the Site Info page, then reuse them later across the website and mobile app.' }}
          </p>
        </div>
        <div class="col-lg-4">
          <div class="info-grid">
            <div class="info-tile bg-white bg-opacity-10 border-0 text-white">
              <span class="label text-white-50">Current admin</span>
              <div class="value text-white">{{ $admin->name }}</div>
            </div>
            <div class="info-tile bg-white bg-opacity-10 border-0 text-white">
              <span class="label text-white-50">Environment</span>
              <div class="value text-white">{{ app()->environment() }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-3 col-6">
      <div class="small-box text-bg-primary">
        <div class="inner">
          <h3>{{ $userCount }}</h3>
          <p>Registered Users</p>
        </div>
        <i class="small-box-icon bi bi-people-fill"></i>
      </div>
    </div>
    <div class="col-lg-3 col-6">
      <div class="small-box text-bg-success">
        <div class="inner">
          <h3>{{ $adminCount }}</h3>
          <p>Admin Accounts</p>
        </div>
        <i class="small-box-icon bi bi-shield-lock-fill"></i>
      </div>
    </div>
    <div class="col-lg-3 col-6">
      <div class="small-box text-bg-warning">
        <div class="inner">
          <h3>{{ $tokenCount }}</h3>
          <p>API Tokens</p>
        </div>
        <i class="small-box-icon bi bi-key-fill"></i>
      </div>
    </div>
    <div class="col-lg-3 col-6">
      <div class="small-box text-bg-danger">
        <div class="inner">
          <h3>{{ $passwordResetCount }}</h3>
          <p>Password Reset Requests</p>
        </div>
        <i class="small-box-icon bi bi-envelope-lock-fill"></i>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-xl-8">
      <div class="card card-outline card-primary h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h3 class="card-title fw-semibold mb-0">Site Information</h3>
          <a href="{{ route('admin.site-info.edit') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-pencil-square me-1"></i>
            Manage Site Info
          </a>
        </div>
        <div class="card-body">
          <div class="info-grid">
            <div class="info-tile">
              <span class="label">Site name</span>
              <div class="value">{{ $siteInfo->site_name }}</div>
            </div>
            <div class="info-tile">
              <span class="label">Base URL</span>
              <div class="value">{{ $siteUrl }}</div>
            </div>
            <div class="info-tile">
              <span class="label">Contact email</span>
              <div class="value">{{ $siteInfo->contact_email ?: 'Not set yet' }}</div>
            </div>
            <div class="info-tile">
              <span class="label">Contact phone</span>
              <div class="value">{{ $siteInfo->contact_phone ?: 'Not set yet' }}</div>
            </div>
            <div class="info-tile">
              <span class="label">Admin login</span>
              <div class="value">{{ url('/admin/login') }}</div>
            </div>
            <div class="info-tile">
              <span class="label">User login</span>
              <div class="value">{{ url('/login') }}</div>
            </div>
          </div>

          <div class="alert alert-light quick-note mt-4 mb-0">
            <strong>Note:</strong> Use the dedicated Site Info page to update these values.
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-4">
      <div class="card card-outline card-success mb-4">
        <div class="card-header">
          <h3 class="card-title fw-semibold">Admin Account</h3>
        </div>
        <div class="card-body">
          <div class="info-grid">
            <div class="info-tile">
              <span class="label">Admin name</span>
              <div class="value">{{ $admin->name }}</div>
            </div>
            <div class="info-tile">
              <span class="label">Admin email</span>
              <div class="value">{{ $admin->email }}</div>
            </div>
            <div class="info-tile">
              <span class="label">Guard</span>
              <div class="value">admin</div>
            </div>
            <div class="info-tile">
              <span class="label">Status</span>
              <div class="value">Authenticated</div>
            </div>
          </div>
        </div>
      </div>

      <div class="card card-outline card-secondary">
        <div class="card-header">
          <h3 class="card-title fw-semibold">Current Public Snapshot</h3>
        </div>
        <div class="card-body">
          <p class="mb-3"><strong>Address:</strong><br />{{ $siteInfo->address ?: 'Not set yet' }}</p>
          <p class="mb-3"><strong>Facebook:</strong><br />{{ $siteInfo->facebook_url ?: 'Not set yet' }}</p>
          <p class="mb-3"><strong>Instagram:</strong><br />{{ $siteInfo->instagram_url ?: 'Not set yet' }}</p>
          <p class="mb-0"><strong>YouTube:</strong><br />{{ $siteInfo->youtube_url ?: 'Not set yet' }}</p>
        </div>
      </div>
    </div>

    <div class="col-12">
      <div id="api-access" class="card card-outline card-warning">
        <div class="card-header">
          <h3 class="card-title fw-semibold">API Access For App</h3>
        </div>
        <div class="card-body">
          <div class="row endpoint-list g-3">
            <div class="col-md-6">
              <div class="endpoint-item h-100">
                <span class="method">POST</span>
                <div class="fw-semibold mb-2">User Login</div>
                <code>/api/user/login</code>
                <p class="text-secondary mt-3 mb-0">Use this endpoint for app users from the `users` table.</p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="endpoint-item h-100">
                <span class="method">POST</span>
                <div class="fw-semibold mb-2">Admin Login</div>
                <code>/api/admin/login</code>
                <p class="text-secondary mt-3 mb-0">Use this endpoint for admin accounts from the `admins` table.</p>
              </div>
            </div>
          </div>
          <div class="alert alert-warning mt-4 mb-0">
            Send <code>email</code>, <code>password</code>, and <code>device_name</code> in the request body to receive a Sanctum bearer token.
          </div>
          <div class="alert alert-light quick-note mt-3 mb-0">
            <strong>Note:</strong> The default template menus, widgets, forms, tables, and extra demo links were removed so this admin panel stays focused on the land site.
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection