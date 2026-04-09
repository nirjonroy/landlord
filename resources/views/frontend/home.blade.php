<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $siteInfo->short_description ?: $siteName }}">
    <meta name="author" content="Laralink">
    <!-- Favicon Icon -->
    <link rel="icon" href="{{ $siteLogoUrl ?: asset('frontend-assets/img/logo.svg') }}">
    <!-- Site Title -->
    <title>{{ $siteName }}</title>
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/slick.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/light-gallery.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/odometer.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/style.css') }}">
    <style>
        .cs_header_logout_form {
            display: inline-flex;
        }

        .cs_header_logout_form button {
            border: 0;
        }

        .cs_hero_banner_slider .cs_slider_arrows {
            display: flex;
            justify-content: space-between;
            left: 32px;
            pointer-events: none;
            position: absolute;
            right: 32px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 3;
        }

        .cs_hero_banner_slider .cs_slider_arrows > div {
            background: rgba(255, 255, 255, 0.92);
            pointer-events: auto;
        }

        .cs_hero_banner_slider .cs_slide > div {
            height: 100%;
        }

        .cs_hero_banner_slider .cs_hero_title {
            text-wrap: balance;
        }

        @media (max-width: 991px) {
            .cs_hero_banner_slider .cs_slider_arrows {
                display: none;
            }
        }
    </style>
</head>

