@extends('layouts.admin')

@section('page_title', 'Subscription Packages')
@section('page_heading', 'Subscription Packages')
@section('breadcrumb_current', 'Subscription Packages')

@section('content')
  @if (session('status') === 'subscription-package-created')
    <div class="alert alert-success">Subscription package created successfully.</div>
  @elseif (session('status') === 'subscription-package-updated')
    <div class="alert alert-success">Subscription package updated successfully.</div>
  @elseif (session('status') === 'subscription-package-deleted')
    <div class="alert alert-success">Subscription package deleted successfully.</div>
  @endif

  @if ($errors->any())
    <div class="alert alert-danger">Please review the highlighted subscription package fields and try again.</div>
  @endif

  <div class="card dashboard-hero mb-4">
    <div class="card-body p-4 p-lg-5 position-relative">
      <div class="row align-items-center g-4">
        <div class="col-lg-8">
          <span class="badge rounded-pill text-bg-light text-primary mb-3">Listing access packages</span>
          <h1 class="h2 fw-bold mb-3">Create the subscription packages users must buy before posting properties.</h1>
          <p class="hero-meta mb-0">Each package controls the price, active duration, and how many active listings a user can keep live at one time.</p>
        </div>
        <div class="col-lg-4">
          <div class="info-grid">
            <div class="info-tile bg-white bg-opacity-10 border-0 text-white">
              <span class="label text-white-50">Total packages</span>
              <div class="value text-white">{{ $packages->count() }}</div>
            </div>
            <div class="info-tile bg-white bg-opacity-10 border-0 text-white">
              <span class="label text-white-50">Active packages</span>
              <div class="value text-white">{{ $packages->where('is_active', true)->count() }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-lg-4">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title fw-semibold mb-0">Add Subscription Package</h3>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('admin.subscription-packages.store') }}">
            @csrf

            <div class="mb-3">
              <label for="name" class="form-label">Package Name</label>
              <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="price" class="form-label">Price (BDT)</label>
              <input id="price" name="price" type="number" min="0" step="0.01" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" required>
              @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="row g-3">
              <div class="col-md-6">
                <label for="duration_days" class="form-label">Duration (Days)</label>
                <input id="duration_days" name="duration_days" type="number" min="1" class="form-control @error('duration_days') is-invalid @enderror" value="{{ old('duration_days', 30) }}" required>
                @error('duration_days')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6">
                <label for="property_limit" class="form-label">Active Listing Limit</label>
                <input id="property_limit" name="property_limit" type="number" min="1" class="form-control @error('property_limit') is-invalid @enderror" value="{{ old('property_limit') }}" placeholder="Leave blank for unlimited">
                @error('property_limit')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="row g-3 mt-1">
              <div class="col-md-6">
                <label for="display_order" class="form-label">Display Order</label>
                <input id="display_order" name="display_order" type="number" min="0" class="form-control @error('display_order') is-invalid @enderror" value="{{ old('display_order', 0) }}">
                @error('display_order')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="mb-3 mt-3">
              <label for="description" class="form-label">Description</label>
              <textarea id="description" name="description" rows="4" class="form-control @error('description') is-invalid @enderror" placeholder="Explain what this package unlocks.">{{ old('description') }}</textarea>
              @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="form-check mb-2">
              <input id="is_active" name="is_active" type="checkbox" class="form-check-input" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
              <label for="is_active" class="form-check-label">Package is active and buyable</label>
            </div>

            <div class="form-check mb-4">
              <input id="is_featured" name="is_featured" type="checkbox" class="form-check-input" value="1" {{ old('is_featured') ? 'checked' : '' }}>
              <label for="is_featured" class="form-check-label">Mark as featured package</label>
            </div>

            <button type="submit" class="btn btn-primary w-100">
              <i class="bi bi-plus-circle me-1"></i>
              Add Package
            </button>
          </form>
        </div>
      </div>
    </div>

    <div class="col-lg-8">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title fw-semibold mb-0">Existing Packages</h3>
        </div>
        <div class="card-body">
          @forelse ($packages as $package)
            <div class="border rounded-4 p-3 mb-3">
              <form method="POST" action="{{ route('admin.subscription-packages.update', $package) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                  <div class="col-md-5">
                    <label class="form-label">Package Name</label>
                    <input name="name" type="text" class="form-control" value="{{ old('name', $package->name) }}" required>
                  </div>
                  <div class="col-md-3">
                    <label class="form-label">Price (BDT)</label>
                    <input name="price" type="number" min="0" step="0.01" class="form-control" value="{{ old('price', $package->price) }}" required>
                  </div>
                  <div class="col-md-2">
                    <label class="form-label">Days</label>
                    <input name="duration_days" type="number" min="1" class="form-control" value="{{ old('duration_days', $package->duration_days) }}" required>
                  </div>
                  <div class="col-md-2">
                    <label class="form-label">Order</label>
                    <input name="display_order" type="number" min="0" class="form-control" value="{{ old('display_order', $package->display_order) }}">
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">Active Listing Limit</label>
                    <input name="property_limit" type="number" min="1" class="form-control" value="{{ old('property_limit', $package->property_limit) }}" placeholder="Unlimited">
                  </div>
                  <div class="col-md-8">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="2" class="form-control" placeholder="Package notes">{{ old('description', $package->description) }}</textarea>
                  </div>
                  <div class="col-md-6">
                    <div class="form-check">
                      <input id="active_{{ $package->id }}" name="is_active" type="checkbox" class="form-check-input" value="1" {{ old('is_active', $package->is_active) ? 'checked' : '' }}>
                      <label for="active_{{ $package->id }}" class="form-check-label">Active</label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-check">
                      <input id="featured_{{ $package->id }}" name="is_featured" type="checkbox" class="form-check-input" value="1" {{ old('is_featured', $package->is_featured) ? 'checked' : '' }}>
                      <label for="featured_{{ $package->id }}" class="form-check-label">Featured</label>
                    </div>
                  </div>
                  <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <span class="text-muted small">Slug: {{ $package->slug }} • {{ $package->property_limit === null ? 'Unlimited active listings' : $package->property_limit.' active listing'.($package->property_limit === 1 ? '' : 's') }}</span>
                    <button type="submit" class="btn btn-primary btn-sm">
                      <i class="bi bi-save me-1"></i>
                      Save
                    </button>
                  </div>
                </div>
              </form>

              <form method="POST" action="{{ route('admin.subscription-packages.destroy', $package) }}" class="mt-2" onsubmit="return confirm('Delete this subscription package? Existing payment history will stay, but users will no longer be able to buy this package.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm">
                  <i class="bi bi-trash me-1"></i>
                  Delete Package
                </button>
              </form>
            </div>
          @empty
            <div class="alert alert-light border mb-0">No subscription packages exist yet.</div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
@endsection
