@extends('layouts.admin')

@section('page_title', 'Properties')
@section('page_heading', 'Property Reviews')
@section('breadcrumb_current', 'Properties')

@push('styles')
  <style>
    .property-meta-grid {
      display: grid;
      gap: 1rem;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    }

    .property-review-card {
      border: 1px solid rgba(15, 23, 42, 0.08);
      border-radius: 1rem;
      height: 100%;
    }

    .property-review-card .meta-label {
      color: #64748b;
      display: block;
      font-size: 0.78rem;
      font-weight: 600;
      letter-spacing: 0.04em;
      margin-bottom: 0.3rem;
      text-transform: uppercase;
    }

    .property-review-card .meta-value {
      color: #0f172a;
      font-weight: 600;
    }

    .review-status-badge {
      border-radius: 999px;
      display: inline-flex;
      font-size: 0.75rem;
      font-weight: 700;
      letter-spacing: 0.03em;
      padding: 0.35rem 0.8rem;
      text-transform: uppercase;
    }

    .review-status-pending {
      background: #fef3c7;
      color: #92400e;
    }

    .review-status-approved {
      background: #dcfce7;
      color: #166534;
    }

    .review-status-rejected {
      background: #fee2e2;
      color: #991b1b;
    }

    .review-note-box {
      background: #f8fafc;
      border: 1px solid rgba(15, 23, 42, 0.08);
      border-radius: 0.9rem;
      padding: 0.9rem 1rem;
    }

    .review-form textarea {
      min-height: 110px;
      resize: vertical;
    }
  </style>
@endpush

