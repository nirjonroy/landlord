@extends('layouts.admin')

@section('page_title', 'API Access')
@section('page_heading', 'API Access For App')
@section('breadcrumb_current', 'API Access')

@section('content')
  <div class="card dashboard-hero mb-4">
    <div class="card-body p-4 p-lg-5 position-relative">
      <div class="row align-items-center g-4">
        <div class="col-lg-8">
          <span class="badge rounded-pill text-bg-light text-primary mb-3">Dedicated API page</span>
          <h1 class="h2 fw-bold mb-3">Use these endpoints for the mobile app and admin integrations.</h1>
          <p class="hero-meta mb-0">
            This page groups the current authentication endpoints, sample payloads, and token usage details so the API references stay separate from the main dashboard.
          </p>
        </div>
        <div class="col-lg-4">
          <div class="info-grid">
            <div class="info-tile bg-white bg-opacity-10 border-0 text-white">
              <span class="label text-white-50">Base API URL</span>
              <div class="value text-white">{{ rtrim($siteUrl, '/') }}/api</div>
            </div>
            <div class="info-tile bg-white bg-opacity-10 border-0 text-white">
              <span class="label text-white-50">Available endpoints</span>
              <div class="value text-white">{{ count($apiEndpoints) }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    @foreach ($apiEndpoints as $endpoint)
      <div class="col-xl-4 col-md-6">
        <div class="card card-outline card-warning h-100">
          <div class="card-header">
            <div class="d-flex justify-content-between align-items-center gap-3">
              <h3 class="card-title fw-semibold mb-0">{{ $endpoint['title'] }}</h3>
              <span class="badge rounded-pill text-bg-primary">{{ $endpoint['method'] }}</span>
            </div>
          </div>
          <div class="card-body d-flex flex-column">
            <p class="mb-2"><code>{{ $endpoint['path'] }}</code></p>
            <p class="text-secondary mb-3">{{ $endpoint['description'] }}</p>

            <div class="info-tile mb-3">
              <span class="label">Full URL</span>
              <div class="value">{{ rtrim($siteUrl, '/') . $endpoint['path'] }}</div>
            </div>

            @isset($endpoint['payload'])
              <div class="mt-auto">
                <div class="small text-uppercase text-secondary fw-semibold mb-2">Sample Request Body</div>
                <pre class="bg-light border rounded p-3 small mb-0"><code>{{ json_encode($endpoint['payload'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
              </div>
            @endisset

            @isset($endpoint['headers'])
              <div class="mt-auto">
                <div class="small text-uppercase text-secondary fw-semibold mb-2">Required Headers</div>
                <pre class="bg-light border rounded p-3 small mb-0"><code>{{ json_encode($endpoint['headers'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
              </div>
            @endisset
          </div>
        </div>
      </div>
    @endforeach

    <div class="col-12">
      <div class="card card-outline card-primary">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h3 class="card-title fw-semibold mb-0">Integration Notes</h3>
          <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>
            Back to Dashboard
          </a>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-4">
              <div class="info-tile h-100">
                <span class="label">Token type</span>
                <div class="value">Bearer token via Sanctum</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="info-tile h-100">
                <span class="label">Login tables</span>
                <div class="value">users and admins</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="info-tile h-100">
                <span class="label">Protected check route</span>
                <div class="value">GET /api/user</div>
              </div>
            </div>
          </div>

          <div class="alert alert-warning mt-4 mb-3">
            Send <code>email</code>, <code>password</code>, and <code>device_name</code> in the login body. Use the returned token as <code>Authorization: Bearer YOUR_TOKEN</code>.
          </div>

          <div class="alert alert-light quick-note mb-0">
            <strong>Note:</strong> API Access now has its own page at <code>/admin/api-access</code> instead of living inside the dashboard.
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection