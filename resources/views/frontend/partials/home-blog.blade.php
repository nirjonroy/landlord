<section id="blog" class="cs_blog_area position-relative">
    <div class="cs_height_120 cs_height_lg_80"></div>
    <div class="container">
        <div class="cs_section_heading cs_style_1 text-center">
            <h2 class="cs_fs_48 cs_semibold mb-0 wow zoomIn">{{ $blogPage->home_section_title }}</h2>
        </div>
        <div class="cs_height_80 cs_height_lg_50"></div>

        @if ($homeBlogPosts->isEmpty())
            <div class="alert alert-light border text-center py-5">
                Blog posts will appear here after the first published post is added from the admin panel.
            </div>
        @else
            <div class="row cs_row_gap_20 cs_gap_y_30">
                @foreach ($homeBlogPosts as $post)
                    <div class="col-lg-4 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="{{ number_format($loop->index * 0.3, 1) }}s">
                        <article class="cs_post cs_style_1 cs_white_bg cs_radius_15">
                            <a href="{{ route('blog.show', $post) }}" aria-label="Click To Read More" class="cs_post_thumbnail">
                                <img src="{{ $post->imageUrlFor('featured') ?: asset('frontend-assets/img/post_img_1.jpg') }}" alt="{{ $post->title }}">
                            </a>
                            <div class="cs_post_content">
                                <h3 class="cs_post_title cs_fs_28 cs_semibold cs_body_font cs_mb_13">
                                    <a href="{{ route('blog.show', $post) }}" aria-label="Click to read post">{{ $post->title }}</a>
                                </h3>
                                <div class="cs_post_meta_wrapper cs_mb_12">
                                    <div class="cs_post_meta">
                                        <span><i class="fa-solid fa-circle-user"></i></span>
                                        <span>{{ $post->author_name }}</span>
                                    </div>
                                    <div class="cs_post_meta">
                                        <span><i class="fa-solid fa-clock"></i></span>
                                        <span>{{ optional($post->published_at)->format('d F') }}</span>
                                    </div>
                                </div>
                                <a href="{{ route('blog.show', $post) }}" aria-label="Click to read post" class="cs_post_btn cs_accent_color cs_medium text-decoration-underline">{{ $blogPage->read_button_text }}</a>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    <div class="cs_height_130 cs_height_lg_80"></div>
</section>