@section('content')
  @if (session('status') === 'property-reviewed')
    <div class="alert alert-success">The property review decision was saved successfully.</div>
  @endif

  <div class="card dashboard-hero mb-4">
    <div class="card-body p-4 p-lg-5 position-relative">
      <div class="row align-items-center g-4">
        <div class="col-lg-8">
          <span class="badge rounded-pill text-bg-light text-primary mb-3">Property moderation</span>
          <h1 class="h2 fw-bold mb-3">Approve or reject user property submissions from one page.</h1>
          <p class="hero-meta mb-0">
            New listings arrive as pending. Review the property information below, approve the valid ones, and reject the incomplete or incorrect ones with a note.
          </p>
        </div>
        <div class="col-lg-4">
          <div class="info-grid">
            <div class="info-tile bg-white bg-opacity-10 border-0 text-white">
              <span class="label text-white-50">Review page</span>
              <div class="value text-white">/admin/properties</div>
            </div>
            <div class="info-tile bg-white bg-opacity-10 border-0 text-white">
              <span class="label text-white-50">Pending now</span>
              <div class="value text-white">{{ $pendingCount }}</div>
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
          <h3>{{ $propertyCount }}</h3>
          <p>Total Properties</p>
        </div>
        <i class="small-box-icon bi bi-buildings-fill"></i>
      </div>
    </div>
    <div class="col-lg-3 col-6">
      <div class="small-box text-bg-warning">
        <div class="inner">
          <h3>{{ $pendingCount }}</h3>
          <p>Pending Review</p>
        </div>
        <i class="small-box-icon bi bi-hourglass-split"></i>
      </div>
    </div>
    <div class="col-lg-3 col-6">
      <div class="small-box text-bg-success">
        <div class="inner">
          <h3>{{ $approvedCount }}</h3>
          <p>Approved</p>
        </div>
        <i class="small-box-icon bi bi-patch-check-fill"></i>
      </div>
    </div>
    <div class="col-lg-3 col-6">
      <div class="small-box text-bg-danger">
        <div class="inner">
          <h3>{{ $rejectedCount }}</h3>
          <p>Rejected</p>
        </div>
        <i class="small-box-icon bi bi-x-octagon-fill"></i>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-12">
      <div class="card card-outline card-warning">
        <div class="card-header">
          <h3 class="card-title fw-semibold mb-0">Pending Review Queue</h3>
        </div>
        <div class="card-body">
          @if ($pendingProperties->isEmpty())
            <div class="alert alert-success mb-0">There are no pending property submissions right now.</div>
          @else
            <div class="row g-4">
              @foreach ($pendingProperties as $property)
                <div class="col-12">
                  <div class="card property-review-card shadow-sm">
                    <div class="card-body p-4">
                      <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
                        <div>
                          <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                            <h3 class="h4 mb-0">{{ $property->title }}</h3>
                            <span class="review-status-badge review-status-pending">Pending</span>
                          </div>
                          <p class="text-secondary mb-0">
                            Submitted by {{ $property->user?->name ?: 'Unknown user' }}
                            @if ($property->user?->email)
                              ({{ $property->user->email }})
                            @endif
                          </p>
                        </div>
                        <div class="text-secondary small">
                          Submitted {{ optional($property->created_at)->format('d M Y, h:i A') ?: 'N/A' }}
                        </div>
                      </div>

                      <div class="property-meta-grid mb-4">
                        <div>
                          <span class="meta-label">Purpose</span>
                          <div class="meta-value">{{ ucfirst($property->purpose) }}</div>
                        </div>
                        <div>
                          <span class="meta-label">Type</span>
                          <div class="meta-value">{{ $property->property_type }}</div>
                        </div>
                        <div>
                          <span class="meta-label">Price</span>
                          <div class="meta-value">৳{{ number_format($property->price) }}</div>
                        </div>
                        <div>
                          <span class="meta-label">Location</span>
                          <div class="meta-value">{{ $property->location ?: 'Not set' }}</div>
                        </div>
                        <div>
                          <span class="meta-label">District</span>
                          <div class="meta-value">{{ $property->district ?: 'Not set' }}</div>
                        </div>
                        <div>
                          <span class="meta-label">Contact Phone</span>
                          <div class="meta-value">{{ $property->contact_phone ?: 'Not set' }}</div>
                        </div>
                        <div>
                          <span class="meta-label">Bedrooms</span>
                          <div class="meta-value">{{ $property->bedrooms ?? 0 }}</div>
                        </div>
                        <div>
                          <span class="meta-label">Bathrooms</span>
                          <div class="meta-value">{{ $property->bathrooms ?? 0 }}</div>
                        </div>
                        <div>
                          <span class="meta-label">Parking</span>
                          <div class="meta-value">{{ $property->garages ?? 0 }}</div>
                        </div>
                        <div>
                          <span class="meta-label">Area</span>
                          <div class="meta-value">{{ $property->area_size ? number_format((float) $property->area_size, 2).' sqft' : 'Not set' }}</div>
                        </div>
                      </div>

                      <div class="row g-4">
                        <div class="col-lg-7">
                          <div class="review-note-box h-100">
                            <div class="meta-label">Property Address</div>
                            <div class="meta-value mb-3">{{ $property->address ?: 'Not set yet' }}</div>
                            <div class="meta-label">Description</div>
                            <div class="text-secondary">{{ $property->description ?: 'No description added by the user.' }}</div>
                          </div>
                        </div>
                        <div class="col-lg-5">
                          <form method="POST" action="{{ route('admin.properties.review.update', $property) }}" class="review-form">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                              <label class="form-label fw-semibold" for="review_note_{{ $property->id }}">Review Note</label>
                              <textarea
                                class="form-control @error('review_note') is-invalid @enderror"
                                id="review_note_{{ $property->id }}"
                                name="review_note"
                                placeholder="Add a reason when rejecting, or leave a short approval note."
                              >{{ old('review_note') }}</textarea>
                              <div class="form-text">A rejection note is required when you reject a property.</div>
                              @error('review_note')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                              @enderror
                            </div>

                            <div class="d-flex flex-wrap gap-2">
                              <button type="submit" name="status" value="approved" class="btn btn-success">
                                <i class="bi bi-check-circle me-1"></i>
                                Approve Property
                              </button>
                              <button type="submit" name="status" value="rejected" class="btn btn-danger">
                                <i class="bi bi-x-circle me-1"></i>
                                Reject Property
                              </button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          @endif
        </div>
      </div>
    </div>

    <div class="col-12">
      <div class="card card-outline card-secondary">
        <div class="card-header">
          <h3 class="card-title fw-semibold mb-0">Reviewed Properties</h3>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead>
                <tr>
                  <th class="px-3">Property</th>
                  <th>Owner</th>
                  <th>Purpose</th>
                  <th>Status</th>
                  <th>Reviewed By</th>
                  <th>Reviewed At</th>
                  <th>Note</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($reviewedProperties as $property)
                  <tr>
                    <td class="px-3">
                      <div class="fw-semibold">{{ $property->title }}</div>
                      <div class="text-secondary small">৳{{ number_format($property->price) }} · {{ $property->location ?: 'Location not set' }}</div>
                    </td>
                    <td>
                      <div>{{ $property->user?->name ?: 'Unknown user' }}</div>
                      <div class="text-secondary small">{{ $property->user?->email ?: 'No email' }}</div>
                    </td>
                    <td>{{ ucfirst($property->purpose) }}</td>
                    <td>
                      <span class="review-status-badge review-status-{{ $property->status }}">
                        {{ ucfirst($property->status) }}
                      </span>
                    </td>
                    <td>{{ $property->reviewedBy?->name ?: 'Not recorded' }}</td>
                    <td>{{ optional($property->reviewed_at)->format('d M Y, h:i A') ?: 'Not reviewed' }}</td>
                    <td class="text-secondary small">{{ $property->review_note ?: 'No note' }}</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="7" class="px-3 py-4 text-center text-secondary">No reviewed properties yet.</td>
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
