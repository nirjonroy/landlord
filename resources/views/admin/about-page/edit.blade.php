@extends('layouts.admin')

@section('page_title', 'About Page')
@section('page_heading', 'About Page Content')
@section('breadcrumb_current', 'About Page')

@push('styles')
  <style>
    .about-preview {
      aspect-ratio: 16 / 9;
      background: #e2e8f0;
      border-radius: 1rem;
      overflow: hidden;
      width: 100%;
    }

    .about-preview img {
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
    $stats = old('stats', $aboutPage->stats ?? []);
    $teamMembers = old('team_members', $aboutPage->team_members ?? []);
    $services = old('services', $aboutPage->services ?? []);
    $testimonials = old('testimonials', $aboutPage->testimonials ?? []);
    $brands = old('brands', $aboutPage->brands ?? []);
    $faqs = old('faqs', $aboutPage->faqs ?? []);
    $updatedSection = session('updated_section');
  @endphp

  @if (session('status') === 'about-page-updated')
    <div class="alert alert-success">
      About page section updated successfully.
      @if ($updatedSection)
        <span class="fw-semibold">Section:</span> {{ str($updatedSection)->replace('-', ' ')->title() }}
      @endif
    </div>
  @endif

  @if ($errors->any())
    <div class="alert alert-danger">Please review the highlighted About page fields and try again.</div>
  @endif

  <div class="card dashboard-hero mb-4">
    <div class="card-body p-4 p-lg-5 position-relative">
      <div class="row align-items-center g-4">
        <div class="col-lg-8">
          <span class="badge rounded-pill text-bg-light text-primary mb-3">Dynamic frontend content</span>
          <h1 class="h2 fw-bold mb-3">Manage every major section of the public About page from here.</h1>
          <p class="hero-meta mb-0">
            This page controls the real <code>/about</code> screen, including the banner, mission, vision, counters, team, services, testimonials, brands, and FAQs.
          </p>
        </div>
        <div class="col-lg-4">
          <div class="info-grid">
            <div class="info-tile bg-white bg-opacity-10 border-0 text-white">
              <span class="label text-white-50">Team members</span>
              <div class="value text-white">{{ count($aboutPage->team_members ?? []) }}</div>
            </div>
            <div class="info-tile bg-white bg-opacity-10 border-0 text-white">
              <span class="label text-white-50">FAQ items</span>
              <div class="value text-white">{{ count($aboutPage->faqs ?? []) }}</div>
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
          <a href="{{ route('about') }}" class="btn btn-outline-primary btn-sm" target="_blank">Open About Page</a>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('admin.about-page.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="section" value="page-header">

            <div class="row g-4">
              <div class="col-lg-5">
                <div class="about-preview">
                  @if ($aboutPage->imageUrlFor('hero'))
                    <img src="{{ $aboutPage->imageUrlFor('hero') }}" alt="About page header image">
                  @endif
                </div>
                <div class="form-text mt-2">Current page-header background image.</div>
              </div>
              <div class="col-lg-7">
                <div class="mb-3">
                  <label for="hero_title" class="form-label">Main Heading</label>
                  <input id="hero_title" name="hero_title" type="text" class="form-control @error('hero_title') is-invalid @enderror" value="{{ old('hero_title', $aboutPage->hero_title) }}" required>
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

    <div class="col-12" id="mission">
      <div class="card section-card">
        <div class="card-header">
          <h3 class="card-title fw-semibold mb-0">Mission Section</h3>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('admin.about-page.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="section" value="mission">

            <div class="row g-4">
              <div class="col-lg-5">
                <div class="about-preview">
                  @if ($aboutPage->imageUrlFor('mission'))
                    <img src="{{ $aboutPage->imageUrlFor('mission') }}" alt="Mission image">
                  @endif
                </div>
              </div>
              <div class="col-lg-7">
                <div class="mb-3">
                  <label for="mission_section_title" class="form-label">Section Title</label>
                  <input id="mission_section_title" name="mission_section_title" type="text" class="form-control @error('mission_section_title') is-invalid @enderror" value="{{ old('mission_section_title', $aboutPage->mission_section_title) }}" required>
                  @error('mission_section_title')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-3">
                  <label for="mission_section_intro" class="form-label">Section Intro</label>
                  <textarea id="mission_section_intro" name="mission_section_intro" rows="2" class="form-control @error('mission_section_intro') is-invalid @enderror">{{ old('mission_section_intro', $aboutPage->mission_section_intro) }}</textarea>
                  @error('mission_section_intro')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-3">
                  <label for="mission_heading" class="form-label">Highlight Heading</label>
                  <input id="mission_heading" name="mission_heading" type="text" class="form-control @error('mission_heading') is-invalid @enderror" value="{{ old('mission_heading', $aboutPage->mission_heading) }}" required>
                  @error('mission_heading')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-3">
                  <label for="mission_body" class="form-label">Body Text</label>
                  <textarea id="mission_body" name="mission_body" rows="6" class="form-control @error('mission_body') is-invalid @enderror">{{ old('mission_body', $aboutPage->mission_body) }}</textarea>
                  @error('mission_body')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-3">
                  <label for="mission_image" class="form-label">Replace Mission Image</label>
                  <input id="mission_image" name="mission_image" type="file" class="form-control @error('mission_image') is-invalid @enderror" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                  @error('mission_image')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="d-flex justify-content-end">
                  <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>
                    Save Mission
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-12" id="vision">
      <div class="card section-card">
        <div class="card-header">
          <h3 class="card-title fw-semibold mb-0">Vision Section</h3>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('admin.about-page.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="section" value="vision">

            <div class="row g-4">
              <div class="col-lg-7">
                <div class="mb-3">
                  <label for="vision_section_title" class="form-label">Section Title</label>
                  <input id="vision_section_title" name="vision_section_title" type="text" class="form-control @error('vision_section_title') is-invalid @enderror" value="{{ old('vision_section_title', $aboutPage->vision_section_title) }}" required>
                  @error('vision_section_title')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-3">
                  <label for="vision_section_intro" class="form-label">Section Intro</label>
                  <textarea id="vision_section_intro" name="vision_section_intro" rows="2" class="form-control @error('vision_section_intro') is-invalid @enderror">{{ old('vision_section_intro', $aboutPage->vision_section_intro) }}</textarea>
                  @error('vision_section_intro')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-3">
                  <label for="vision_heading" class="form-label">Highlight Heading</label>
                  <input id="vision_heading" name="vision_heading" type="text" class="form-control @error('vision_heading') is-invalid @enderror" value="{{ old('vision_heading', $aboutPage->vision_heading) }}" required>
                  @error('vision_heading')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-3">
                  <label for="vision_body" class="form-label">Body Text</label>
                  <textarea id="vision_body" name="vision_body" rows="6" class="form-control @error('vision_body') is-invalid @enderror">{{ old('vision_body', $aboutPage->vision_body) }}</textarea>
                  @error('vision_body')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-3">
                  <label for="vision_image" class="form-label">Replace Vision Image</label>
                  <input id="vision_image" name="vision_image" type="file" class="form-control @error('vision_image') is-invalid @enderror" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                  @error('vision_image')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="d-flex justify-content-end">
                  <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>
                    Save Vision
                  </button>
                </div>
              </div>
              <div class="col-lg-5">
                <div class="about-preview">
                  @if ($aboutPage->imageUrlFor('vision'))
                    <img src="{{ $aboutPage->imageUrlFor('vision') }}" alt="Vision image">
                  @endif
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-12" id="stats">
      <div class="card section-card">
        <div class="card-header">
          <h3 class="card-title fw-semibold mb-0">Counter Section</h3>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('admin.about-page.update') }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="section" value="stats">

            <div class="row g-4">
              @foreach ($stats as $index => $item)
                <div class="col-lg-3 col-md-6">
                  <div class="repeat-card">
                    <h4 class="h6 fw-semibold mb-3">Counter {{ $index + 1 }}</h4>
                    <div class="mb-3">
                      <label for="stats_{{ $index }}_value" class="form-label">Value</label>
                      <input id="stats_{{ $index }}_value" name="stats[{{ $index }}][value]" type="number" min="0" class="form-control @error('stats.'.$index.'.value') is-invalid @enderror" value="{{ old('stats.'.$index.'.value', $item['value'] ?? '') }}" required>
                      @error('stats.'.$index.'.value')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="mb-0">
                      <label for="stats_{{ $index }}_label" class="form-label">Label</label>
                      <input id="stats_{{ $index }}_label" name="stats[{{ $index }}][label]" type="text" class="form-control @error('stats.'.$index.'.label') is-invalid @enderror" value="{{ old('stats.'.$index.'.label', $item['label'] ?? '') }}" required>
                      @error('stats.'.$index.'.label')
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
                Save Counters
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-12" id="team">
      <div class="card section-card">
        <div class="card-header">
          <h3 class="card-title fw-semibold mb-0">Team Section</h3>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('admin.about-page.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="section" value="team">

            <div class="mb-4">
              <label for="team_section_title" class="form-label">Section Title</label>
              <input id="team_section_title" name="team_section_title" type="text" class="form-control @error('team_section_title') is-invalid @enderror" value="{{ old('team_section_title', $aboutPage->team_section_title) }}" required>
              @error('team_section_title')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="row g-4">
              @foreach ($teamMembers as $index => $member)
                <div class="col-xl-4 col-md-6">
                  <div class="repeat-card">
                    <div class="about-preview mb-3" style="aspect-ratio: 4 / 4;">
                      @if ($aboutPage->imageUrlFor('team', $index))
                        <img src="{{ $aboutPage->imageUrlFor('team', $index) }}" alt="Team member image">
                      @endif
                    </div>
                    <h4 class="h6 fw-semibold mb-3">Team Member {{ $index + 1 }}</h4>
                    <div class="mb-3">
                      <label for="team_members_{{ $index }}_name" class="form-label">Name</label>
                      <input id="team_members_{{ $index }}_name" name="team_members[{{ $index }}][name]" type="text" class="form-control @error('team_members.'.$index.'.name') is-invalid @enderror" value="{{ old('team_members.'.$index.'.name', $member['name'] ?? '') }}" required>
                      @error('team_members.'.$index.'.name')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="mb-3">
                      <label for="team_members_{{ $index }}_role" class="form-label">Role</label>
                      <input id="team_members_{{ $index }}_role" name="team_members[{{ $index }}][role]" type="text" class="form-control @error('team_members.'.$index.'.role') is-invalid @enderror" value="{{ old('team_members.'.$index.'.role', $member['role'] ?? '') }}" required>
                      @error('team_members.'.$index.'.role')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="mb-0">
                      <label for="team_members_{{ $index }}_image" class="form-label">Replace Photo</label>
                      <input id="team_members_{{ $index }}_image" name="team_members[{{ $index }}][image]" type="file" class="form-control @error('team_members.'.$index.'.image') is-invalid @enderror" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                      @error('team_members.'.$index.'.image')
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
                Save Team
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-12" id="services">
      <div class="card section-card">
        <div class="card-header">
          <h3 class="card-title fw-semibold mb-0">Services Section</h3>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('admin.about-page.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="section" value="services">

            <div class="mb-4">
              <label for="services_section_title" class="form-label">Section Title</label>
              <input id="services_section_title" name="services_section_title" type="text" class="form-control @error('services_section_title') is-invalid @enderror" value="{{ old('services_section_title', $aboutPage->services_section_title) }}" required>
              @error('services_section_title')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="row g-4">
              @foreach ($services as $index => $service)
                <div class="col-xl-4 col-md-6">
                  <div class="repeat-card">
                    <div class="about-preview mb-3">
                      @if ($aboutPage->imageUrlFor('services', $index))
                        <img src="{{ $aboutPage->imageUrlFor('services', $index) }}" alt="Service image">
                      @endif
                    </div>
                    <h4 class="h6 fw-semibold mb-3">Service {{ $index + 1 }}</h4>
                    <div class="mb-3">
                      <label for="services_{{ $index }}_title" class="form-label">Title</label>
                      <input id="services_{{ $index }}_title" name="services[{{ $index }}][title]" type="text" class="form-control @error('services.'.$index.'.title') is-invalid @enderror" value="{{ old('services.'.$index.'.title', $service['title'] ?? '') }}" required>
                      @error('services.'.$index.'.title')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="mb-3">
                      <label for="services_{{ $index }}_subtitle" class="form-label">Subtitle</label>
                      <input id="services_{{ $index }}_subtitle" name="services[{{ $index }}][subtitle]" type="text" class="form-control @error('services.'.$index.'.subtitle') is-invalid @enderror" value="{{ old('services.'.$index.'.subtitle', $service['subtitle'] ?? '') }}">
                      @error('services.'.$index.'.subtitle')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="mb-0">
                      <label for="services_{{ $index }}_image" class="form-label">Replace Image</label>
                      <input id="services_{{ $index }}_image" name="services[{{ $index }}][image]" type="file" class="form-control @error('services.'.$index.'.image') is-invalid @enderror" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                      @error('services.'.$index.'.image')
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
                Save Services
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
          <form method="POST" action="{{ route('admin.about-page.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="section" value="testimonials">

            <div class="mb-4">
              <label for="testimonial_section_title" class="form-label">Section Title</label>
              <input id="testimonial_section_title" name="testimonial_section_title" type="text" class="form-control @error('testimonial_section_title') is-invalid @enderror" value="{{ old('testimonial_section_title', $aboutPage->testimonial_section_title) }}" required>
              @error('testimonial_section_title')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="row g-4">
              @foreach ($testimonials as $index => $testimonial)
                <div class="col-xl-4 col-md-6">
                  <div class="repeat-card">
                    <div class="about-preview mb-3" style="aspect-ratio: 4 / 4;">
                      @if ($aboutPage->imageUrlFor('testimonials', $index))
                        <img src="{{ $aboutPage->imageUrlFor('testimonials', $index) }}" alt="Testimonial avatar">
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
          <form method="POST" action="{{ route('admin.about-page.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="section" value="brands">

            <div class="row g-4">
              @foreach ($brands as $index => $brand)
                <div class="col-xl-4 col-md-6">
                  <div class="repeat-card">
                    <div class="about-preview mb-3" style="aspect-ratio: 3 / 2;">
                      @if ($aboutPage->imageUrlFor('brands', $index))
                        <img src="{{ $aboutPage->imageUrlFor('brands', $index) }}" alt="Brand logo">
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

    <div class="col-12" id="faq">
      <div class="card section-card">
        <div class="card-header">
          <h3 class="card-title fw-semibold mb-0">FAQ Section</h3>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('admin.about-page.update') }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="section" value="faq">

            <div class="mb-4">
              <label for="faq_section_title" class="form-label">Section Title</label>
              <input id="faq_section_title" name="faq_section_title" type="text" class="form-control @error('faq_section_title') is-invalid @enderror" value="{{ old('faq_section_title', $aboutPage->faq_section_title) }}" required>
              @error('faq_section_title')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="row g-4">
              @foreach ($faqs as $index => $faq)
                <div class="col-lg-4">
                  <div class="repeat-card">
                    <h4 class="h6 fw-semibold mb-3">FAQ {{ $index + 1 }}</h4>
                    <div class="mb-3">
                      <label for="faqs_{{ $index }}_question" class="form-label">Question</label>
                      <input id="faqs_{{ $index }}_question" name="faqs[{{ $index }}][question]" type="text" class="form-control @error('faqs.'.$index.'.question') is-invalid @enderror" value="{{ old('faqs.'.$index.'.question', $faq['question'] ?? '') }}" required>
                      @error('faqs.'.$index.'.question')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="mb-0">
                      <label for="faqs_{{ $index }}_answer" class="form-label">Answer</label>
                      <textarea id="faqs_{{ $index }}_answer" name="faqs[{{ $index }}][answer]" rows="6" class="form-control @error('faqs.'.$index.'.answer') is-invalid @enderror" required>{{ old('faqs.'.$index.'.answer', $faq['answer'] ?? '') }}</textarea>
                      @error('faqs.'.$index.'.answer')
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
                Save FAQ
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
