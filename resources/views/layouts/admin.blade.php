<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $siteName }} | @yield('page_title', 'Admin Panel')</title>
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('assets/css/adminlte.css') }}" />
    <style>
      .app-sidebar .nav-link p {
        margin-bottom: 0;
      }

      .brand-logo-image {
        background: rgba(255, 255, 255, 0.92);
        border-radius: 50%;
        object-fit: contain;
        padding: 0.2rem;
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

      .form-section {
        border-top: 1px solid rgba(15, 23, 42, 0.08);
        margin-top: 1.5rem;
        padding-top: 1.5rem;
      }
    </style>
    @stack('styles')
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
              <span class="nav-link">@yield('page_title', 'Admin Panel')</span>
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
                  <a href="{{ route('admin.site-info.edit') }}" class="btn btn-default btn-flat">Site Info</a>
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
            <img src="{{ $siteLogoUrl ?: asset('assets/assets/img/AdminLTELogo.png') }}" alt="{{ $siteName }}" class="brand-image opacity-75 shadow brand-logo-image" />
            <span class="brand-text fw-light">{{ $siteName }}</span>
          </a>
        </div>

        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" data-accordion="false">
              <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-speedometer2"></i>
                  <p>Dashboard</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-person-lines-fill"></i>
                  <p>Users</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.properties.index') }}" class="nav-link {{ request()->routeIs('admin.properties.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-buildings"></i>
                  <p>Properties</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.about-page.edit') }}" class="nav-link {{ request()->routeIs('admin.about-page.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-file-earmark-richtext"></i>
                  <p>About Page</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.blog-page.edit') }}" class="nav-link {{ request()->routeIs('admin.blog-page.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-journal-richtext"></i>
                  <p>Blog Page</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.blog-categories.index') }}" class="nav-link {{ request()->routeIs('admin.blog-categories.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-tags"></i>
                  <p>Blog Categories</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.blog-posts.index') }}" class="nav-link {{ request()->routeIs('admin.blog-posts.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-card-text"></i>
                  <p>Blog Posts</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.contact-page.edit') }}" class="nav-link {{ request()->routeIs('admin.contact-page.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-envelope-paper"></i>
                  <p>Contact Page</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.homepage-banners.index') }}" class="nav-link {{ request()->routeIs('admin.homepage-banners.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-images"></i>
                  <p>Hero Banners</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.site-info.edit') }}" class="nav-link {{ request()->routeIs('admin.site-info.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-info-circle"></i>
                  <p>Site Info</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.api-access.index') }}" class="nav-link {{ request()->routeIs('admin.api-access.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-phone"></i>
                  <p>API Access</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.staff.create') }}" class="nav-link {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-people"></i>
                  <p>Staff</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.roles.index') }}" class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-shield-check"></i>
                  <p>Roles</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.permissions.index') }}" class="nav-link {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-key"></i>
                  <p>Permissions</p>
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
                <h3 class="mb-0">@yield('page_heading', 'Admin Panel')</h3>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">@yield('breadcrumb_current', 'Dashboard')</li>
                </ol>
              </div>
            </div>
          </div>
        </div>

        <div class="app-content">
          <div class="container-fluid">
            @yield('content')
          </div>
        </div>
      </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/adminlte.js') }}"></script>
    @stack('scripts')
  </body>
</html>
