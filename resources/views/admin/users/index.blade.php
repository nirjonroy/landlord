@extends('layouts.admin')

@section('page_title', 'Users')
@section('page_heading', 'App Users')
@section('breadcrumb_current', 'Users')

@section('content')
  <div class="card dashboard-hero mb-4">
    <div class="card-body p-4 p-lg-5 position-relative">
      <div class="row align-items-center g-4">
        <div class="col-lg-8">
          <span class="badge rounded-pill text-bg-light text-primary mb-3">User activity overview</span>
          <h1 class="h2 fw-bold mb-3">Track the users who are using {{ $siteName }}.</h1>
          <p class="hero-meta mb-0">
            This page shows current app users, total listing posts, and the rent versus sale split whenever listing data is available.
          </p>
        </div>
        <div class="col-lg-4">
          <div class="info-grid">
            <div class="info-tile bg-white bg-opacity-10 border-0 text-white">
              <span class="label text-white-50">Users page</span>
              <div class="value text-white">/admin/users</div>
            </div>
            <div class="info-tile bg-white bg-opacity-10 border-0 text-white">
              <span class="label text-white-50">Listing source</span>
              <div class="value text-white">{{ $listingTable ?: 'Not connected yet' }}</div>
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
          <p>App Users</p>
        </div>
        <i class="small-box-icon bi bi-people-fill"></i>
      </div>
    </div>
    <div class="col-lg-3 col-6">
      <div class="small-box text-bg-success">
        <div class="inner">
          <h3>{{ $totalPostsCount }}</h3>
          <p>Total Posts</p>
        </div>
        <i class="small-box-icon bi bi-file-post-fill"></i>
      </div>
    </div>
    <div class="col-lg-3 col-6">
      <div class="small-box text-bg-warning">
        <div class="inner">
          <h3>{{ $rentPostsCount }}</h3>
          <p>Rent Posts</p>
        </div>
        <i class="small-box-icon bi bi-house-door-fill"></i>
      </div>
    </div>
    <div class="col-lg-3 col-6">
      <div class="small-box text-bg-danger">
        <div class="inner">
          <h3>{{ $salePostsCount }}</h3>
          <p>Sale Posts</p>
        </div>
        <i class="small-box-icon bi bi-cash-stack"></i>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-xl-8">
      <div class="card card-outline card-primary">
        <div class="card-header">
          <h3 class="card-title fw-semibold mb-0">Users Summary</h3>
        </div>
        <div class="card-body">
          <div class="info-grid">
            <div class="info-tile">
              <span class="label">Registered users</span>
              <div class="value">{{ $userCount }}</div>
            </div>
            <div class="info-tile">
              <span class="label">Users with posts</span>
              <div class="value">{{ $usersWithPostsCount }}</div>
            </div>
            <div class="info-tile">
              <span class="label">Listing table</span>
              <div class="value">{{ $listingTable ?: 'Waiting for future module' }}</div>
            </div>
            <div class="info-tile">
              <span class="label">Type column</span>
              <div class="value">{{ $listingTypeColumn ?: 'Rent / sale split not connected yet' }}</div>
            </div>
          </div>

          <div class="alert {{ $listingDataAvailable ? 'alert-success' : 'alert-warning' }} mt-4 mb-0">
            {{ $listingDataMessage }}
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-4">
      <div class="card card-outline card-info h-100">
        <div class="card-header">
          <h3 class="card-title fw-semibold mb-0">What You Can Track</h3>
        </div>
        <div class="card-body">
          <div class="info-grid">
            <div class="info-tile">
              <span class="label">Current users</span>
              <div class="value">Live from the users table</div>
            </div>
            <div class="info-tile">
              <span class="label">Posts per user</span>
              <div class="value">Shown below when listing data exists</div>
            </div>
            <div class="info-tile">
              <span class="label">Rent posts</span>
              <div class="value">Auto-counted from the listing type column</div>
            </div>
            <div class="info-tile">
              <span class="label">Sale posts</span>
              <div class="value">Auto-counted from the listing type column</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12">
      <div class="card card-outline card-secondary">
        <div class="card-header">
          <h3 class="card-title fw-semibold mb-0">User Directory</h3>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead>
                <tr>
                  <th class="px-3">User</th>
                  <th>Email</th>
                  <th>Joined</th>
                  <th class="text-center">Posts</th>
                  <th class="text-center">Rent</th>
                  <th class="text-center">Sale</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($users as $user)
                  <tr>
                    <td class="px-3">
                      <div class="fw-semibold">{{ $user->name }}</div>
                      <div class="text-secondary small">ID #{{ $user->id }}</div>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>{{ optional($user->created_at)->format('d M Y') ?: 'Not available' }}</td>
                    <td class="text-center">{{ $user->posts_count }}</td>
                    <td class="text-center">{{ $user->rent_posts_count }}</td>
                    <td class="text-center">{{ $user->sale_posts_count }}</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="px-3 py-4 text-center text-secondary">No app users found yet.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
