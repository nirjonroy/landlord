@extends('layouts.admin')

@section('page_title', 'Property Details')
@section('page_heading', 'Property Details')
@section('breadcrumb_current', 'Property Details')

@push('styles')
  <style>
    .property-detail-grid {
      display: grid;
      gap: 1rem;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    }

    .property-detail-tile {
      padding: 1rem;
      border-radius: 1rem;
      background: #f8fafc;
      border: 1px solid rgba(15, 23, 42, 0.08);
    }

    .property-detail-tile .label {
      display: block;
      margin-bottom: 0.35rem;
      color: #64748b;
      font-size: 0.78rem;
      font-weight: 700;
      letter-spacing: 0.04em;
      text-transform: uppercase;
    }

    .property-detail-gallery {
      display: grid;
      gap: 1rem;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    }

    .property-detail-gallery img {
      width: 100%;
      height: 180px;
      object-fit: cover;
      border-radius: 1rem;
    }

    .property-status-chip {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 0.45rem 0.9rem;
      border-radius: 999px;
      font-size: 0.78rem;
      font-weight: 700;
      text-transform: uppercase;
    }

    .property-status-chip-success {
      background: #dcfce7;
      color: #166534;
    }

    .property-status-chip-warning {
      background: #fef3c7;
      color: #92400e;
    }

    .property-status-chip-danger {
      background: #fee2e2;
      color: #991b1b;
    }

    .property-status-chip-neutral {
      background: #e2e8f0;
      color: #0f172a;
    }
  </style>
@endpush

