@extends('layouts.admin')

@section('page_title', 'Coverage Cities')
@section('page_heading', 'Coverage Cities')
@section('breadcrumb_current', 'Coverage Cities')

@section('content')
  @if (session('status') === 'homepage-city-section-updated')
    <div class="alert alert-success">Coverage section updated successfully.</div>
  @elseif (session('status') === 'homepage-city-created')
    <div class="alert alert-success">Coverage city created successfully.</div>
  @elseif (session('status') === 'homepage-city-updated')
    <div class="alert alert-success">Coverage city updated successfully.</div>
  @elseif (session('status') === 'homepage-city-deleted')
    <div class="alert alert-success">Coverage city deleted successfully.</div>
  @endif

  @if ($errors->any())
    <div class="alert alert-danger">Please review the highlighted coverage-city fields and try again.</div>
  @endif

  <div class="card dashboard-hero mb-4">
    <div class="card-body p-4 p-lg-5 position-relative">
      <div class="row align-items-center g-4">
        <div class="col-lg-8">
          <span class="badge rounded-pill text-bg-light text-primary mb-3">Homepage coverage slider</span>
          <h1 class="h2 fw-bold mb-3">Manage the city cards and heading shown in the “available across Bangladesh” section on the homepage.</h1>
          <p class="hero-meta mb-0">You can edit the section heading, change the property counts, reorder cities, and upload custom city images from here.</p>
        </div>
        <div class="col-lg-4">
          <div class="info-tile bg-white bg-opacity-10 border-0 text-white">
            <span class="label text-white-50">Total cities</span>
            <div class="value text-white">{{ $cities->count() }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-lg-4">
      <div class="card mb-4">
        <div class="card-header">
          <h3 class="card-title fw-semibold mb-0">Section Content</h3>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('admin.homepage-cities.section.update') }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
              <label for="title" class="form-label">Heading</label>
              <input id="title" name="title" type="text" class="form-control" value="{{ old('title', $citySection->title) }}" required>
            </div>

            <div class="mb-4">
              <label for="subtitle" class="form-label">Subtitle</label>
              <textarea id="subtitle" name="subtitle" rows="4" class="form-control">{{ old('subtitle', $citySection->subtitle) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100">
              <i class="bi bi-save me-1"></i>
              Save Section
            </button>
          </form>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <h3 class="card-title fw-semibold mb-0">Add Coverage City</h3>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('admin.homepage-cities.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
              <label for="name" class="form-label">City Name</label>
              <input id="name" name="name" type="text" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="mb-3">
              <label for="property_count" class="form-label">Property Count</label>
              <input id="property_count" name="property_count" type="number" min="0" class="form-control" value="{{ old('property_count', 0) }}">
            </div>

            <div class="mb-3">
              <label for="display_order" class="form-label">Display Order</label>
              <input id="display_order" name="display_order" type="number" min="0" class="form-control" value="{{ old('display_order', 0) }}">
            </div>

            <div class="mb-4">
              <label for="image" class="form-label">City Image</label>
              <input id="image" name="image" type="file" class="form-control" accept=".jpg,.jpeg,.png,.webp">
              <div class="form-text">Optional. If you skip this, the section uses a seeded fallback image.</div>
            </div>

            <button type="submit" class="btn btn-primary w-100">
              <i class="bi bi-plus-circle me-1"></i>
              Add City
            </button>
          </form>
        </div>
      </div>
    </div>

    <div class="col-lg-8">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title fw-semibold mb-0">Coverage City Cards</h3>
        </div>
        <div class="card-body">
          @forelse ($cities as $city)
            <div class="border rounded-4 p-3 mb-3">
              <div class="row g-3 align-items-start">
                <div class="col-md-3">
                  <div class="border rounded-4 bg-light d-flex align-items-center justify-content-center p-2 h-100">
                    <img src="{{ $city->imageUrl() }}" alt="{{ $city->name }}" style="width: 100%; max-height: 120px; object-fit: cover; border-radius: 0.8rem;">
                  </div>
                </div>
                <div class="col-md-9">
                  <form method="POST" action="{{ route('admin.homepage-cities.update', $city) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                      <div class="col-md-5">
                        <label class="form-label">City Name</label>
                        <input name="name" type="text" class="form-control" value="{{ old('name', $city->name) }}" required>
                      </div>
                      <div class="col-md-4">
                        <label class="form-label">Property Count</label>
                        <input name="property_count" type="number" min="0" class="form-control" value="{{ old('property_count', $city->property_count) }}">
                      </div>
                      <div class="col-md-3">
                        <label class="form-label">Order</label>
                        <input name="display_order" type="number" min="0" class="form-control" value="{{ old('display_order', $city->display_order) }}">
                      </div>
                      <div class="col-md-8">
                        <label class="form-label">Replace Image</label>
                        <input name="image" type="file" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                      </div>
                      <div class="col-md-4 d-flex align-items-end">
                        <div class="form-check mb-2">
                          <input id="remove_image_{{ $city->id }}" name="remove_image" type="checkbox" class="form-check-input" value="1">
                          <label for="remove_image_{{ $city->id }}" class="form-check-label">Remove uploaded image</label>
                        </div>
                      </div>
                      <div class="col-12 d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Current source: {{ $city->image_source === 'upload' ? 'Uploaded image' : 'Seeded asset image' }}</span>
                        <button type="submit" class="btn btn-primary btn-sm">
                          <i class="bi bi-save me-1"></i>
                          Save
                        </button>
                      </div>
                    </div>
                  </form>

                  <form method="POST" action="{{ route('admin.homepage-cities.destroy', $city) }}" class="mt-2" onsubmit="return confirm('Delete this coverage city card?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                      <i class="bi bi-trash me-1"></i>
                      Delete City
                    </button>
                  </form>
                </div>
              </div>
            </div>
          @empty
            <div class="alert alert-light border mb-0">No coverage cities exist yet.</div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
@endsection
