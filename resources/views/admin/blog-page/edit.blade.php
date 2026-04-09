@extends('layouts.admin')

@section('page_title', 'Blog Page')
@section('page_heading', 'Blog Page Content')
@section('breadcrumb_current', 'Blog Page')

@push('styles')
  <style>
    .blog-preview {
      aspect-ratio: 16 / 9;
      background: #e2e8f0;
      border-radius: 1rem;
      overflow: hidden;
      width: 100%;
    }

    .blog-preview img {
      height: 100%;
      object-fit: cover;
      width: 100%;
    }

    .section-card {
      border: 1px solid rgba(15, 23, 42, 0.08);
      border-radius: 1rem;
    }
  </style>
@endpush

@section('content')
  @php($updatedSection = session('updated_section'))

  @if (session('status') === 'blog-page-updated')
    <div class="alert alert-success">
      Blog page section updated successfully.
      @if ($updatedSection)
        <span class="fw-semibold">Section:</span> {{ str($updatedSection)->replace('-', ' ')->title() }}
      @endif
    </div>
  @endif

  @if ($errors->any())
    <div class="alert alert-danger">Please review the highlighted blog page fields and try again.</div>
  @endif

  <div class="card dashboard-hero mb-4">
    <div class="card-body p-4 p-lg-5 position-relative">
      <div class="row align-items-center g-4">
        <div class="col-lg-8">
          <span class="badge rounded-pill text-bg-light text-primary mb-3">Dynamic blog CMS</span>
          <h1 class="h2 fw-bold mb-3">Manage the public blog listing page and shared blog labels from here.</h1>
          <p class="hero-meta mb-0">
            Blog posts and categories have their own admin pages. This screen controls the blog hero area and the labels shared across the blog list, blog details, and homepage news section.
          </p>
        </div>
        <div class="col-lg-4">
          <div class="info-grid">
            <div class="info-tile bg-white bg-opacity-10 border-0 text-white">
              <span class="label text-white-50">Categories</span>
              <div class="value text-white">{{ $categoryCount }}</div>
            </div>
            <div class="info-tile bg-white bg-opacity-10 border-0 text-white">
              <span class="label text-white-50">Published posts</span>
              <div class="value text-white">{{ $publishedPostCount }} / {{ $postCount }}</div>
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
          <a href="{{ route('blog.index') }}" class="btn btn-outline-primary btn-sm" target="_blank">Open Blog Page</a>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('admin.blog-page.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="section" value="page-header">

            <div class="row g-4">
              <div class="col-lg-5">
                <div class="blog-preview">
                  @if ($blogPage->imageUrlFor('hero'))
                    <img src="{{ $blogPage->imageUrlFor('hero') }}" alt="Blog page header image">
                  @endif
                </div>
                <div class="form-text mt-2">Current blog page background image.</div>
              </div>
              <div class="col-lg-7">
                <div class="mb-3">
                  <label for="hero_title" class="form-label">Main Heading</label>
                  <input id="hero_title" name="hero_title" type="text" class="form-control @error('hero_title') is-invalid @enderror" value="{{ old('hero_title', $blogPage->hero_title) }}" required>
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

    <div class="col-12" id="labels">
      <div class="card section-card">
        <div class="card-header">
          <h3 class="card-title fw-semibold mb-0">Template Labels</h3>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('admin.blog-page.update') }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="section" value="labels">

            <div class="row g-4">
              <div class="col-md-6">
                <label for="home_section_title" class="form-label">Homepage Blog Section Title</label>
                <input id="home_section_title" name="home_section_title" type="text" class="form-control @error('home_section_title') is-invalid @enderror" value="{{ old('home_section_title', $blogPage->home_section_title) }}" required>
                @error('home_section_title')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6">
                <label for="read_button_text" class="form-label">Read Button Text</label>
                <input id="read_button_text" name="read_button_text" type="text" class="form-control @error('read_button_text') is-invalid @enderror" value="{{ old('read_button_text', $blogPage->read_button_text) }}" required>
                @error('read_button_text')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6">
                <label for="categories_title" class="form-label">Sidebar Categories Title</label>
                <input id="categories_title" name="categories_title" type="text" class="form-control @error('categories_title') is-invalid @enderror" value="{{ old('categories_title', $blogPage->categories_title) }}" required>
                @error('categories_title')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6">
                <label for="recommendation_title" class="form-label">Sidebar Recommendation Title</label>
                <input id="recommendation_title" name="recommendation_title" type="text" class="form-control @error('recommendation_title') is-invalid @enderror" value="{{ old('recommendation_title', $blogPage->recommendation_title) }}" required>
                @error('recommendation_title')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6">
                <label for="latest_posts_title" class="form-label">Latest Posts Label</label>
                <input id="latest_posts_title" name="latest_posts_title" type="text" class="form-control @error('latest_posts_title') is-invalid @enderror" value="{{ old('latest_posts_title', $blogPage->latest_posts_title) }}" required>
                @error('latest_posts_title')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6">
                <label for="tags_title" class="form-label">Popular Tags Label</label>
                <input id="tags_title" name="tags_title" type="text" class="form-control @error('tags_title') is-invalid @enderror" value="{{ old('tags_title', $blogPage->tags_title) }}" required>
                @error('tags_title')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6">
                <label for="article_tags_title" class="form-label">Detail Page Article Tags Label</label>
                <input id="article_tags_title" name="article_tags_title" type="text" class="form-control @error('article_tags_title') is-invalid @enderror" value="{{ old('article_tags_title', $blogPage->article_tags_title) }}" required>
                @error('article_tags_title')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6">
                <label for="comments_section_title" class="form-label">Detail Page Comments Label</label>
                <input id="comments_section_title" name="comments_section_title" type="text" class="form-control @error('comments_section_title') is-invalid @enderror" value="{{ old('comments_section_title', $blogPage->comments_section_title) }}" required>
                @error('comments_section_title')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i>
                Save Labels
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