@section('content')
  @if (session('status') === 'property-reviewed')
    <div class="alert alert-success">The property review decision was saved successfully.</div>
  @endif

  <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
      <h1 class="h3 fw-bold mb-1">{{ $property->title }}</h1>
      <p class="text-secondary mb-0">{{ $property->location ?: 'Location not set' }}</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
      <span class="property-status-chip property-status-chip-{{ $reviewTone }}">Review: {{ $reviewLabel }}</span>
      <span class="property-status-chip property-status-chip-{{ $availabilityTone }}">Market: {{ $availabilityLabel }}</span>
      <a href="{{ route('admin.properties.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>
        Back to Properties
      </a>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-xl-8">
      <div class="card card-outline card-primary">
        <div class="card-header">
          <h3 class="card-title fw-semibold mb-0">Property Overview</h3>
        </div>
        <div class="card-body">
          <div class="property-detail-grid mb-4">
            <div class="property-detail-tile">
              <span class="label">Owner</span>
              <div class="fw-semibold">{{ $property->user?->name ?: 'Unknown owner' }}</div>
              <div class="text-secondary small">{{ $property->user?->email ?: 'No email' }}</div>
            </div>
            <div class="property-detail-tile">
              <span class="label">Purpose</span>
              <div class="fw-semibold">{{ ucfirst($property->purpose) }}</div>
            </div>
            <div class="property-detail-tile">
              <span class="label">Property Type</span>
              <div class="fw-semibold">{{ $property->property_type }}</div>
            </div>
            <div class="property-detail-tile">
              <span class="label">Price</span>
              <div class="fw-semibold">BDT {{ number_format((int) $property->price) }}</div>
            </div>
            <div class="property-detail-tile">
              <span class="label">ZIP Code</span>
              <div class="fw-semibold">{{ $property->postal_code ?: 'Not set' }}</div>
            </div>
            <div class="property-detail-tile">
              <span class="label">Contact Phone</span>
              <div class="fw-semibold">{{ $property->contact_phone ?: 'Not set' }}</div>
            </div>
            <div class="property-detail-tile">
              <span class="label">Bedrooms</span>
              <div class="fw-semibold">{{ $property->bedrooms ?? 0 }}</div>
            </div>
            <div class="property-detail-tile">
              <span class="label">Bathrooms</span>
              <div class="fw-semibold">{{ $property->bathrooms ?? 0 }}</div>
            </div>
            <div class="property-detail-tile">
              <span class="label">Parking</span>
              <div class="fw-semibold">{{ $property->garages ?? 0 }}</div>
            </div>
            <div class="property-detail-tile">
              <span class="label">Area</span>
              <div class="fw-semibold">{{ $property->area_size ? number_format((float) $property->area_size, 2).' sqft' : 'Not set' }}</div>
            </div>
            <div class="property-detail-tile">
              <span class="label">District</span>
              <div class="fw-semibold">{{ $property->district ?: 'Not set' }}</div>
            </div>
            <div class="property-detail-tile">
              <span class="label">Division</span>
              <div class="fw-semibold">{{ $property->division ?: 'Not set' }}</div>
            </div>
          </div>

          <div class="mb-4">
            <h4 class="h5 fw-semibold mb-2">Full Address</h4>
            <p class="mb-0 text-secondary">{{ $property->address ?: 'No address provided yet.' }}</p>
          </div>

          <div>
            <h4 class="h5 fw-semibold mb-2">Description</h4>
            <p class="mb-0 text-secondary">{{ $property->description ?: 'No description provided yet.' }}</p>
          </div>
        </div>
      </div>

      <div class="card card-outline card-secondary mt-4">
        <div class="card-header">
          <h3 class="card-title fw-semibold mb-0">Property Images</h3>
        </div>
        <div class="card-body">
          @if ($thumbnailUrl || $galleryUrls !== [])
            <div class="property-detail-gallery">
              @if ($thumbnailUrl)
                <a href="{{ $thumbnailUrl }}" target="_blank" rel="noopener">
                  <img src="{{ $thumbnailUrl }}" alt="Property cover image">
                </a>
              @endif
              @foreach ($galleryUrls as $galleryUrl)
                <a href="{{ $galleryUrl }}" target="_blank" rel="noopener">
                  <img src="{{ $galleryUrl }}" alt="Property gallery image">
                </a>
              @endforeach
            </div>
          @else
            <div class="alert alert-light mb-0">The user has not uploaded any property images yet.</div>
          @endif
        </div>
      </div>
    </div>

    <div class="col-xl-4">
      <div class="card card-outline card-warning">
        <div class="card-header">
          <h3 class="card-title fw-semibold mb-0">Review Actions</h3>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <div class="small text-uppercase text-secondary fw-semibold mb-1">Submitted</div>
            <div>{{ optional($property->created_at)->format('d M Y, h:i A') ?: 'N/A' }}</div>
          </div>
          <div class="mb-3">
            <div class="small text-uppercase text-secondary fw-semibold mb-1">Reviewed By</div>
            <div>{{ $property->reviewedBy?->name ?: 'Not reviewed yet' }}</div>
          </div>
          <div class="mb-4">
            <div class="small text-uppercase text-secondary fw-semibold mb-1">Reviewed At</div>
            <div>{{ optional($property->reviewed_at)->format('d M Y, h:i A') ?: 'Not reviewed yet' }}</div>
          </div>

          <form method="POST" action="{{ route('admin.properties.review.update', $property) }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="return_to" value="show">

            <div class="mb-3">
              <label class="form-label fw-semibold" for="review_note">Review Note</label>
              <textarea id="review_note" name="review_note" class="form-control @error('review_note') is-invalid @enderror" rows="6" placeholder="Add a note for approval or rejection.">{{ old('review_note', $property->review_note) }}</textarea>
              @error('review_note')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <div class="d-flex flex-wrap gap-2">
              <button type="submit" name="status" value="approved" class="btn btn-success">
                <i class="bi bi-check-circle me-1"></i>
                Approve
              </button>
              <button type="submit" name="status" value="rejected" class="btn btn-danger">
                <i class="bi bi-x-circle me-1"></i>
                Reject
              </button>
            </div>
          </form>
        </div>
      </div>

      <div class="card card-outline card-info mt-4">
        <div class="card-header">
          <h3 class="card-title fw-semibold mb-0">Public View</h3>
        </div>
        <div class="card-body">
          <p class="text-secondary">Open the landlord-facing detail page to verify what the owner and public users can see.</p>
          <a href="{{ route('properties.show', $property) }}" class="btn btn-outline-primary" target="_blank" rel="noopener">
            <i class="bi bi-box-arrow-up-right me-1"></i>
            Open Property Page
          </a>
        </div>
      </div>
    </div>
  </div>
@endsection
