@extends('layouts.frontend-public')

@section('title', $blogPost->title.' | '.$siteName)
@section('meta_description', $blogPost->meta_description ?: $blogPost->excerpt)

@push('styles')
  <style>
    .cs_blog_detail_paragraphs p + p {
      margin-top: 1.25rem;
    }

    .cs_comment_empty_state {
      padding: 1.25rem 1.5rem;
      border: 1px solid rgba(15, 23, 42, 0.08);
      border-radius: 1rem;
      background: #fff;
    }
  </style>
@endpush

@section('content')
    <section>
        <div class="cs_height_130 cs_height_lg_80"></div>
        <div class="container">
            <div class="row cs_gap_y_40">
                <div class="col-xl-4 col-lg-5">
                    @php($searchTerm = '')
                    @php($activeCategorySlug = $blogPost->category?->slug)
                    @php($activeTag = '')
                    @include('frontend.partials.blog-sidebar')
                </div>
                <div class="col-xl-8 col-lg-7">
                    <div class="cs_post_details wow fadeInRight">
                        <h2>{{ $blogPost->title }}</h2>
                        <div class="cs_post_meta_wrapper">
                            <div class="cs_post_meta">
                                <span><i class="fa-solid fa-circle-user"></i></span>
                                <span>{{ $blogPost->author_name }}</span>
                            </div>
                            <div class="cs_post_meta">
                                <span><i class="fa-solid fa-clock"></i></span>
                                <span>{{ optional($blogPost->published_at)->format('d F Y') }}</span>
                            </div>
                            <div class="cs_post_meta">
                                <span><i class="fa-solid fa-comment-dots"></i></span>
                                <span>{{ $comments->count() }} comments</span>
                            </div>
                        </div>
                        <img src="{{ $blogPost->imageUrlFor('featured') ?: asset('frontend-assets/img/post_details_img_1.jpg') }}" alt="{{ $blogPost->title }}">
                        <div class="cs_blog_detail_paragraphs">
                            @foreach (preg_split("/\\r\\n\\r\\n+|\\n\\n+/", trim($blogPost->content)) as $paragraph)
                                @if (filled($paragraph))
                                    <p>{!! nl2br(e($paragraph)) !!}</p>
                                @endif
                            @endforeach
                        </div>
                        @if ($blogPost->quote)
                            <blockquote>{{ $blogPost->quote }}</blockquote>
                        @endif
                    </div>

                    <div class="cs_post_tags">
                        <p class="cs_mb_12">{{ $blogPage->article_tags_title }}</p>
                        <div class="cs_tags_links wow fadeInUp">
                            @forelse ($articleTags as $tag)
                                <a href="{{ route('blog.index', ['tag' => $tag]) }}" class="cs_tag_link cs_radius_4" aria-label="Post tag button">{{ $tag }}</a>
                            @empty
                                <span class="cs_tag_link cs_radius_4">No tags</span>
                            @endforelse
                        </div>
                    </div>

                    <div class="cs_post_comments">
                        <div class="cs_post_comment_header cs_mb_50">
                            <h3 class="cs_fs_24 cs_semibold mb-0">{{ $blogPage->comments_section_title }} ({{ $comments->count() }})</h3>
                        </div>

                        @if ($comments->isEmpty())
                            <div class="cs_comment_empty_state">No comments are published for this article yet.</div>
                        @else
                            <ul class="cs_comment_list cs_heading_font cs_mp_0">
                                @foreach ($comments as $index => $comment)
                                    <li class="cs_comment_body">
                                        <div class="cs_avatar cs_style_1 cs_mb_25">
                                            <div class="cs_avatar_thumbnail cs_radius_50">
                                                <img src="{{ $blogPost->imageUrlFor('comments', $index) ?: asset('frontend-assets/img/avatar_4.jpg') }}" alt="{{ $comment['name'] }}">
                                            </div>
                                            <div class="cs_avatar_info">
                                                <h3 class="cs_fs_18 cs_semibold cs_body_font cs_black_color mb-0">{{ $comment['name'] }}</h3>
                                                <p class="cs_fs_14 mb-0"><i class="fa-solid fa-clock"></i>{{ $comment['date_label'] ?? 'Recently' }}</p>
                                            </div>
                                        </div>
                                        <div class="cs_comment_info">
                                            <p>{{ $comment['body'] }}</p>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="cs_height_130 cs_height_lg_80"></div>
    </section>
@endsection
