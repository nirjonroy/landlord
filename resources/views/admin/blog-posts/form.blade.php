@extends('layouts.admin')

@section('page_title', $pageTitle)
@section('page_heading', $pageTitle)
@section('breadcrumb_current', 'Blog Posts')

@push('styles')
  <style>
    .post-preview {
      aspect-ratio: 16 / 10;
      background: #e2e8f0;
      border-radius: 1rem;
      overflow: hidden;
      width: 100%;
    }

    .post-preview img {
      height: 100%;
      object-fit: cover;
      width: 100%;
    }

    .repeat-card {
      background: #f8fafc;
      border: 1px solid rgba(15, 23, 42, 0.08);
      border-radius: 1rem;
      height: 100%;
      padding: 1rem;
    }

    .comment-avatar-preview {
      width: 68px;
      height: 68px;
      border-radius: 50%;
      object-fit: cover;
      display: block;
    }
  </style>
@endpush

@section('content')
  @php
    $comments = old('comments', $commentSlots->toArray());
    $tagsInput = old('tags_input', implode(', ', $blogPost->tags ?? []));
  @endphp

  @if (session('status') === 'blog-post-created')
    <div class="alert alert-success">Blog post created successfully.</div>
  @elseif (session('status') === 'blog-post-updated')
    <div class="alert alert-success">Blog post updated successfully.</div>
  @endif

  @if ($errors->any())
    <div class="alert alert-danger">Please review the highlighted blog post fields and try again.</div>
  @endif

  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h3 class="card-title fw-semibold mb-0">{{ $pageTitle }}</h3>
      <div class="d-flex gap-2">
        <a href="{{ route('admin.blog-posts.index') }}" class="btn btn-outline-secondary btn-sm">Back to Posts</a>
        @if ($blogPost->exists && $blogPost->is_published && $blogPost->published_at)
          <a href="{{ route('blog.show', $blogPost) }}" class="btn btn-outline-primary btn-sm" target="_blank">Preview Post</a>
        @endif
      </div>
    </div>
    <div class="card-body">
      <form method="POST" action="{{ $formAction }}" enctype="multipart/form-data">
        @csrf
        @if ($formMethod !== 'POST')
          @method($formMethod)
        @endif

        <div class="row g-4">
          <div class="col-lg-8">
            <div class="mb-3">
              <label for="title" class="form-label">Post Title</label>
              <input id="title" name="title" type="text" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $blogPost->title) }}" required>
              @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="row g-3">
              <div class="col-md-6">
                <label for="blog_category_id" class="form-label">Category</label>
                <select id="blog_category_id" name="blog_category_id" class="form-select @error('blog_category_id') is-invalid @enderror">
                  <option value="">Select category</option>
                  @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected((string) old('blog_category_id', $blogPost->blog_category_id) === (string) $category->id)>{{ $category->name }}</option>
                  @endforeach
                </select>
                @error('blog_category_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6">
                <label for="author_name" class="form-label">Author Name</label>
                <input id="author_name" name="author_name" type="text" class="form-control @error('author_name') is-invalid @enderror" value="{{ old('author_name', $blogPost->author_name) }}" required>
                @error('author_name')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="row g-3 mt-0">
              <div class="col-md-6">
                <label for="slug" class="form-label">Slug</label>
                <input id="slug" name="slug" type="text" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $blogPost->slug) }}">
                @error('slug')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6">
                <label for="published_at" class="form-label">Publish Date</label>
                <input id="published_at" name="published_at" type="datetime-local" class="form-control @error('published_at') is-invalid @enderror" value="{{ old('published_at', optional($blogPost->published_at)->format('Y-m-d\TH:i')) }}">
                @error('published_at')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="mb-3 mt-3">
              <label for="excerpt" class="form-label">Excerpt</label>
              <textarea id="excerpt" name="excerpt" rows="3" class="form-control @error('excerpt') is-invalid @enderror" required>{{ old('excerpt', $blogPost->excerpt) }}</textarea>
              @error('excerpt')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="content" class="form-label">Article Content</label>
              <textarea id="content" name="content" rows="12" class="form-control @error('content') is-invalid @enderror" required>{{ old('content', $blogPost->content) }}</textarea>
              @error('content')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="quote" class="form-label">Highlight Quote</label>
              <textarea id="quote" name="quote" rows="3" class="form-control @error('quote') is-invalid @enderror">{{ old('quote', $blogPost->quote) }}</textarea>
              @error('quote')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="col-lg-4">
            <div class="post-preview mb-3">
              @if ($blogPost->imageUrlFor('featured'))
                <img src="{{ $blogPost->imageUrlFor('featured') }}" alt="{{ $blogPost->title ?: 'Featured image' }}">
              @endif
            </div>

            <div class="mb-3">
              <label for="featured_image" class="form-label">Featured Image</label>
              <input id="featured_image" name="featured_image" type="file" class="form-control @error('featured_image') is-invalid @enderror" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" {{ $blogPost->exists ? '' : 'required' }}>
              @error('featured_image')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="tags_input" class="form-label">Tags</label>
              <input id="tags_input" name="tags_input" type="text" class="form-control @error('tags_input') is-invalid @enderror" value="{{ $tagsInput }}" placeholder="dhaka, rent, landlord tips">
              <div class="form-text">Separate tags with commas.</div>
              @error('tags_input')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="meta_description" class="form-label">Meta Description</label>
              <textarea id="meta_description" name="meta_description" rows="4" class="form-control @error('meta_description') is-invalid @enderror">{{ old('meta_description', $blogPost->meta_description) }}</textarea>
              @error('meta_description')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="form-check mb-2">
              <input id="is_published" name="is_published" type="checkbox" class="form-check-input" value="1" {{ old('is_published', $blogPost->is_published) ? 'checked' : '' }}>
              <label for="is_published" class="form-check-label">Published</label>
            </div>

            <div class="form-check mb-4">
              <input id="show_on_home" name="show_on_home" type="checkbox" class="form-check-input" value="1" {{ old('show_on_home', $blogPost->show_on_home) ? 'checked' : '' }}>
              <label for="show_on_home" class="form-check-label">Show on homepage news section</label>
            </div>
          </div>

          <div class="col-12">
            <div class="border-top pt-4">
              <h4 class="h5 fw-semibold mb-3">Detail Page Comments</h4>
              <p class="text-muted small mb-4">These optional comments are shown on the public blog details page. Leave a card empty if you do not want to display it.</p>

              <div class="row g-4">
                @foreach ($comments as $index => $comment)
                  <div class="col-lg-4">
                    <div class="repeat-card">
                      <div class="mb-3">
                        @if (!empty($comment['avatar_path']) && !empty($comment['avatar_source']))
                          <img src="{{ $blogPost->imageUrlFor('comments', $index) ?: asset('frontend-assets/img/avatar_4.jpg') }}" alt="Comment avatar" class="comment-avatar-preview mb-3">
                        @endif
                        <label for="comments_{{ $index }}_avatar" class="form-label">Avatar</label>
                        <input id="comments_{{ $index }}_avatar" name="comments[{{ $index }}][avatar]" type="file" class="form-control @error('comments.'.$index.'.avatar') is-invalid @enderror" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                        @error('comments.'.$index.'.avatar')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                      <div class="mb-3">
                        <label for="comments_{{ $index }}_name" class="form-label">Name</label>
                        <input id="comments_{{ $index }}_name" name="comments[{{ $index }}][name]" type="text" class="form-control @error('comments.'.$index.'.name') is-invalid @enderror" value="{{ old('comments.'.$index.'.name', $comment['name'] ?? '') }}">
                        @error('comments.'.$index.'.name')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                      <div class="mb-3">
                        <label for="comments_{{ $index }}_date_label" class="form-label">Date Label</label>
                        <input id="comments_{{ $index }}_date_label" name="comments[{{ $index }}][date_label]" type="text" class="form-control @error('comments.'.$index.'.date_label') is-invalid @enderror" value="{{ old('comments.'.$index.'.date_label', $comment['date_label'] ?? '') }}">
                        @error('comments.'.$index.'.date_label')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                      <div class="mb-0">
                        <label for="comments_{{ $index }}_body" class="form-label">Comment</label>
                        <textarea id="comments_{{ $index }}_body" name="comments[{{ $index }}][body]" rows="4" class="form-control @error('comments.'.$index.'.body') is-invalid @enderror">{{ old('comments.'.$index.'.body', $comment['body'] ?? '') }}</textarea>
                        @error('comments.'.$index.'.body')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>

        <div class="d-flex justify-content-end mt-4">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i>
            {{ $submitLabel }}
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection
