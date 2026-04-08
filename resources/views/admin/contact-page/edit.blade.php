@extends('layouts.admin')

@section('page_title', 'Contact Page')
@section('page_heading', 'Contact Page Content')
@section('breadcrumb_current', 'Contact Page')

@push('styles')
  <style>
    .contact-preview {
      aspect-ratio: 16 / 9;
      background: #e2e8f0;
      border-radius: 1rem;
      overflow: hidden;
      width: 100%;
    }

    .contact-preview img {
      height: 100%;
      object-fit: cover;
      width: 100%;
    }

    .section-card {
      border: 1px solid rgba(15, 23, 42, 0.08);
      border-radius: 1rem;
    }

    .repeat-card {
      background: #f8fafc;
      border: 1px solid rgba(15, 23, 42, 0.08);
      border-radius: 1rem;
      height: 100%;
      padding: 1rem;
    }
  </style>
@endpush

@section('content')
  @php
    $testimonials = old('testimonials', $contactPage->testimonials ?? []);
    $brands = old('brands', $contactPage->brands ?? []);
    $updatedSection = session('updated_section');
  @endphp

  @if (session('status') === 'contact-page-updated')
    <div class="alert alert-success">
      Contact page section updated successfully.
      @if ($updatedSection)
        <span class="fw-semibold">Section:</span> {{ str($updatedSection)->replace('-', ' ')->title() }}
      @endif
    </div>
  @endif

  @if ($errors->any())
    <div class="alert alert-danger">Please review the highlighted Contact page fields and try again.</div>
  @endif

  <div class="card dashboard-hero mb-4">
    <div class="card-body p-4 p-lg-5 position-relative">
      <div class="row align-items-center g-4">
        <div class="col-lg-8">
          <span class="badge rounded-pill text-bg-light text-primary mb-3">Dynamic frontend content</span>
          <h1 class="h2 fw-bold mb-3">Manage the public Contact page and view recent inquiries.</h1>
          <p class="hero-meta mb-0">
            This page controls the real <code>/contact</code> screen, including the header image, form copy, testimonials, brand logos, and recent submitted messages.
          </p>
        </div>
        <div class="col-lg-4">
          <div class="info-grid">
            <div class="info-tile bg-white bg-opacity-10 border-0 text-white">
              <span class="label text-white-50">Saved inquiries</span>
              <div class="value text-white">{{ $messageCount }}</div>
            </div>
            <div class="info-tile bg-white bg-opacity-10 border-0 text-white">
              <span class="label text-white-50">Latest shown</span>
              <div class="value text-white">{{ $messages->count() }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-12" id="page-header">
      <div class="card section-card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h3 class="card-title fw-semibold mb-0">Page Header</h3>
          <a href="{{ route('contact') }}" class="btn btn-outline-primary btn-sm" target="_blank">Open Contact Page</a>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('admin.contact-page.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="section" value="page-header">

            <div class="row g-4">
              <div class="col-lg-5">
                <div class="contact-preview">
                  @if ($contactPage->imageUrlFor('hero'))
                    <img src="{{ $contactPage->imageUrlFor('hero') }}" alt="Contact page header image">
                  @endif
                </div>
              </div>
              <div class="col-lg-7">
                <div class="mb-3">
                  <label for="hero_title" class="form-label">Main Heading</label>
                  <input id="hero_title" name="hero_title" type="text" class="form-control @error('hero_title') is-invalid @enderror" value="{{ old('hero_title', $contactPage->hero_title) }}" required>
                  @error('hero_title')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-3">
                  <label for="hero_background" class="form-label">Replace Background Image</label>
                  <input id="hero_background" name="hero_background" type="file" class="form-control @error('hero_background') is-invalid @enderror" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                  @error('hero_background')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="d-flex justify-content-end">
                  <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>
                    Save Page Header
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-12" id="contact-form">
      <div class="card section-card">
        <div class="card-header">
          <h3 class="card-title fw-semibold mb-0">Contact Form Copy</h3>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('admin.contact-page.update') }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="section" value="contact-form">

            <div class="row g-4">
              <div class="col-lg-6">
                <div class="mb-3">
                  <label for="form_title" class="form-label">Form Title</label>
                  <input id="form_title" name="form_title" type="text" class="form-control @error('form_title') is-invalid @enderror" value="{{ old('form_title', $contactPage->form_title) }}" required>
                  @error('form_title')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-3">
                  <label for="submit_button_text" class="form-label">Submit Button Text</label>
                  <input id="submit_button_text" name="submit_button_text" type="text" class="form-control @error('submit_button_text') is-invalid @enderror" value="{{ old('submit_button_text', $contactPage->submit_button_text) }}" required>
                  @error('submit_button_text')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="col-lg-6">
                <div class="mb-3">
                  <label for="success_message" class="form-label">Success Message</label>
                  <input id="success_message" name="success_message" type="text" class="form-control @error('success_message') is-invalid @enderror" value="{{ old('success_message', $contactPage->success_message) }}" required>
                  @error('success_message')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-3">
                  <label for="form_intro" class="form-label">Form Intro</label>
                  <textarea id="form_intro" name="form_intro" rows="4" class="form-control @error('form_intro') is-invalid @enderror">{{ old('form_intro', $contactPage->form_intro) }}</textarea>
                  @error('form_intro')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>

            <div class="alert alert-light mb-0">
              Site phone, email, and address still come from the Site Info page.
            </div>

            <div class="d-flex justify-content-end mt-4">
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i>
                Save Form Copy
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-12" id="testimonials">
      <div class="card section-card">
        <div class="card-header">
          <h3 class="card-title fw-semibold mb-0">Testimonials Section</h3>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('admin.contact-page.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="section" value="testimonials">

            <div class="mb-4">
              <label for="testimonial_section_title" class="form-label">Section Title</label>
              <input id="testimonial_section_title" name="testimonial_section_title" type="text" class="form-control @error('testimonial_section_title') is-invalid @enderror" value="{{ old('testimonial_section_title', $contactPage->testimonial_section_title) }}" required>
              @error('testimonial_section_title')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="row g-4">
              @foreach ($testimonials as $index => $testimonial)
                <div class="col-xl-4 col-md-6">
                  <div class="repeat-card">
                    <div class="contact-preview mb-3" style="aspect-ratio: 4 / 4;">
                      @if ($contactPage->imageUrlFor('testimonials', $index))
                        <img src="{{ $contactPage->imageUrlFor('testimonials', $index) }}" alt="Testimonial avatar">
                      @endif
                    </div>
                    <h4 class="h6 fw-semibold mb-3">Testimonial {{ $index + 1 }}</h4>
                    <div class="mb-3">
                      <label for="testimonials_{{ $index }}_name" class="form-label">Name</label>
                      <input id="testimonials_{{ $index }}_name" name="testimonials[{{ $index }}][name]" type="text" class="form-control @error('testimonials.'.$index.'.name') is-invalid @enderror" value="{{ old('testimonials.'.$index.'.name', $testimonial['name'] ?? '') }}" required>
                      @error('testimonials.'.$index.'.name')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="mb-3">
                      <label for="testimonials_{{ $index }}_location" class="form-label">Location</label>
                      <input id="testimonials_{{ $index }}_location" name="testimonials[{{ $index }}][location]" type="text" class="form-control @error('testimonials.'.$index.'.location') is-invalid @enderror" value="{{ old('testimonials.'.$index.'.location', $testimonial['location'] ?? '') }}" required>
                      @error('testimonials.'.$index.'.location')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="mb-3">
                      <label for="testimonials_{{ $index }}_quote" class="form-label">Quote</label>
                      <textarea id="testimonials_{{ $index }}_quote" name="testimonials[{{ $index }}][quote]" rows="4" class="form-control @error('testimonials.'.$index.'.quote') is-invalid @enderror" required>{{ old('testimonials.'.$index.'.quote', $testimonial['quote'] ?? '') }}</textarea>
                      @error('testimonials.'.$index.'.quote')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="row g-3">
                      <div class="col-sm-6">
                        <label for="testimonials_{{ $index }}_rating" class="form-label">Rating</label>
                        <input id="testimonials_{{ $index }}_rating" name="testimonials[{{ $index }}][rating]" type="number" min="0" max="5" step="0.5" class="form-control @error('testimonials.'.$index.'.rating') is-invalid @enderror" value="{{ old('testimonials.'.$index.'.rating', $testimonial['rating'] ?? 5) }}" required>
                        @error('testimonials.'.$index.'.rating')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                      <div class="col-sm-6">
                        <label for="testimonials_{{ $index }}_avatar" class="form-label">Replace Avatar</label>
                        <input id="testimonials_{{ $index }}_avatar" name="testimonials[{{ $index }}][avatar]" type="file" class="form-control @error('testimonials.'.$index.'.avatar') is-invalid @enderror" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                        @error('testimonials.'.$index.'.avatar')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>

            <div class="d-flex justify-content-end mt-4">
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i>
                Save Testimonials
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-12" id="brands">
      <div class="card section-card">
        <div class="card-header">
          <h3 class="card-title fw-semibold mb-0">Brand Logos</h3>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('admin.contact-page.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="section" value="brands">

            <div class="row g-4">
              @foreach ($brands as $index => $brand)
                <div class="col-xl-4 col-md-6">
                  <div class="repeat-card">
                    <div class="contact-preview mb-3" style="aspect-ratio: 3 / 2;">
                      @if ($contactPage->imageUrlFor('brands', $index))
                        <img src="{{ $contactPage->imageUrlFor('brands', $index) }}" alt="Brand logo">
                      @endif
                    </div>
                    <h4 class="h6 fw-semibold mb-3">Brand {{ $index + 1 }}</h4>
                    <div class="mb-3">
                      <label for="brands_{{ $index }}_name" class="form-label">Logo Name</label>
                      <input id="brands_{{ $index }}_name" name="brands[{{ $index }}][name]" type="text" class="form-control @error('brands.'.$index.'.name') is-invalid @enderror" value="{{ old('brands.'.$index.'.name', $brand['name'] ?? '') }}" required>
                      @error('brands.'.$index.'.name')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="mb-0">
                      <label for="brands_{{ $index }}_image" class="form-label">Replace Logo</label>
                      <input id="brands_{{ $index }}_image" name="brands[{{ $index }}][image]" type="file" class="form-control @error('brands.'.$index.'.image') is-invalid @enderror" accept=".jpg,.jpeg,.png,.svg,.webp,image/jpeg,image/png,image/webp,image/svg+xml">
                      @error('brands.'.$index.'.image')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                </div>
              @endforeach
            </div>

            <div class="d-flex justify-content-end mt-4">
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i>
                Save Brand Logos
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-12">
      <div class="card section-card">
        <div class="card-header">
          <h3 class="card-title fw-semibold mb-0">Recent Inquiries</h3>
        </div>
        <div class="card-body p-0">
          @if ($messages->isEmpty())
            <div class="alert alert-light border-0 rounded-0 mb-0">No contact messages have been submitted yet.</div>
          @else
            <div class="table-responsive">
              <table class="table align-middle mb-0">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Received</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($messages as $message)
                    <tr>
                      <td class="fw-semibold">{{ $message->name }}</td>
                      <td>{{ $message->email }}</td>
                      <td style="min-width: 340px;">{{ \Illuminate\Support\Str::limit($message->message, 180) }}</td>
                      <td>{{ $message->created_at?->format('d M Y, h:i A') }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
@endsection
