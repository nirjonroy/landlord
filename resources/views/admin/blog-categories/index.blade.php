@extends('layouts.admin')

@section('page_title', 'Blog Categories')
@section('page_heading', 'Blog Categories')
@section('breadcrumb_current', 'Blog Categories')

@section('content')
  @if (session('status') === 'blog-category-created')
    <div class="alert alert-success">Blog category created successfully.</div>
  @elseif (session('status') === 'blog-category-updated')
    <div class="alert alert-success">Blog category updated successfully.</div>
  @elseif (session('status') === 'blog-category-deleted')
    <div class="alert alert-success">Blog category deleted successfully.</div>
  @endif

  @if ($errors->any())
    <div class="alert alert-danger">Please review the highlighted category fields and try again.</div>
  @endif

  <div class="card dashboard-hero mb-4">
    <div class="card-body p-4 p-lg-5 position-relative">
      <div class="row align-items-center g-4">
        <div class="col-lg-8">
          <span class="badge rounded-pill text-bg-light text-primary mb-3">Blog taxonomy</span>
          <h1 class="h2 fw-bold mb-3">Create and manage the categories used across the blog listing and detail pages.</h1>
          <p class="hero-meta mb-0">Active categories show in the frontend sidebar and can be attached to blog posts from the posts manager.</p>
        </div>
        <div class="col-lg-4">
          <div class="info-tile bg-white bg-opacity-10 border-0 text-white">
            <span class="label text-white-50">Total categories</span>
            <div class="value text-white">{{ $categories->count() }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-lg-4">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title fw-semibold mb-0">Create Category</h3>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('admin.blog-categories.store') }}">
            @csrf

            <div class="mb-3">
              <label for="name" class="form-label">Name</label>
              <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="slug" class="form-label">Slug</label>
              <input id="slug" name="slug" type="text" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}">
              @error('slug')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="description" class="form-label">Description</label>
              <textarea id="description" name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
              @error('description')
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

            <div class="form-check mb-4">
              <input id="is_active" name="is_active" type="checkbox" class="form-check-input" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
              <label for="is_active" class="form-check-label">Active on frontend</label>
            </div>

            <button type="submit" class="btn btn-primary w-100">
              <i class="bi bi-plus-circle me-1"></i>
              Add Category
            </button>
          </form>
        </div>
      </div>
    </div>

    <div class="col-lg-8">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title fw-semibold mb-0">Existing Categories</h3>
        </div>
        <div class="card-body">
          @forelse ($categories as $category)
            <div class="border rounded-4 p-3 mb-3">
              <form method="POST" action="{{ route('admin.blog-categories.update', $category) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                  <div class="col-md-4">
                    <label class="form-label">Name</label>
                    <input name="name" type="text" class="form-control" value="{{ old('name', $category->name) }}" required>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">Slug</label>
                    <input name="slug" type="text" class="form-control" value="{{ old('slug', $category->slug) }}">
                  </div>
                  <div class="col-md-2">
                    <label class="form-label">Order</label>
                    <input name="display_order" type="number" min="0" class="form-control" value="{{ old('display_order', $category->display_order) }}">
                  </div>
                  <div class="col-md-2 d-flex align-items-end">
                    <div class="form-check mb-2">
                      <input id="category_active_{{ $category->id }}" name="is_active" type="checkbox" class="form-check-input" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                      <label for="category_active_{{ $category->id }}" class="form-check-label">Active</label>
                    </div>
                  </div>
                  <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="3" class="form-control">{{ old('description', $category->description) }}</textarea>
                  </div>
                  <div class="col-12 d-flex justify-content-between align-items-center">
                    <span class="text-muted small">{{ $category->posts_count }} post(s) linked</span>
                    <button type="submit" class="btn btn-primary btn-sm">
                      <i class="bi bi-save me-1"></i>
                      Save
                    </button>
                  </div>
                </div>
              </form>

              <form method="POST" action="{{ route('admin.blog-categories.destroy', $category) }}" class="mt-2" onsubmit="return confirm('Delete this category? Linked posts will keep working but lose their category.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm">
                  <i class="bi bi-trash me-1"></i>
                  Delete Category
                </button>
              </form>
            </div>
          @empty
            <div class="alert alert-light border mb-0">No blog categories exist yet.</div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
@endsection
