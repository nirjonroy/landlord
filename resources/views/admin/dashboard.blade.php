@php
    $admin = \Illuminate\Support\Facades\Auth::guard('admin')->user();
    $siteName = config('app.name', 'Land Site');
    $siteUrl = config('app.url', url('/')).'/';
    $userCount = \App\Models\User::count();
    $adminCount = \App\Models\Admin::count();
    $tokenCount = \Laravel\Sanctum\PersonalAccessToken::count();
    $passwordResetCount = \Illuminate\Support\Facades\DB::table('password_resets')->count();
@endphp
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $siteName }} | Admin Panel</title>
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('assets/css/adminlte.css') }}" />
    <style>
      .app-sidebar .nav-link p {
        margin-bottom: 0;
      }

      .dashboard-hero {
        background: linear-gradient(135deg, #16324f 0%, #2f6b8f 100%);
        border: 0;
        color: #fff;
        overflow: hidden;
        position: relative;
      }

      .dashboard-hero::after {
        background: rgba(255, 255, 255, 0.08);
        border-radius: 999px;
        content: '';
        height: 220px;
        position: absolute;
        right: -60px;
        top: -80px;
        width: 220px;
      }

      .dashboard-hero .hero-meta {
        color: rgba(255, 255, 255, 0.78);
      }

      .info-grid {
        display: grid;
        gap: 1rem;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      }

      .info-tile {
        background: #f8fafc;
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 1rem;
        padding: 1rem;
      }

      .info-tile .label {
        color: #64748b;
        display: block;
        font-size: 0.825rem;
        margin-bottom: 0.35rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
      }

      .info-tile .value {
        color: #0f172a;
        font-size: 1rem;
        font-weight: 600;
        word-break: break-word;
      }

      .endpoint-list .endpoint-item {
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 0.9rem;
        padding: 1rem;
      }

      .endpoint-list .method {
        background: #dbeafe;
        border-radius: 999px;
        color: #1d4ed8;
        display: inline-block;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.03em;
        margin-bottom: 0.75rem;
        padding: 0.25rem 0.7rem;
      }

      .endpoint-list code {
        color: #0f172a;
        font-size: 0.95rem;
      }

      .quick-note {
        border-left: 4px solid #0d6efd;
      }
    </style>
  </head>
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
      <nav class="app-header navbar navbar-expand bg-body">
        <div class="container-fluid">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                <i class="bi bi-list"></i>
              </a>
            </li>
            <li class="nav-item d-none d-md-block">
              <a href="{{ route('admin.dashboard') }}" class="nav-link">Dashboard</a>
            </li>
            <li class="nav-item d-none d-md-block">
              <span class="nav-link">{{ $siteName }} Admin</span>
            </li>
          </ul>

          <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown user-menu">
              <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                <img src="{{ asset('assets/assets/img/user2-160x160.jpg') }}" class="user-image rounded-circle shadow" alt="Admin Avatar" />
                <span class="d-none d-md-inline">{{ $admin->name }}</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <li class="user-header text-bg-primary">
                  <img src="{{ asset('assets/assets/img/user2-160x160.jpg') }}" class="rounded-circle shadow" alt="Admin Avatar" />
                  <p>
                    {{ $admin->name }}
                    <small>{{ $admin->email }}</small>
                  </p>
                </li>
                <li class="user-footer">
                  <a href="#site-info" class="btn btn-default btn-flat">Site Info</a>
                  <form method="POST" action="{{ route('admin.logout') }}" class="float-end">
                    @csrf
                    <button type="submit" class="btn btn-default btn-flat">Sign out</button>
                  </form>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </nav>

      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <div class="sidebar-brand">
          <a href="{{ route('admin.dashboard') }}" class="brand-link text-decoration-none">
            <img src="{{ asset('assets/assets/img/AdminLTELogo.png') }}" alt="{{ $siteName }}" class="brand-image opacity-75 shadow" />
            <span class="brand-text fw-light">{{ $siteName }}</span>
          </a>
        </div>

        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" data-accordion="false">
              <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link active">
                  <i class="nav-icon bi bi-speedometer2"></i>
                  <p>Dashboard</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#site-info" class="nav-link">
                  <i class="nav-icon bi bi-info-circle"></i>
                  <p>Site Info</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#api-access" class="nav-link">
                  <i class="nav-icon bi bi-phone"></i>
                  <p>API Access</p>
                </a>
              </li>
              <li class="nav-item mt-3 px-3">
                <div class="small text-uppercase text-secondary fw-semibold">Admin</div>
              </li>
              <li class="nav-item px-3 pb-2 text-secondary small">
                Logged in as {{ $admin->email }}
              </li>
            </ul>
          </nav>
        </div>
      </aside>

      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row align-items-center">
              <div class="col-sm-6">
                <h3 class="mb-0">Admin Dashboard</h3>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
              </div>
            </div>
          </div>
        </div>

        <div class="app-content">
          <div class="container-fluid">
            <div class="card dashboard-hero mb-4">
              <div class="card-body p-4 p-lg-5 position-relative">
                <div class="row align-items-center g-4">
                  <div class="col-lg-8">
                    <span class="badge rounded-pill text-bg-light text-primary mb-3">Land management admin panel</span>
                    <h1 class="h2 fw-bold mb-3">Manage {{ $siteName }} from one clean workspace.</h1>
                    <p class="hero-meta mb-0">
                      This dashboard keeps only the core admin items you need now: site information, admin access, and mobile app login API references.
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
              <div class="col-xl-7">
                <div id="site-info" class="card card-outline card-primary h-100">
                  <div class="card-header">
                    <h3 class="card-title fw-semibold">Site Information</h3>
                  </div>
                  <div class="card-body">
                    <div class="info-grid">
                      <div class="info-tile">
                        <span class="label">Site name</span>
                        <div class="value">{{ $siteName }}</div>
                      </div>
                      <div class="info-tile">
                        <span class="label">Base URL</span>
                        <div class="value">{{ $siteUrl }}</div>
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
                      <strong>Note:</strong> The default template menus, widgets, forms, tables, and extra demo links were removed so this admin panel stays focused on the land site.
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-xl-5">
                <div class="card card-outline card-success h-100">
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
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/adminlte.js') }}"></script>
  </body>
</html>