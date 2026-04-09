@extends('layouts.frontend-public')

@section('title', 'Blog | '.$siteName)
@section('meta_description', $siteInfo->short_description ?: $siteName)

@push('styles')
  <style>
    .cs_blog_pagination .pagination {
      justify-content: center;
      margin-top: 3rem;
    }

    .cs_blog_pagination .page-link {
      border-radius: 10px;
      margin: 0 0.25rem;
      padding: 0.75rem 1rem;
    }
  </style>
@endpush

@section('content')
    <section class="cs_page_heading cs_style_1">
        <div class="container">
            <div class="cs_page_heading_content_wrapper cs_heading_bg cs_bg_filed" data-src="{{ $blogPage->imageUrlFor('hero') ?: asset('frontend-assets/img/page_header_1.jpg') }}">
                <h1 class="cs_fs_48 cs_semibold cs_mb_7 wow fadeInLeft">{{ $blogPage->hero_title }}</h1>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" aria-label="Back to home button">Home</a></li>
                    <li class="breadcrumb-item active">Blog</li>
                </ol>
            </div>
        </div>
    </section>

    <div class="cs_blog_area cs_type_1 position-relative">
        <div class="cs_height_130 cs_height_lg_80"></div>
        <div class="container">
            <div class="row cs_gap_y_40">
                <div class="col-xl-4 col-lg-5">
                    @include('frontend.partials.blog-sidebar')
                </div>
                <div class="col-xl-8 col-lg-7">
                    @if ($posts->isEmpty())
                        <div class="alert alert-light border py-5 text-center">
                            No blog posts matched your current filters.
                        </div>
                    @else
                        <div class="row cs_row_gap_20 cs_gap_y_20">
                            @foreach ($posts as $post)
                                <div class="col-xl-6 wow fadeInUp" @if ($loop->iteration % 2 === 0) data-wow-delay="150ms" @endif>
                                    <article class="cs_post cs_style_1 cs_type_1 cs_white_bg cs_radius_15">
                                        <a href="{{ route('blog.show', $post) }}" aria-label="Click to read post" class="cs_post_thumbnail">
                                            <img src="{{ $post->imageUrlFor('featured') ?: asset('frontend-assets/img/post_img_3.jpg') }}" alt="{{ $post->title }}">
                                        </a>
                                        <div class="cs_post_content border-0">
                                            <h3 class="cs_post_title cs_fs_28 cs_semibold cs_body_font cs_mb_14">
                                                <a href="{{ route('blog.show', $post) }}" aria-label="Click to read post">{{ $post->title }}</a>
                                            </h3>
                                            <div class="cs_post_meta_wrapper cs_mb_12">
                                                <div class="cs_post_meta">
                                                    <span><i class="fa-solid fa-circle-user"></i></span>
                                                    <span>{{ $post->author_name }}</span>
                                                </div>
                                                <div class="cs_post_meta">
                                                    <span><i class="fa-solid fa-clock"></i></span>
                                                    <span>{{ optional($post->published_at)->format('d F Y') }}</span>
                                                </div>
                                            </div>
                                            <a href="{{ route('blog.show', $post) }}" aria-label="Click to read post" class="cs_post_btn cs_accent_color cs_medium text-decoration-underline">{{ $blogPage->read_button_text }}</a>
                                        </div>
                                    </article>
                                </div>
                            @endforeach
                        </div>

                        <div class="cs_blog_pagination">
                            {{ $posts->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="cs_height_130 cs_height_lg_80"></div>
    </div>
@endsection
