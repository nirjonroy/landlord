@extends('layouts.admin')

@section('page_title', 'Property Types')
@section('page_heading', 'Property Types')
@section('breadcrumb_current', 'Property Types')

@section('content')
  @if (session('status') === 'property-type-created')
    <div class="alert alert-success">Property type created successfully.</div>
  @elseif (session('status') === 'property-type-updated')
    <div class="alert alert-success">Property type updated successfully.</div>
  @elseif (session('status') === 'property-type-deleted')
    <div class="alert alert-success">Property type deleted successfully.</div>
  @endif

  @if ($errors->any())
    <div class="alert alert-danger">Please review the highlighted property type fields and try again.</div>
  @endif

  <div class="card dashboard-hero mb-4">
    <div class="card-body p-4 p-lg-5 position-relative">
      <div class="row align-items-center g-4">
        <div class="col-lg-8">
          <span class="badge rounded-pill text-bg-light text-primary mb-3">Homepage categories</span>
          <h1 class="h2 fw-bold mb-3">Manage the property-type cards used on the homepage, listing page filter, and user add-property form.</h1>
          <p class="hero-meta mb-0">Use one shared list so the frontend cards, filters, and user submission dropdown stay in sync from the admin panel.</p>
        </div>
        <div class="col-lg-4">
          <div class="info-tile bg-white bg-opacity-10 border-0 text-white">
            <span class="label text-white-50">Total types</span>
            <div class="value text-white">{{ $propertyTypes->count() }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-lg-4">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title fw-semibold mb-0">Add Property Type</h3>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('admin.property-types.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
              <label for="name" class="form-label">Label</label>
              <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="filter_value" class="form-label">Property Value</label>
              <input id="filter_value" name="filter_value" type="text" class="form-control @error('filter_value') is-invalid @enderror" value="{{ old('filter_value') }}" required>
              <div class="form-text">This value is saved on user properties and used by the listing filter. Example: `Apartment` or `Land`.</div>
              @error('filter_value')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="display_order" class="form-label">Display Order</label>
              <input id="display_order" name="display_order" type="number" min="0" class="form-control @error('display_order') is-invalid @enderror" value="{{ old('display_order', 0) }}">
              @error('display_order')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="icon_image" class="form-label">Icon</label>
              <input id="icon_image" name="icon_image" type="file" class="form-control @error('icon_image') is-invalid @enderror" accept=".svg,.png,.jpg,.jpeg,.webp">
              <div class="form-text">Optional. Upload an SVG or image icon for the homepage card.</div>
              @error('icon_image')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="form-check mb-2">
              <input id="is_active" name="is_active" type="checkbox" class="form-check-input" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
              <label for="is_active" class="form-check-label">Active in filters and user form</label>
            </div>

            <div class="form-check mb-4">
              <input id="show_on_home" name="show_on_home" type="checkbox" class="form-check-input" value="1" {{ old('show_on_home', true) ? 'checked' : '' }}>
              <label for="show_on_home" class="form-check-label">Show on homepage cards</label>
            </div>

            <button type="submit" class="btn btn-primary w-100">
              <i class="bi bi-plus-circle me-1"></i>
              Add Property Type
            </button>
          </form>
        </div>
      </div>
    </div>

    <div class="col-lg-8">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title fw-semibold mb-0">Existing Property Types</h3>
        </div>
        <div class="card-body">
          @forelse ($propertyTypes as $propertyType)
            <div class="border rounded-4 p-3 mb-3">
              <div class="row g-3 align-items-start">
                <div class="col-md-2">
                  <div class="border rounded-4 bg-light d-flex align-items-center justify-content-center p-3 h-100">
                    <img src="{{ $propertyType->iconUrl() }}" alt="{{ $propertyType->name }}" style="max-width: 70px; max-height: 70px; object-fit: contain;">
                  </div>
                </div>
                <div class="col-md-10">
                  <form method="POST" action="{{ route('admin.property-types.update', $propertyType) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                      <div class="col-md-5">
                        <label class="form-label">Label</label>
                        <input name="name" type="text" class="form-control" value="{{ old('name', $propertyType->name) }}" required>
                      </div>
                      <div class="col-md-4">
                        <label class="form-label">Property Value</label>
                        <input name="filter_value" type="text" class="form-control" value="{{ old('filter_value', $propertyType->filter_value) }}" required>
                      </div>
                      <div class="col-md-3">
                        <label class="form-label">Order</label>
                        <input name="display_order" type="number" min="0" class="form-control" value="{{ old('display_order', $propertyType->display_order) }}">
                      </div>
                      <div class="col-md-8">
                        <label class="form-label">Replace Icon</label>
                        <input name="icon_image" type="file" class="form-control" accept=".svg,.png,.jpg,.jpeg,.webp">
                      </div>
                      <div class="col-md-4 d-flex align-items-end">
                        <div class="form-check mb-2">
                          <input id="remove_icon_{{ $propertyType->id }}" name="remove_icon" type="checkbox" class="form-check-input" value="1">
                          <label for="remove_icon_{{ $propertyType->id }}" class="form-check-label">Remove current icon</label>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-check">
                          <input id="active_{{ $propertyType->id }}" name="is_active" type="checkbox" class="form-check-input" value="1" {{ old('is_active', $propertyType->is_active) ? 'checked' : '' }}>
                          <label for="active_{{ $propertyType->id }}" class="form-check-label">Active</label>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-check">
                          <input id="show_on_home_{{ $propertyType->id }}" name="show_on_home" type="checkbox" class="form-check-input" value="1" {{ old('show_on_home', $propertyType->show_on_home) ? 'checked' : '' }}>
                          <label for="show_on_home_{{ $propertyType->id }}" class="form-check-label">Show on homepage</label>
                        </div>
                      </div>
                      <div class="col-12 d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Current source: {{ $propertyType->icon_source === 'upload' ? 'Uploaded icon' : 'Seeded asset icon' }}</span>
                        <button type="submit" class="btn btn-primary btn-sm">
                          <i class="bi bi-save me-1"></i>
                          Save
                        </button>
                      </div>
                    </div>
                  </form>

                  <form method="POST" action="{{ route('admin.property-types.destroy', $propertyType) }}" class="mt-2" onsubmit="return confirm('Delete this property type? Existing properties will keep their saved text value, but new submissions will no longer offer it.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                      <i class="bi bi-trash me-1"></i>
                      Delete Property Type
                    </button>
                  </form>
                </div>
              </div>
            </div>
          @empty
            <div class="alert alert-light border mb-0">No property types exist yet.</div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
@endsection