<body>
    <!-- Start Preloader -->
    <div class="cs_preloader cs_center">
        <div class="cs_preloader_in cs_center cs_radius_50">
            <span class="cs_center cs_white_bg cs_accent_color">
        <svg width="50" height="50" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M24 7.96075V21.317C23.9993 22.0283 23.7181 22.7104 23.2182 23.2134C22.7182 23.7164 22.0404 23.9993 21.3333 24H16.8889C16.4174 24 15.9652 23.8115 15.6318 23.4761C15.2984 23.1407 15.1111 22.6857 15.1111 22.2113V17.2924C15.1111 17.0552 15.0175 16.8277 14.8508 16.66C14.6841 16.4923 14.458 16.398 14.2222 16.398H9.77778C9.54203 16.398 9.31594 16.4923 9.14924 16.66C8.98254 16.8277 8.88889 17.0552 8.88889 17.2924V22.2113C8.88889 22.6857 8.70159 23.1407 8.36819 23.4761C8.03479 23.8115 7.58261 24 7.11111 24H2.66667C1.95964 23.9993 1.28177 23.7164 0.781828 23.2134C0.281884 22.7104 0.000705969 22.0283 0 21.317V7.96075C0.000665148 7.65188 0.0804379 7.34839 0.231621 7.07957C0.382805 6.81075 0.600296 6.58567 0.863111 6.42605L11.0853 0.255041C11.3617 0.0881572 11.6779 0 12.0002 0C12.3225 0 12.6388 0.0881572 12.9151 0.255041L23.1373 6.42605C23.4001 6.58573 23.6175 6.81083 23.7686 7.07965C23.9197 7.34846 23.9994 7.65192 24 7.96075Z" fill="currentColor"/>
          </svg>
          </span>
        </div>
    </div>
    <!-- End Preloader -->
    <!-- Start Header Section -->
    <header class="cs_site_header cs_style_1 cs_sticky_header">
        <div class="cs_main_header">
            <div class="container">
                <div class="cs_main_header_in">
                    <div class="cs_main_header_left">
                        <a class="cs_site_branding" href="{{ route('home') }}" aria-label="Click to visit home page"><img src="{{ $siteLogoUrl ?: asset('frontend-assets/img/logo.svg') }}" alt="{{ $siteName }}"></a>
                        <nav class="cs_nav cs_heading_color">
                            <ul class="cs_nav_list">
                                <li class="menu-item-has-children">
                                    <a href="{{ route('home') }}" aria-label="Home">Home</a>
                                    <ul>
                                        <li><a href="{{ route('home') }}" aria-label="Click to visit home default page">Home Default</a></li>
                                        <li><a href="{{ route('home') }}" aria-label="Click to visit home V2 page">Home V2</a></li>
                                        <li><a href="{{ route('home') }}" aria-label="Click to visit home V3 page">Home V3</a></li>
                                    </ul>
                                </li>
                                <li class="menu-item-has-children">
                                    <a href="#featured-properties">Listing</a>
                                    <ul>
                                        <li><a href="#featured-properties" aria-label="Click to visit all property page">Property Grid View</a></li>
                                        <li><a href="#featured-properties" aria-label="Click to visit property map view page">Property Map View</a></li>
                                        <li><a href="#featured-properties" aria-label="Click to visit property details page">Property Details</a></li>
                                        <li><a href="#featured-properties" aria-label="Click to visit property details V2 page">Property Details V2</a></li>
                                    </ul>
                                </li>
                                <li><a href="{{ route('about') }}" aria-label="Click to visit about page">About</a></li>
                                <li><a href="{{ route('contact') }}" aria-label="Click to visit contact page">Contact</a></li>
                                <li><a href="{{ route('blog.index') }}" aria-label="Click to visit blog page">Blog</a></li>
                            </ul>
                        </nav>
                    </div>
                    <div class="cs_main_header_right">
                        @if (auth()->check())
                            <a href="{{ route('dashboard') }}" aria-label="Dashboard button" class="cs_btn cs_style_1 cs_type_1 cs_accent_color cs_fs_15 cs_medium cs_radius_7">
              <span class="cs_btn_icon"><i class="fa-solid fa-table-columns"></i></span>
              <span class="cs_btn_text">Dashboard</span>
              </a>
                            <form method="POST" action="{{ route('logout') }}" class="cs_header_logout_form">
                                @csrf
                                <button type="submit" aria-label="Logout button" class="cs_btn cs_style_1 cs_accent_bg cs_fs_15 cs_medium cs_white_color cs_radius_7">
              <span class="cs_btn_icon"><i class="fa-solid fa-right-from-bracket"></i></span>
              <span class="cs_btn_text">Logout</span>
              </button>
                            </form>
                        @elseif (auth('admin')->check())
                            <a href="{{ route('admin.dashboard') }}" aria-label="Admin dashboard button" class="cs_btn cs_style_1 cs_type_1 cs_accent_color cs_fs_15 cs_medium cs_radius_7">
              <span class="cs_btn_icon"><i class="fa-solid fa-table-columns"></i></span>
              <span class="cs_btn_text">Dashboard</span>
              </a>
                            <form method="POST" action="{{ route('admin.logout') }}" class="cs_header_logout_form">
                                @csrf
                                <button type="submit" aria-label="Admin logout button" class="cs_btn cs_style_1 cs_accent_bg cs_fs_15 cs_medium cs_white_color cs_radius_7">
              <span class="cs_btn_icon"><i class="fa-solid fa-right-from-bracket"></i></span>
              <span class="cs_btn_text">Logout</span>
              </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" aria-label="Sign In Button" class="cs_btn cs_style_1 cs_accent_bg cs_fs_15 cs_medium cs_white_color cs_radius_7">
              <span class="cs_btn_icon"><i class="fa-solid fa-circle-user"></i></span>
              <span class="cs_btn_text">Sign In</span>
              </a>
                            <a href="{{ route('register') }}" aria-label="Create account button" class="cs_btn cs_style_1 cs_type_1 cs_accent_color cs_fs_15 cs_medium cs_radius_7">
              <span class="cs_btn_icon"><i class="fa-solid fa-user-plus"></i></span>
              <span class="cs_btn_text">Create Account</span>
              </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="cs_height_116 cs_height_lg_110"></div>
    <!-- End Header Section -->
    <!-- Start Hero Section -->
    @include('frontend.partials.home-hero')
    <!-- End Hero Section -->
    <!-- Start Category Section -->
    <section>
        <div class="cs_height_120 cs_height_lg_80"></div>
        <div class="container">
            <div class="cs_section_heading cs_style_1 text-center">
                <h2 class="cs_section_title cs_fs_48 cs_semibold mb-0 wow fadeInDown">Discover the Property Types</h2>
            </div>
            <div class="cs_height_40 cs_height_lg_30"></div>
            <div class="cs_features_content_wrapper">
                <div class="row">
                    <div class="col-lg-3 col-sm-6 wow zoomIn">
                        <a href="#featured-properties" aria-label="Click to visit all property" class="cs_iconbox cs_style_1 cs_white_bg cs_radius_15 cs_hover_3 text-center">
                <span class="cs_iconbox_icon cs_center cs_mb_31">
                <img src="{{ asset('frontend-assets/img/icons/house_icon.svg') }}" alt="House Icon">
                </span>
                <h3 class="cs_iconbox_title cs_fs_24 cs_normal cs_body_font mb-0">HOUSE</h3>
              </a>
                    </div>
                    <div class="col-lg-3 col-sm-6 wow zoomIn">
                        <a href="#featured-properties" aria-label="Click to visit all property" class="cs_iconbox cs_style_1 cs_white_bg cs_radius_15 cs_hover_3 text-center">
                <span class="cs_iconbox_icon cs_center cs_mb_31">
                <img src="{{ asset('frontend-assets/img/icons/apartment_icon.svg') }}" alt="Appartment Icon">
                </span>
                <h3 class="cs_iconbox_title cs_fs_24 cs_normal cs_body_font mb-0">APARTMENT</h3>
              </a>
                    </div>
                    <div class="col-lg-3 col-sm-6 wow zoomIn">
                        <a href="#featured-properties" aria-label="Click to visit all property" class="cs_iconbox cs_style_1 cs_white_bg cs_radius_15 cs_hover_3 text-center">
                <span class="cs_iconbox_icon cs_center cs_mb_31">
                <img src="{{ asset('frontend-assets/img/icons/villa_icon.svg') }}" alt="Vill Icon">
                </span>
                <h3 class="cs_iconbox_title cs_fs_24 cs_normal cs_body_font mb-0">VILLA</h3>
              </a>
                    </div>
                    <div class="col-lg-3 col-sm-6 wow zoomIn">
                        <a href="#featured-properties" aria-label="Click to visit all property" class="cs_iconbox cs_style_1 cs_white_bg cs_radius_15 cs_hover_3 text-center">
                <span class="cs_iconbox_icon cs_center cs_mb_31">
                <img src="{{ asset('frontend-assets/img/icons/bunglo_icon.svg') }}" alt="Bunglow Icon">
                </span>
                <h3 class="cs_iconbox_title cs_fs_24 cs_normal cs_body_font mb-0">BUNGALOW</h3>
              </a>
                    </div>
                    <div class="col-lg-3 col-sm-6 wow zoomIn">
                        <a href="#featured-properties" aria-label="Click to visit all property" class="cs_iconbox cs_style_1 cs_white_bg cs_radius_15 cs_hover_3 text-center">
                            <div class="cs_iconbox_icon cs_center cs_accent_color cs_mb_31">
                                <img src="{{ asset('frontend-assets/img/icons/townhouse_icon.svg') }}" alt="Townhouse Icon">
                            </div>
                            <h3 class="cs_iconbox_title cs_fs_24 cs_normal cs_body_font mb-0">TOWNHOUSE</h3>
                        </a>
                    </div>
                    <div class="col-lg-3 col-sm-6 wow zoomIn">
                        <a href="#featured-properties" aria-label="Click to visit all property" class="cs_iconbox cs_style_1 cs_white_bg cs_radius_15 cs_hover_3 text-center">
                <span class="cs_iconbox_icon cs_center cs_mb_31">
                <img src="{{ asset('frontend-assets/img/icons/loft_icon.svg') }}" alt="Loft Icon">
                </span>
                <h3 class="cs_iconbox_title cs_fs_24 cs_normal cs_body_font mb-0">LOFT</h3>
              </a>
                    </div>
                    <div class="col-lg-3 col-sm-6 wow zoomIn">
                        <a href="#featured-properties" aria-label="Click to visit all property" class="cs_iconbox cs_style_1 cs_white_bg cs_radius_15 cs_hover_3 text-center">
                <span class="cs_iconbox_icon cs_center cs_mb_31">
                <img src="{{ asset('frontend-assets/img/icons/office_icon.svg') }}" alt="Office Icon">
                </span>
                <h3 class="cs_iconbox_title cs_fs_24 cs_normal cs_body_font mb-0">OFFICE</h3>
              </a>
                    </div>
                    <div class="col-lg-3 col-sm-6 wow zoomIn">
                        <a href="#" aria-label="More Category Link" class="cs_iconbox cs_style_1 cs_white_bg cs_radius_15 cs_hover_3 text-center">
                <span class="cs_iconbox_icon cs_center cs_mb_31">
                <img src="{{ asset('frontend-assets/img/icons/more_icon.svg') }}" alt="Plus Icon">
                </span>
                <h3 class="cs_iconbox_title cs_fs_24 cs_normal cs_body_font mb-0">MORE</h3>
              </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="cs_height_80 cs_height_lg_50"></div>
    </section>
    <!-- End Category Section -->
    @include('frontend.partials.featured-properties-slider')
    <!-- Start Popular Properties Section -->
    @include('frontend.partials.featured-properties-tabs')
