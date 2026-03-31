@extends('layouts.admin')

@section('page_title', 'Site Info')
@section('page_heading', 'Site Information')
@section('breadcrumb_current', 'Site Info')

@section('content')
  <div class="card dashboard-hero mb-4">
    <div class="card-body p-4 p-lg-5 position-relative">
      <div class="row align-items-center g-4">
        <div class="col-lg-8">
          <span class="badge rounded-pill text-bg-light text-primary mb-3">Dedicated update page</span>
          <h1 class="h2 fw-bold mb-3">Update the public details for {{ $siteName }}.</h1>
          <p class="hero-meta mb-0">
            Change your site name, URLs, contact details, social links, and short description here. These values stay in the database for later use across the website and app.
          </p>
        </div>
        <div class="col-lg-4">
          <div class="info-grid">
            <div class="info-tile bg-white bg-opacity-10 border-0 text-white">
              <span class="label text-white-50">Current admin</span>
              <div class="value text-white">{{ $admin->name }}</div>
            </div>
            <div class="info-tile bg-white bg-opacity-10 border-0 text-white">
              <span class="label text-white-50">Saved site</span>
              <div class="value text-white">{{ $siteInfo->site_name }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-xl-8">
      <div class="card card-outline card-primary h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h3 class="card-title fw-semibold mb-0">Edit Site Information</h3>
          <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>
            Back to Dashboard
          </a>
        </div>
        <div class="card-body">
          @if (session('status') === 'site-info-updated')
            <div class="alert alert-success">
              Site information updated successfully.
            </div>
          @endif

          @if ($errors->any())
            <div class="alert alert-danger">
              Please fix the highlighted fields and try again.
            </div>
          @endif

          <form method="POST" action="{{ route('admin.site-info.update') }}">
            @csrf
            @method('PUT')

            <div class="row g-3">
              <div class="col-md-6">
                <label for="site_name" class="form-label">Site Name</label>
                <input id="site_name" name="site_name" type="text" class="form-control @error('site_name') is-invalid @enderror" value="{{ old('site_name', $siteInfo->site_name) }}" required />
                @error('site_name')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6">
                <label for="site_url" class="form-label">Site URL</label>
                <input id="site_url" name="site_url" type="url" class="form-control @error('site_url') is-invalid @enderror" value="{{ old('site_url', $siteInfo->site_url) }}" placeholder="https://example.com" />
                @error('site_url')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6">
                <label for="contact_email" class="form-label">Contact Email</label>
                <input id="contact_email" name="contact_email" type="email" class="form-control @error('contact_email') is-invalid @enderror" value="{{ old('contact_email', $siteInfo->contact_email) }}" placeholder="hello@example.com" />
                @error('contact_email')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6">
                <label for="contact_phone" class="form-label">Contact Phone</label>
                <input id="contact_phone" name="contact_phone" type="text" class="form-control @error('contact_phone') is-invalid @enderror" value="{{ old('contact_phone', $siteInfo->contact_phone) }}" placeholder="+8801XXXXXXXXX" />
                @error('contact_phone')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-12">
                <label for="address" class="form-label">Address</label>
                <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror" rows="2" placeholder="Office or company address">{{ old('address', $siteInfo->address) }}</textarea>
                @error('address')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-12">
                <label for="short_description" class="form-label">Short Description</label>
                <textarea id="short_description" name="short_description" class="form-control @error('short_description') is-invalid @enderror" rows="4" placeholder="Short description for the site or app">{{ old('short_description', $siteInfo->short_description) }}</textarea>
                @error('short_description')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-4">
                <label for="facebook_url" class="form-label">Facebook URL</label>
                <input id="facebook_url" name="facebook_url" type="url" class="form-control @error('facebook_url') is-invalid @enderror" value="{{ old('facebook_url', $siteInfo->facebook_url) }}" placeholder="https://facebook.com/..." />
                @error('facebook_url')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-4">
                <label for="instagram_url" class="form-label">Instagram URL</label>
                <input id="instagram_url" name="instagram_url" type="url" class="form-control @error('instagram_url') is-invalid @enderror" value="{{ old('instagram_url', $siteInfo->instagram_url) }}" placeholder="https://instagram.com/..." />
                @error('instagram_url')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-4">
                <label for="youtube_url" class="form-label">YouTube URL</label>
                <input id="youtube_url" name="youtube_url" type="url" class="form-control @error('youtube_url') is-invalid @enderror" value="{{ old('youtube_url', $siteInfo->youtube_url) }}" placeholder="https://youtube.com/..." />
                @error('youtube_url')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-4">
              <p class="text-secondary mb-0">This page updates the single `site_infos` record used by the admin panel.</p>
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i>
                Save Site Info
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-xl-4">
      <div class="card card-outline card-secondary mb-4">
        <div class="card-header">
          <h3 class="card-title fw-semibold">Current Public Snapshot</h3>
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
          </div>
        </div>
      </div>

      <div class="card card-outline card-success mb-4">
        <div class="card-header">
          <h3 class="card-title fw-semibold">Quick Links</h3>
        </div>
        <div class="card-body">
          <p class="mb-3"><strong>Admin login:</strong><br />{{ url('/admin/login') }}</p>
          <p class="mb-3"><strong>User login:</strong><br />{{ url('/login') }}</p>
          <p class="mb-3"><strong>Facebook:</strong><br />{{ $siteInfo->facebook_url ?: 'Not set yet' }}</p>
          <p class="mb-3"><strong>Instagram:</strong><br />{{ $siteInfo->instagram_url ?: 'Not set yet' }}</p>
          <p class="mb-0"><strong>YouTube:</strong><br />{{ $siteInfo->youtube_url ?: 'Not set yet' }}</p>
        </div>
      </div>

      <div class="alert alert-light quick-note mb-0">
        <strong>Note:</strong> The edit form is now on its own page at <code>/admin/site-info</code> instead of inside the dashboard.
      </div>
    </div>
  </div>
@endsection