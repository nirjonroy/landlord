@extends('layouts.admin')

@section('page_title', 'Hero Banners')
@section('page_heading', 'Homepage Hero Banners')
@section('breadcrumb_current', 'Hero Banners')

@push('styles')
  <style>
    .banner-preview {
      aspect-ratio: 16 / 8;
      background: #e2e8f0;
      border-radius: 1rem;
      overflow: hidden;
      width: 100%;
    }

    .banner-preview img {
      height: 100%;
      object-fit: cover;
      width: 100%;
    }

    .banner-card {
      border: 1px solid rgba(15, 23, 42, 0.08);
      border-radius: 1rem;
    }
  </style>
@endpush

@section('content')
  @if (session('status') === 'banner-created')
    <div class="alert alert-success">Homepage banner created successfully.</div>
  @endif

  @if (session('status') === 'banner-updated')
    <div class="alert alert-success">Homepage banner updated successfully.</div>
  @endif

  @if (session('status') === 'banner-deleted')
    <div class="alert alert-success">Homepage banner deleted successfully.</div>
  @endif

  <div class="card dashboard-hero mb-4">
    <div class="card-body p-4 p-lg-5 position-relative">
      <div class="row align-items-center g-4">
        <div class="col-lg-8">
          <span class="badge rounded-pill text-bg-light text-primary mb-3">Homepage hero control</span>
          <h1 class="h2 fw-bold mb-3">Upload slider banners with heading and sub text.</h1>
          <p class="hero-meta mb-0">
            Add multiple active banners to turn the homepage hero into a slider. The display order controls which banner appears first.
          </p>
        </div>
        <div class="col-lg-4">
          <div class="info-grid">
            <div class="info-tile bg-white bg-opacity-10 border-0 text-white">
              <span class="label text-white-50">Banner count</span>
              <div class="value text-white">{{ $banners->count() }}</div>
            </div>
            <div class="info-tile bg-white bg-opacity-10 border-0 text-white">
              <span class="label text-white-50">Active banners</span>
              <div class="value text-white">{{ $banners->where('is_active', true)->count() }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-xl-5">
      <div class="card card-outline card-primary h-100">
        <div class="card-header">
          <h3 class="card-title fw-semibold mb-0">Add New Banner</h3>
        </div>
        <div class="card-body">
          @if ($errors->any())
            <div class="alert alert-danger">Please fix the highlighted banner fields and try again.</div>
          @endif

          <form method="POST" action="{{ route('admin.homepage-banners.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
              <label for="heading" class="form-label">Heading</label>
              <input id="heading" name="heading" type="text" class="form-control @error('heading') is-invalid @enderror" value="{{ old('heading') }}" required>
              @error('heading')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="sub_text" class="form-label">Sub Text</label>
              <textarea id="sub_text" name="sub_text" rows="4" class="form-control @error('sub_text') is-invalid @enderror">{{ old('sub_text') }}</textarea>
              @error('sub_text')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="row g-3">
              <div class="col-md-6">
                <label for="display_order" class="form-label">Display Order</label>
                <input id="display_order" name="display_order" type="number" min="0" class="form-control @error('display_order') is-invalid @enderror" value="{{ old('display_order', $banners->count() + 1) }}">
                @error('display_order')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6 d-flex align-items-end">
                <div class="form-check info-tile w-100 mb-0">
                  <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active" @checked(old('is_active', true))>
                  <label class="form-check-label" for="is_active">Active on homepage</label>
                </div>
              </div>
            </div>

            <div class="mt-3">
              <label for="image" class="form-label">Banner Image</label>
              <input id="image" name="image" type="file" class="form-control @error('image') is-invalid @enderror" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" required>
              @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <div class="form-text">Accepted: JPG, PNG, or WebP. Recommended wide image ratio.</div>
            </div>

            <div class="d-flex justify-content-end mt-4">
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-upload me-1"></i>
                Save Banner
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-xl-7">
      <div class="card card-outline card-secondary h-100">
        <div class="card-header">
          <h3 class="card-title fw-semibold mb-0">Existing Banners</h3>
        </div>
        <div class="card-body">
          @forelse ($banners as $banner)
            <div class="card banner-card mb-4">
              <div class="card-body p-4">
                <div class="row g-4">
                  <div class="col-lg-5">
                    <div class="banner-preview">
                      @if ($banner->image_url)
                        <img src="{{ $banner->image_url }}" alt="{{ $banner->heading }}">
                      @endif
                    </div>
                    <div class="mt-3 small text-secondary">
                      Source: {{ $banner->image_source === 'asset' ? 'Seeded asset' : 'Uploaded image' }}
                    </div>
                  </div>

                  <div class="col-lg-7">
                    <form method="POST" action="{{ route('admin.homepage-banners.update', $banner) }}" enctype="multipart/form-data">
                      @csrf
                      @method('PUT')

                      <div class="mb-3">
                        <label for="heading_{{ $banner->id }}" class="form-label">Heading</label>
                        <input id="heading_{{ $banner->id }}" name="heading" type="text" class="form-control" value="{{ old('heading', $banner->heading) }}" required>
                      </div>

                      <div class="mb-3">
                        <label for="sub_text_{{ $banner->id }}" class="form-label">Sub Text</label>
                        <textarea id="sub_text_{{ $banner->id }}" name="sub_text" rows="3" class="form-control">{{ old('sub_text', $banner->sub_text) }}</textarea>
                      </div>

                      <div class="row g-3">
                        <div class="col-md-6">
                          <label for="display_order_{{ $banner->id }}" class="form-label">Display Order</label>
                          <input id="display_order_{{ $banner->id }}" name="display_order" type="number" min="0" class="form-control" value="{{ old('display_order', $banner->display_order) }}">
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                          <div class="form-check info-tile w-100 mb-0">
                            <input class="form-check-input" type="checkbox" value="1" id="is_active_{{ $banner->id }}" name="is_active" @checked(old('is_active', $banner->is_active))>
                            <label class="form-check-label" for="is_active_{{ $banner->id }}">Active on homepage</label>
                          </div>
                        </div>
                      </div>

                      <div class="mt-3">
                        <label for="image_{{ $banner->id }}" class="form-label">Replace Image</label>
                        <input id="image_{{ $banner->id }}" name="image" type="file" class="form-control" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                      </div>

                      <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mt-4">
                        <div class="small text-secondary">
                          Updated {{ optional($banner->updated_at)->format('d M Y, h:i A') ?: 'N/A' }}
                        </div>
                        <div class="d-flex gap-2">
                          <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-save me-1"></i>
                            Update
                          </button>
                        </div>
                      </div>
                    </form>
                    <div class="d-flex justify-content-end mt-2">
                          <form method="POST" action="{{ route('admin.homepage-banners.destroy', $banner) }}" onsubmit="return confirm('Delete this banner?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                              <i class="bi bi-trash me-1"></i>
                              Delete
                            </button>
                          </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @empty
            <div class="alert alert-light mb-0">No homepage banners have been added yet.</div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
@endsection