@include('frontend.partials.popular-cities')
    <!-- Start About Section -->
    <section id="about" class="cs_about cs_style_1">
        <div class="cs_height_103 cs_height_lg_80"></div>
        <div class="container">
            <div class="row cs_gap_y_40 align-items-center">
                <div class="col-lg-6">
                    <div class="cs_about_thumbnail_wrapper position-relative">
                        <div class="cs_about_shape_1 cs_accent_color position-absolute">
                            <svg width="88" height="127" viewBox="0 0 88 127" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <circle cx="3" cy="3" r="3" transform="matrix(1 0 0 -1 0 126.863)" fill="currentColor"/>
                  <circle cx="3" cy="3" r="3" transform="matrix(1 0 0 -1 54.5833 126.863)" fill="currentColor"/>
                  <circle cx="3" cy="3" r="3" transform="matrix(1 0 0 -1 27.2917 126.863)" fill="currentColor"/>
                  <circle cx="3" cy="3" r="3" transform="matrix(1 0 0 -1 81.875 126.863)" fill="currentColor"/>
                  <circle cx="3" cy="3" r="3" transform="matrix(1 0 0 -1 0 102.691)" fill="currentColor"/>
                  <circle cx="3" cy="3" r="3" transform="matrix(1 0 0 -1 54.5833 102.691)" fill="currentColor"/>
                  <circle cx="3" cy="3" r="3" transform="matrix(1 0 0 -1 27.2917 102.691)" fill="currentColor"/>
                  <circle cx="3" cy="3" r="3" transform="matrix(1 0 0 -1 81.875 102.691)" fill="currentColor"/>
                  <circle cx="3" cy="3" r="3" transform="matrix(1 0 0 -1 0 78.5186)" fill="currentColor"/>
                  <circle cx="3" cy="3" r="3" transform="matrix(1 0 0 -1 54.5833 78.5186)" fill="currentColor"/>
                  <circle cx="3" cy="3" r="3" transform="matrix(1 0 0 -1 27.2917 78.5186)" fill="currentColor"/>
                  <circle cx="3" cy="3" r="3" transform="matrix(1 0 0 -1 81.875 78.5186)" fill="currentColor"/>
                  <circle cx="3" cy="3" r="3" transform="matrix(1 0 0 -1 0 54.3452)" fill="currentColor"/>
                  <circle cx="3" cy="3" r="3" transform="matrix(1 0 0 -1 54.5833 54.3452)" fill="currentColor"/>
                  <circle cx="3" cy="3" r="3" transform="matrix(1 0 0 -1 27.2917 54.3452)" fill="currentColor"/>
                  <circle cx="3" cy="3" r="3" transform="matrix(1 0 0 -1 81.875 54.3452)" fill="currentColor"/>
                  <circle cx="3" cy="3" r="3" transform="matrix(1 0 0 -1 0 30.1729)" fill="currentColor"/>
                  <circle cx="3" cy="3" r="3" transform="matrix(1 0 0 -1 54.5833 30.1729)" fill="currentColor"/>
                  <circle cx="3" cy="3" r="3" transform="matrix(1 0 0 -1 27.2917 30.1729)" fill="currentColor"/>
                  <circle cx="3" cy="3" r="3" transform="matrix(1 0 0 -1 81.875 30.1729)" fill="currentColor"/>
                  <circle cx="3" cy="3" r="3" transform="matrix(1 0 0 -1 0 6.00024)" fill="currentColor"/>
                  <circle cx="3" cy="3" r="3" transform="matrix(1 0 0 -1 54.5833 6.00049)" fill="currentColor"/>
                  <circle cx="3" cy="3" r="3" transform="matrix(1 0 0 -1 27.2917 6.00049)" fill="currentColor"/>
                  <circle cx="3" cy="3" r="3" transform="matrix(1 0 0 -1 81.875 6.00049)" fill="currentColor"/>
                </svg>
                        </div>
                        <div class="cs_about_shape_2 position-absolute"></div>
                        <div class="cs_about_thumbnail">
                            <img src="{{ asset('frontend-assets/img/about_img_1.png') }}" alt="About Image">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="cs_about_text">
                        <h2 class="cs_fs_48 cs_semibold cs_mb_46 cs_mb_lg_30  wow fadeInDown">Welcome to {{ $siteName }}</h2>
                        <p class="cs_fs_24 cs_mb_57 cs_mb_lg_36">We are a Bangladesh-focused real estate platform that helps users discover flats, land, and homes across major cities with trusted local support.</p>
                        <a href="{{ route('about') }}" area-label="Click to visit About Page" class="cs_btn cs_style_1 cs_accent_bg cs_medium cs_white_color cs_radius_7 wow fadeInUp">
              <span class="cs_btn_text">About More</span>
              </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="cs_height_130 cs_height_lg_80"></div>
    </section>
    <!-- End About Section -->
    <!-- Start Counter Section -->
    <div class="container">
        <div class="cs_counter_wrapper cs_light_gray_bg cs_radius_15 wow fadeInUp">
            <div class="cs_funfact cs_style_1 cs_center_column">
                <div class="cs_fs_48 cs_semibold cs_accent_color">
                    <span class="odometer" data-count-to="5635"></span>
                </div>
                <p class="mb-0">HOUSES FOR SALE</p>
            </div>
            <div class="cs_funfact cs_style_1 cs_center_column">
                <div class="cs_fs_48 cs_semibold cs_accent_color">
                    <span class="odometer" data-count-to="324"></span>
                </div>
                <p class="mb-0">OPEN HOUSES</p>
            </div>
            <div class="cs_funfact cs_style_1 cs_center_column">
                <div class="cs_fs_48 cs_semibold cs_accent_color">
                    <span class="odometer" data-count-to="105"></span>
                </div>
                <p class="mb-0">HOUSES RECENTLY SOLD</p>
            </div>
            <div class="cs_funfact cs_style_1 cs_center_column">
                <div class="cs_fs_48 cs_semibold cs_accent_color">
                    <span class="odometer" data-count-to="301"></span>
                </div>
                <p class="mb-0">PRICE REDUCED</p>
            </div>
        </div>
    </div>
    <div class="cs_height_130 cs_height_lg_80"></div>
    <!-- End Counter Section -->
    <!-- Start Testimonial Section -->
    <section>
        <div class="container">
            <div class="cs_review_content_wrapper cs_slider cs_style_1 cs_slider_gap_20 cs_overflow_visible cs_ptb_12 cs_testimonial_slider position-relative">
                <div class="cs_review_shape_1 cs_accent_bg cs_radius_15 position-absolute"></div>
                <div class="cs_review_shape_2 position-absolute">
                    <img src="{{ asset('frontend-assets/img/dots_shape.svg') }}" alt="Dots Shape">
                </div>
                <div class="cs_review_shape_3 position-absolute">
                    <img src="{{ asset('frontend-assets/img/icons/quote_1.svg') }}" alt="Quote Shape">
                </div>
                <div class="row align-items-center">
                    <div class="col-xxl-3 col-lg-4">
                        <div class="cs_section_heading cs_style_1 cs_mb_31">
                            <h2 class="cs_fs_48 cs_semibold mb-0 wow fadeInLeft">Satisfied {{ $siteName }}'s Clients</h2>
                        </div>
                    </div>
                    <div class="col-xxl-8 col-lg-8 offset-xxl-1">
                        <div class="cs_full_screen_right">
                            <div class="cs_slider_container" data-autoplay="0" data-loop="1" data-speed="800" data-center="0" data-variable-width="0" data-slides-per-view="responsive" data-xs-slides="1" data-sm-slides="2" data-md-slides="3" data-lg-slides="4" data-add-slides="4">
                                <div class="cs_slider_wrapper">
                                    <div class="cs_slide">
                                        <div class="cs_card cs_style_3 cs_white_bg cs_radius_15">
                                            <div class="cs_avatar cs_style_1 cs_mb_40 cs_mb_lg_24">
                                                <div class="cs_avatar_thumbnail cs_center cs_accent_bg cs_fs_20 cs_white_color cs_radius_50">
                                                    <img src="{{ asset('frontend-assets/img/avatar_1.jpg') }}" alt="Avatar">
                                                </div>
                                                <div class="cs_avatar_info">
                                                    <h3 class="cs_fs_18 cs_semibold cs_body_font">Rowan Jacobson</h3>
                                                    <p class="cs_fs_14 mb-0">Dhaka, Bangladesh</p>
                                                </div>
                                            </div>
                                            <blockquote>I had quite a hard time selling my house in the city even though my family and I had to immediately pay off the money to buy a house in the countryside. Luckily, {{ $siteName }} agents helped sell our house at a decent
                                                price and fast.</blockquote>
                                            <div class="cs_rating cs_accent_color" data-rating="5">
                                                <div class="cs_rating_percentage"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cs_slide">
                                        <div class="cs_card cs_style_3 cs_white_bg cs_radius_15">
                                            <div class="cs_avatar cs_style_1 cs_mb_40 cs_mb_lg_24">
                                                <div class="cs_avatar_thumbnail cs_center cs_accent_bg cs_fs_20 cs_white_color cs_radius_50">
                                                    <img src="{{ asset('frontend-assets/img/avatar_2.jpg') }}" alt="Avatar">
                                                </div>
                                                <div class="cs_avatar_info">
                                                    <h3 class="cs_fs_18 cs_semibold cs_body_font">Liliana Christ</h3>
                                                    <p class="cs_fs_14 mb-0">Chattogram, Bangladesh</p>
                                                </div>
                                            </div>
                                            <blockquote>I thought it would be hard to find a reliable flat in Chattogram, but {{ $siteName }} made the full process smooth, quick, and practical.</blockquote>
                                            <div class="cs_rating cs_accent_color" data-rating="5">
                                                <div class="cs_rating_percentage"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cs_slide">
                                        <div class="cs_card cs_style_3 cs_white_bg cs_radius_15">
                                            <div class="cs_avatar cs_style_1 cs_mb_40 cs_mb_lg_24">
                                                <div class="cs_avatar_thumbnail cs_center cs_accent_bg cs_fs_20 cs_white_color cs_radius_50">
                                                    <img src="{{ asset('frontend-assets/img/avatar_3.jpg') }}" alt="Avatar">
                                                </div>
                                                <div class="cs_avatar_info">
                                                    <h3 class="cs_fs_18 cs_semibold cs_body_font">Dani Crowford</h3>
                                                    <p class="cs_fs_14 mb-0">Sylhet, Bangladesh</p>
                                                </div>
                                            </div>
                                            <blockquote>When I moved to Sylhet for study, my family worried about finding a safe place to stay. Thanks to {{ $siteName }}, they do not have to worry anymore.</blockquote>
                                            <div class="cs_rating cs_accent_color" data-rating="5">
                                                <div class="cs_rating_percentage"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cs_slide">
                                        <div class="cs_card cs_style_3 cs_white_bg cs_radius_15">
                                            <div class="cs_avatar cs_style_1 cs_mb_40 cs_mb_lg_24">
                                                <div class="cs_avatar_thumbnail cs_center cs_accent_bg cs_fs_20 cs_white_color cs_radius_50">MP</div>
                                                <div class="cs_avatar_info">
                                                    <h3 class="cs_fs_18 cs_semibold cs_body_font">Micky Peso</h3>
                                                    <p class="cs_fs_14 mb-0">Rajshahi, Bangladesh</p>
                                                </div>
                                            </div>
                                            <blockquote>I had quite a hard time selling my house in the city even though my family and I had to immediately pay off the money to buy a house in the countryside.</blockquote>
                                            <div class="cs_rating cs_accent_color" data-rating="4.5">
                                                <div class="cs_rating_percentage"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cs_slide">
                                        <div class="cs_card cs_style_3 cs_white_bg cs_radius_15">
                                            <div class="cs_avatar cs_style_1 cs_mb_40 cs_mb_lg_24">
                                                <div class="cs_avatar_thumbnail cs_center cs_accent_bg cs_fs_20 cs_white_color cs_radius_50">KJ</div>
                                                <div class="cs_avatar_info">
                                                    <h3 class="cs_fs_18 cs_semibold cs_body_font">Kyle Jacson</h3>
                                                    <p class="cs_fs_14 mb-0">Khulna, Bangladesh</p>
                                                </div>
                                            </div>
                                            <blockquote>I thought it would be hard to find a suitable home in Khulna, but {{ $siteName }} made the process clear, local, and convenient.</blockquote>
                                            <div class="cs_rating cs_accent_color" data-rating="5">
                                                <div class="cs_rating_percentage"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cs_slider_arrows cs_style_1">
                    <div class="cs_left_arrow rounded-circle cs_center cs_accent_color wow fadeInLeft">
                        <i class="fa-solid fa-arrow-left"></i>
                    </div>
                    <div class="cs_right_arrow rounded-circle cs_center cs_accent_color wow fadeInRight">
                        <i class="fa-solid fa-arrow-right"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="cs_height_130 cs_height_lg_80"></div>
    </section>
    <!-- End Testimonial Section -->
    <!-- Start CTA Section -->
    <section class="cs_cta cs_style_1 position-relative">
        <div class="container">
            <div class="cs_cta_content position-relative">
                <div class="cs_cta_banner">
                    <img src="{{ asset('frontend-assets/img/cta_bg_1.jpg') }}" alt="CTA Banner">
                </div>
                <div class="cs_cta_text_wrapper">
                    <div class="cs_cta_text cs_accent_bg cs_radius_15 cs_center_column text-center position-relative">
                        <h2 class="cs_fs_48 cs_semibold cs_white_color cs_mb_50 cs_mb_lg_30 wow fadeInDown">Let's Find Your Dream House <br> with {{ $siteName }}!</h2>
                        <a href="#featured-properties" aria-label="Click to visit all property" class="cs_btn cs_style_1 cs_white_bg cs_accent_color cs_medium cs_radius_7 wow fadeInUp">
              <span class="cs_btn_text">View More</span>
              </a>
                        <div class="cs_cta_shape_1 position-absolute">
                            <img src="{{ asset('frontend-assets/img/dots_shape.svg') }}" alt="Dots Shape">
                        </div>
                        <div class="cs_cta_shape_2 position-absolute">
                            <img src="{{ asset('frontend-assets/img/cta_shape_1.svg') }}" alt="Vector Shape">
                        </div>
                        <div class="cs_cta_shape_3 position-absolute">
                            <img src="{{ asset('frontend-assets/img/dots_shape.svg') }}" alt="Dots Shape">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="cs_height_120 cs_height_lg_80"></div> -->
    </section>
    <!-- End CTA Section -->
    @include('frontend.partials.home-blog')
    <!-- Start FAQ Section -->
    <section>
        <div class="cs_height_120 cs_height_lg_80"></div>
        <div class="container">
            <div class="cs_section_heading cs_style_1 text-center">
                <h2 class="cs_fs_48 cs_semibold mb-0 wow fadeInDown">Frequently Asked Questions</h2>
            </div>
            <div class="cs_height_80 cs_height_lg_50"></div>
            <div class="row">
                <div class="col-xl-8 offset-xl-2">
                    <div class="cs_accordians_wrapper cs_style_1 cs_radius_15">
                        <div class="cs_accordian cs_style_1 cs_white_bg">
                            <div class="cs_accordian_overlay cs_accent_bg position-absolute"></div>
                            <div class="cs_accordian_head">
                                <h3 class="cs_accordian_title cs_fs_24 cs_medium cs_body_font mb-0">How to deal with {{ $siteName }} agents?</h3>
                                <span class="cs_accordian_toggle cs_center cs_accent_color cs_radius_50 position-absolute"><i class="fa-solid fa-arrow-down"></i></span>
                            </div>
                            <div class="cs_accordian_body">
                                <p>It depends on the context of the situation. If there is a bidding process for a product or service and a price has been listed, then it may be possible to bid on that price. However, if the price listed is a fixed price
                                    for a product or service without a bidding process, then it may not be possible to bid on that price.</p>
                            </div>
                        </div>
                        <div class="cs_accordian cs_style_1 cs_white_bg active">
                            <div class="cs_accordian_overlay cs_accent_bg position-absolute"></div>
                            <div class="cs_accordian_head">
                                <h3 class="cs_accordian_title cs_fs_24 cs_medium cs_body_font mb-0">Is it possible to bid on the price listed?</h3>
                                <span class="cs_accordian_toggle cs_center cs_accent_color cs_radius_50 position-absolute"><i class="fa-solid fa-arrow-down"></i></span>
                            </div>
                            <div class="cs_accordian_body">
                                <p>It depends on the context of the situation. If there is a bidding process for a product or service and a price has been listed, then it may be possible to bid on that price. However, if the price listed is a fixed price
                                    for a product or service without a bidding process, then it may not be possible to bid on that price.</p>
                            </div>
                        </div>
                        <div class="cs_accordian cs_style_1 cs_white_bg">
                            <div class="cs_accordian_overlay cs_accent_bg position-absolute"></div>
                            <div class="cs_accordian_head">
                                <h3 class="cs_accordian_title cs_fs_24 cs_medium cs_body_font mb-0">What if I want to sell my property in {{ $siteName }}?</h3>
                                <span class="cs_accordian_toggle cs_center cs_accent_color cs_radius_50 position-absolute"><i class="fa-solid fa-arrow-down"></i></span>
                            </div>
                            <div class="cs_accordian_body">
                                <p>It depends on the context of the situation. If there is a bidding process for a product or service and a price has been listed, then it may be possible to bid on that price. However, if the price listed is a fixed price
                                    for a product or service without a bidding process, then it may not be possible to bid on that price.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="cs_height_130 cs_height_lg_80"></div>
    </section>
    <!-- End FAQ Section -->
    <!-- Start Footer Section -->
    <footer id="contact" class="cs_footer cs_style_1 cs_heading_bg cs_gray_color">
        <div class="container">
            <div class="cs_footer_row">
                <div class="cs_footer_col">
                    <div class="cs_footer_widget">
                        <div class="cs_footer_widget">
                            <h2 class="cs_footer_widget_title cs_fs_18 cs_white_color cs_semibold wow fadeInDown">{{ $siteName }} Properties</h2>
                            <ul class="cs_footer_widget_address cs_mp_0">
                                <li><i class="fa-solid fa-location-dot"></i>{{ $siteInfo->address ?: 'Office or company address not set yet.' }}</li>
                                <li><a href="tel:{{ $siteInfo->contact_phone ?: '+8801XXXXXXXXX' }}" aria-label="Phone Link"><i class="fa-solid fa-phone"></i>{{ $siteInfo->contact_phone ?: '+8801XXXXXXXXX' }}</a></li>
                                <li><a href="mailto:{{ $siteInfo->contact_email ?: 'admin@landsite.test' }}" aria-label="Email Link"><i class="fa-solid fa-envelope"></i>{{ $siteInfo->contact_email ?: 'admin@landsite.test' }}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="cs_footer_col">
                    <div class="cs_footer_widget">
                        <h2 class="cs_footer_widget_title cs_fs_18 cs_white_color cs_semibold">Quick Links</h2>
                        <ul class="cs_footer_widget_menu">
                            <li><a href="{{ route('about') }}" aria-label="Page Link">About Us</a></li>
                            <li><a href="#" aria-label="Page Link">Terms & Conditions</a></li>
                            <li><a href="#" aria-label="Page Link">Guide</a></li>
                            <li><a href="#" aria-label="Page Link">Support Center</a></li>
                            <li><a href="{{ route('blog.index') }}" aria-label="Page Link">Blog</a></li>
                            <li><a href="{{ route('contact') }}" aria-label="Page Link">Contact</a></li>
                            <li><a href="#" aria-label="Page Link">Privacy Policy</a></li>
                        </ul>
                    </div>
                </div>
                <div class="cs_footer_col">
                    <div class="cs_footer_widget">
                        <h2 class="cs_footer_widget_title cs_fs_18 cs_white_color cs_semibold">Discover</h2>
                        <ul class="cs_footer_widget_menu">
                            <li>Dhaka</li>
                            <li>Chattogram</li>
                            <li>Sylhet</li>
                            <li>Rajshahi</li>
                            <li>Khulna</li>
                        </ul>
                    </div>
                </div>
                <div class="cs_footer_col">
                    <div class="cs_newsletter cs_style_1">
                        <h2 class="cs_newsletter_title cs_fs_24 cs_semibold cs_white_color cs_mb_53 cs_mb_lg_30 wow fadeInDown">Get the latest information about properties from {{ $siteName }}</h2>
                        <form action="#" class="cs_newsletter_form cs_radius_10 wow fadeInUp">
                            <input type="email" name="email" autocomplete="given-name" class="cs_newsletter_input cs_radius_7" placeholder="Enter your email here">
                            <button type="submit" aria-label="Subscribe Button" class="cs_btn cs_style_1 cs_accent_bg cs_white_color cs_medium cs_radius_7">
                <span class="cs_btn_text">Subscribe</span>
                </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="cs_footer_bottom">
            <div class="container">
                <div class="cs_footer_bottom_in">
                    <div class="cs_socials_wrapper">
                        <h3 class="cs_fs_16 cs_white_color cs_normal mb-0">Follow Us</h3>
                        <div class="cs_social_btns cs_style_1">
                            <a href="{{ $siteInfo->facebook_url ?: '#' }}" aria-label="Social button" class="cs_center cs_radius_50 cs_gray_bg">
                <i class="fa-brands fa-facebook-f"></i>
                </a>
                            <a href="{{ $siteInfo->youtube_url ?: '#' }}" aria-label="Social button" class="cs_center cs_radius_50 cs_gray_bg">
                <i class="fa-brands fa-youtube"></i>
                </a>
                            <a href="#" aria-label="Social button" class="cs_center cs_radius_50 cs_gray_bg">
                <i class="fa-brands fa-linkedin-in"></i>
                </a>
                            <a href="#" aria-label="Social button" class="cs_center cs_radius_50 cs_gray_bg">
                <i class="fa-brands fa-twitter"></i>
                </a>
                            <a href="{{ $siteInfo->instagram_url ?: '#' }}" aria-label="Social button" class="cs_center cs_radius_50 cs_gray_bg">
                <i class="fa-brands fa-instagram"></i>
                </a>
                        </div>
                    </div>
                    <ul class="cs_footer_widget_menu">
                        <li><a href="#" aria-label="Privacy & Policy">Privacy Policy</a></li>
                        <li><a href="#" aria-label="Terms & Condition">Term & Conditions</a></li>
                        <li><a href="#" aria-label="Cookies & Policy">Cookies Policy</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <!-- End Footer Section -->
    <!-- Start Scroll To Top Button -->
    <button type="button" class="cs_scrolltop_btn cs_center cs_radius_50 cs_white_bg cs_accent_color">
      <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 15 15"><path fill="currentColor" fill-rule="evenodd" d="M7.146 2.146a.5.5 0 0 1 .708 0l4 4a.5.5 0 0 1-.708.708L8 3.707V12.5a.5.5 0 0 1-1 0V3.707L3.854 6.854a.5.5 0 1 1-.708-.708z" clip-rule="evenodd"/>
      </svg>
     </button>
    <!-- End Scroll To Top Button -->
    <!-- Script -->
    <script src="{{ asset('frontend-assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('frontend-assets/js/jquery.slick.min.js') }}"></script>
    <script src="{{ asset('frontend-assets/js/light-gallery.min.js') }}"></script>
    <script src="{{ asset('frontend-assets/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('frontend-assets/js/odometer.js') }}"></script>
    <script src="{{ asset('frontend-assets/js/lenis.min.js') }}"></script>
    <script src="{{ asset('frontend-assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('frontend-assets/js/main.js') }}"></script>
    </body>

</html>


