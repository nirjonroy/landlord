@extends('layouts.admin')

@section('page_title', 'Blog Posts')
@section('page_heading', 'Blog Posts')
@section('breadcrumb_current', 'Blog Posts')

@section('content')
  @if (session('status') === 'blog-post-deleted')
    <div class="alert alert-success">Blog post deleted successfully.</div>
  @endif

  <div class="card dashboard-hero mb-4">
    <div class="card-body p-4 p-lg-5 position-relative">
      <div class="row align-items-center g-4">
        <div class="col-lg-8">
          <span class="badge rounded-pill text-bg-light text-primary mb-3">Post manager</span>
          <h1 class="h2 fw-bold mb-3">Create, publish, and organize the posts used on the blog listing, detail page, and homepage news section.</h1>
          <p class="hero-meta mb-0">Use the post editor for article text, image, tags, and the optional comment list shown on the detail page.</p>
        </div>
        <div class="col-lg-4">
          <div class="info-grid">
            <div class="info-tile bg-white bg-opacity-10 border-0 text-white">
              <span class="label text-white-50">Posts</span>
              <div class="value text-white">{{ $postCount }}</div>
            </div>
            <div class="info-tile bg-white bg-opacity-10 border-0 text-white">
              <span class="label text-white-50">Homepage picks</span>
              <div class="value text-white">{{ $homePostCount }}</div>
            </div>
          </div>
        </div>
      </div>
      <div class="mt-4 d-flex gap-2 flex-wrap">
        <a href="{{ route('admin.blog-posts.create') }}" class="btn btn-light btn-sm">
          <i class="bi bi-plus-circle me-1"></i>
          Create Post
        </a>
        <a href="{{ route('blog.index') }}" class="btn btn-outline-light btn-sm" target="_blank">Open Blog Page</a>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <h3 class="card-title fw-semibold mb-0">All Posts</h3>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped align-middle mb-0">
          <thead>
            <tr>
              <th>Post</th>
              <th>Category</th>
              <th>Published</th>
              <th>Status</th>
              <th>Home</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($posts as $post)
              <tr>
                <td>
                  <div class="fw-semibold">{{ $post->title }}</div>
                  <div class="text-muted small">{{ $post->author_name }}</div>
                </td>
                <td>{{ $post->category?->name ?: 'Uncategorized' }}</td>
                <td>{{ $post->published_at ? $post->published_at->format('d M Y') : 'Not scheduled' }}</td>
                <td>
                  @if ($post->is_published)
                    <span class="badge text-bg-success">Published</span>
                  @else
                    <span class="badge text-bg-warning">Draft</span>
                  @endif
                </td>
                <td>
                  @if ($post->show_on_home)
                    <span class="badge text-bg-primary">Homepage</span>
                  @else
                    <span class="badge text-bg-secondary">No</span>
                  @endif
                </td>
                <td class="text-end">
                  <div class="d-inline-flex gap-2">
                    @if ($post->is_published && $post->published_at)
                      <a href="{{ route('blog.show', $post) }}" class="btn btn-outline-secondary btn-sm" target="_blank">View</a>
                    @endif
                    <a href="{{ route('admin.blog-posts.edit', $post) }}" class="btn btn-primary btn-sm">Edit</a>
                    <form method="POST" action="{{ route('admin.blog-posts.destroy', $post) }}" onsubmit="return confirm('Delete this blog post?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center py-5 text-muted">No blog posts have been created yet.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection
