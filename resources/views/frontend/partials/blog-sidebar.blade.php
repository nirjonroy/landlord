<aside class="cs_sidebar cs_style_1">
    <div class="cs_sidebar_widget cs_white_bg cs_radius_8 wow fadeInLeft">
        <form class="cs_sidebar_search_form" method="GET" action="{{ route('blog.index') }}">
            @if (!empty($activeCategorySlug))
                <input type="hidden" name="category" value="{{ $activeCategorySlug }}">
            @endif
            @if (!empty($activeTag))
                <input type="hidden" name="tag" value="{{ $activeTag }}">
            @endif
            <input type="text" name="search" placeholder="Search" value="{{ $searchTerm ?? '' }}">
            <button type="submit" aria-label="Search button" class="cs_search_icon">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
    </div>

    <div class="cs_sidebar_widget cs_white_bg cs_radius_8 wow fadeInLeft" data-wow-delay="150ms">
        <h2 class="cs_fs_24 cs_semibold cs_accent_color cs_body_font cs_mb_7">{{ $blogPage->categories_title }}</h2>
        <ul class="cs_categories cs_mp_0">
            @forelse ($categories as $category)
                <li>
                    <a href="{{ route('blog.index', ['category' => $category->slug]) }}" aria-label="Category button">
                        {{ $category->name }} ({{ $category->published_posts_count }})
                    </a>
                </li>
            @empty
                <li><span>No categories available yet.</span></li>
            @endforelse
        </ul>
    </div>

    <div class="cs_sidebar_widget cs_white_bg cs_radius_8 wow fadeInLeft" data-wow-delay="300ms">
        <h2 class="cs_fs_24 cs_semibold cs_accent_color cs_body_font cs_mb_11">{{ $blogPage->recommendation_title }}</h2>
        <h3 class="cs_fs_16 cs_semibold cs_body_font cs_mb_14">{{ $blogPage->latest_posts_title }}</h3>
        <ul class="cs_latest_posts cs_mp_0">
            @forelse ($latestPosts as $latestPost)
                <li>
                    <div class="cs_post cs_style_3">
                        <a href="{{ route('blog.show', $latestPost) }}" aria-label="Click to read post" class="cs_post_thumbnail cs_radius_5">
                            <img src="{{ $latestPost->imageUrlFor('featured') ?: asset('frontend-assets/img/post_img_7.jpg') }}" alt="{{ $latestPost->title }}">
                        </a>
                        <div class="cs_post_info">
                            <h3 class="cs_post_title cs_fs_16 cs_semibold cs_body_font cs_black_color cs_mb_10">
                                <a href="{{ route('blog.show', $latestPost) }}" aria-label="Click to read post">{{ $latestPost->title }}</a>
                            </h3>
                            <div class="cs_post_meta_wrapper cs_fs_14">
                                <div class="cs_post_meta">
                                    <span><i class="fa-solid fa-circle-user"></i></span>
                                    <span>{{ $latestPost->author_name }}</span>
                                </div>
                                <div class="cs_post_meta">
                                    <span><i class="fa-solid fa-clock"></i></span>
                                    <span>{{ optional($latestPost->published_at)->format('d F') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            @empty
                <li><span>No published posts yet.</span></li>
            @endforelse
        </ul>

        <h2 class="cs_fs_16 cs_semibold cs_body_font cs_black_color cs_mb_15">{{ $blogPage->tags_title }}</h2>
        <div class="cs_tags_links">
            @forelse ($popularTags as $tag)
                <a href="{{ route('blog.index', ['tag' => $tag]) }}" class="cs_tag_link cs_radius_4" aria-label="Post tag button">{{ $tag }}</a>
            @empty
                <span class="cs_tag_link cs_radius_4">No tags yet</span>
            @endforelse
        </div>
    </div>
</aside>
