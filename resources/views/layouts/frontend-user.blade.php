<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $siteInfo->short_description ?: $siteName }}">
    <meta name="author" content="Laralink">
    <link rel="icon" href="{{ $siteLogoUrl ?: asset('frontend-assets/img/logo.svg') }}">
    <title>@yield('title', $siteName)</title>
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/slick.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/light-gallery.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/odometer.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/style.css') }}">
    <style>
        .cs_status_alert {
            width: 100%;
            padding: 16px 18px;
            margin-bottom: 24px;
            border-radius: 12px;
            font-size: 15px;
            line-height: 1.6em;
        }

        .cs_status_alert_success {
            color: #0f5132;
            background-color: #d1e7dd;
            border: 1px solid #badbcc;
        }

        .cs_status_alert_error {
            color: #842029;
            background-color: #f8d7da;
            border: 1px solid #f5c2c7;
        }

        .cs_form_hint {
            margin-top: 8px;
            color: #6c757d;
            font-size: 14px;
            line-height: 1.5em;
        }

        .cs_form_error {
            margin-top: 8px;
            color: #dc3545;
            font-size: 14px;
            line-height: 1.5em;
        }

        .cs_profile_avatar {
            width: 130px;
            height: 130px;
            overflow: hidden;
            border-radius: 50%;
            background: #f3f6fb;
        }

        .cs_profile_avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .cs_profile_summary_card {
            padding: 20px;
            border-radius: 12px;
            background-color: #f8fafc;
            margin-bottom: 18px;
        }

        .cs_profile_summary_card h3 {
            margin-bottom: 8px;
        }

        .cs_profile_summary_meta {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .cs_profile_summary_meta li {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            padding: 8px 0;
            border-bottom: 1px solid rgba(15, 23, 42, 0.08);
            font-size: 14px;
        }

        .cs_profile_summary_meta li:last-child {
            border-bottom: 0;
        }

        .cs_file_grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 18px;
        }

        .cs_file_card {
            padding: 18px;
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 12px;
            background: #fff;
            height: 100%;
        }

        .cs_file_preview {
            width: 100%;
            height: 180px;
            border-radius: 12px;
            overflow: hidden;
            background: #f8fafc;
            margin-bottom: 16px;
        }

        .cs_file_preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .cs_file_actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 14px;
        }

        .cs_remove_check {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            margin: 0;
            font-size: 14px;
        }

        .cs_remove_check input {
            width: 18px;
            height: 18px;
            accent-color: var(--accent-color);
        }

        .cs_danger_box {
            padding: 24px;
            border-radius: 12px;
            background: #fff5f5;
            border: 1px solid #fecaca;
        }

        .cs_logout_form {
            display: inline-flex;
        }

        .cs_logout_form button {
            border: 0;
        }

        .cs_tab_links.cs_style_5 li a {
            cursor: pointer;
        }
    </style>
    @yield('head_styles')
</head>

<body>
    <div class="cs_preloader cs_center">
        <div class="cs_preloader_in cs_center cs_radius_50">
            <span class="cs_center cs_white_bg cs_accent_color">
                <svg width="50" height="50" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M24 7.96075V21.317C23.9993 22.0283 23.7181 22.7104 23.2182 23.2134C22.7182 23.7164 22.0404 23.9993 21.3333 24H16.8889C16.4174 24 15.9652 23.8115 15.6318 23.4761C15.2984 23.1407 15.1111 22.6857 15.1111 22.2113V17.2924C15.1111 17.0552 15.0175 16.8277 14.8508 16.66C14.6841 16.4923 14.458 16.398 14.2222 16.398H9.77778C9.54203 16.398 9.31594 16.4923 9.14924 16.66C8.98254 16.8277 8.88889 17.0552 8.88889 17.2924V22.2113C8.88889 22.6857 8.70159 23.1407 8.36819 23.4761C8.03479 23.8115 7.58261 24 7.11111 24H2.66667C1.95964 23.9993 1.28177 23.7164 0.781828 23.2134C0.281884 22.7104 0.000705969 22.0283 0 21.317V7.96075C0.000665148 7.65188 0.0804379 7.34839 0.231621 7.07957C0.382805 6.81075 0.600296 6.58567 0.863111 6.42605L11.0853 0.255041C11.3617 0.0881572 11.6779 0 12.0002 0C12.3225 0 12.6388 0.0881572 12.9151 0.255041L23.1373 6.42605C23.4001 6.58573 23.6175 6.81083 23.7686 7.07965C23.9197 7.34846 23.9994 7.65192 24 7.96075Z" fill="currentColor" />
                </svg>
            </span>
        </div>
    </div>

    <header class="cs_site_header cs_style_1 cs_sticky_header">
        <div class="cs_main_header">
            <div class="container">
                <div class="cs_main_header_in">
                    <div class="cs_main_header_left">
                        <a class="cs_site_branding" href="{{ route('home') }}" aria-label="Click to visit home page">
                            <img src="{{ $siteLogoUrl ?: asset('frontend-assets/img/logo.svg') }}" alt="{{ $siteName }}">
                        </a>
                        <nav class="cs_nav cs_heading_color">
                            <ul class="cs_nav_list">
                                <li><a href="{{ route('home') }}" aria-label="Home">Home</a></li>
                                <li class="menu-item-has-children">
                                    <a href="{{ route('properties.index') }}" aria-label="Listing">Listing</a>
                                    <ul>
                                        <li><a href="{{ route('properties.index') }}" aria-label="All listings">All Listings</a></li>
                                        <li><a href="{{ route('properties.index', ['purpose' => 'rent']) }}" aria-label="Rent listings">Rent Listings</a></li>
                                        <li><a href="{{ route('properties.index', ['purpose' => 'sale']) }}" aria-label="Sale listings">Sale Listings</a></li>
                                    </ul>
                                </li>
                                <li><a href="{{ route('about') }}" aria-label="About">About</a></li>
                                <li><a href="{{ route('contact') }}" aria-label="Contact">Contact</a></li>
                                <li><a href="{{ route('blog.index') }}" aria-label="Blog">Blog</a></li>
                                <li class="cs_mobile_nav_actions">
                                    <div class="cs_mobile_nav_buttons">
                                        <a href="{{ route('profile.edit', ['tab' => 'add_property']) }}#add_property" aria-label="Add property button" class="cs_btn cs_style_1 cs_type_1 cs_accent_color cs_fs_15 cs_medium cs_radius_7">
                                            <span class="cs_btn_icon"><i class="fa-solid fa-circle-plus"></i></span>
                                            <span class="cs_btn_text">Add Property</span>
                                        </a>
                                        <form method="POST" action="{{ route('logout') }}" class="cs_logout_form">
                                            @csrf
                                            <button type="submit" aria-label="Logout button" class="cs_btn cs_style_1 cs_accent_bg cs_fs_15 cs_medium cs_white_color cs_radius_7">
                                                <span class="cs_btn_icon"><i class="fa-solid fa-right-from-bracket"></i></span>
                                                <span class="cs_btn_text">Logout</span>
                                            </button>
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="cs_main_header_right">
                        <a href="{{ route('profile.edit', ['tab' => 'add_property']) }}#add_property" aria-label="Add property button" class="cs_btn cs_style_1 cs_type_1 cs_accent_color cs_fs_15 cs_medium cs_radius_7">
                            <span class="cs_btn_icon"><i class="fa-solid fa-circle-plus"></i></span>
                            <span class="cs_btn_text">Add Property</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="cs_logout_form">
                            @csrf
                            <button type="submit" aria-label="Logout button" class="cs_btn cs_style_1 cs_accent_bg cs_fs_15 cs_medium cs_white_color cs_radius_7">
                                <span class="cs_btn_icon"><i class="fa-solid fa-right-from-bracket"></i></span>
                                <span class="cs_btn_text">Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="cs_height_116 cs_height_lg_110"></div>

    @yield('content')

    <footer class="cs_footer cs_style_1 cs_heading_bg cs_gray_color">
        <div class="container">
            <div class="cs_footer_row">
                <div class="cs_footer_col">
                    <div class="cs_footer_widget">
                        <h2 class="cs_footer_widget_title cs_fs_18 cs_white_color cs_semibold wow fadeInDown">{{ $siteName }} Properties</h2>
                        <ul class="cs_footer_widget_address cs_mp_0">
                            <li><i class="fa-solid fa-location-dot"></i>{{ $siteInfo->address ?: 'Office or company address not set yet.' }}</li>
                            <li><a href="tel:{{ $siteInfo->contact_phone ?: '+8801XXXXXXXXX' }}" aria-label="Phone Link"><i class="fa-solid fa-phone"></i>{{ $siteInfo->contact_phone ?: '+8801XXXXXXXXX' }}</a></li>
                            <li><a href="mailto:{{ $siteInfo->contact_email ?: 'admin@landsite.test' }}" aria-label="Email Link"><i class="fa-solid fa-envelope"></i>{{ $siteInfo->contact_email ?: 'admin@landsite.test' }}</a></li>
                        </ul>
                    </div>
                </div>
                <div class="cs_footer_col">
                    <div class="cs_footer_widget">
                        <h2 class="cs_footer_widget_title cs_fs_18 cs_white_color cs_semibold">Quick Links</h2>
                        <ul class="cs_footer_widget_menu">
                            <li><a href="{{ route('about') }}" aria-label="About Us">About Us</a></li>
                            <li><a href="{{ route('properties.index') }}" aria-label="Listings">Listings</a></li>
                            <li><a href="{{ route('blog.index') }}" aria-label="Blog">Blog</a></li>
                            <li><a href="{{ route('contact') }}" aria-label="Contact">Contact</a></li>
                            <li><a href="{{ route('profile.edit') }}" aria-label="Profile">My Profile</a></li>
                            <li><a href="{{ route('admin.login') }}" aria-label="Admin Login">Admin Login</a></li>
                        </ul>
                    </div>
                </div>
                <div class="cs_footer_col">
                    <div class="cs_footer_widget">
                        <h2 class="cs_footer_widget_title cs_fs_18 cs_white_color cs_semibold">Discover</h2>
                        <ul class="cs_footer_widget_menu">
                            <li>Land Listings</li>
                            <li>Owner Verification</li>
                            <li>Tenant Leads</li>
                            <li>Home Photos</li>
                            <li>Bangladesh Coverage</li>
                        </ul>
                    </div>
                </div>
                <div class="cs_footer_col">
                    <div class="cs_newsletter cs_style_1">
                        <h2 class="cs_newsletter_title cs_fs_24 cs_semibold cs_white_color cs_mb_53 cs_mb_lg_30 wow fadeInDown">Get the latest information about properties from {{ $siteName }}</h2>
                        <form action="#" class="cs_newsletter_form cs_radius_10 wow fadeInUp">
                            <input type="email" name="email" autocomplete="email" class="cs_newsletter_input cs_radius_7" placeholder="Enter your email here">
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
                            <a href="{{ $siteInfo->facebook_url ?: '#' }}" aria-label="Facebook" class="cs_center cs_radius_50 cs_gray_bg">
                                <i class="fa-brands fa-facebook-f"></i>
                            </a>
                            <a href="{{ $siteInfo->youtube_url ?: '#' }}" aria-label="YouTube" class="cs_center cs_radius_50 cs_gray_bg">
                                <i class="fa-brands fa-youtube"></i>
                            </a>
                            <a href="{{ $siteInfo->instagram_url ?: '#' }}" aria-label="Instagram" class="cs_center cs_radius_50 cs_gray_bg">
                                <i class="fa-brands fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                    <ul class="cs_footer_widget_menu">
                        <li><a href="#" aria-label="Privacy Policy">Privacy Policy</a></li>
                        <li><a href="#" aria-label="Terms & Condition">Term & Conditions</a></li>
                        <li><a href="#" aria-label="Cookies Policy">Cookies Policy</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <button type="button" class="cs_scrolltop_btn cs_center cs_radius_50 cs_white_bg cs_accent_color">
        <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 15 15">
            <path fill="currentColor" fill-rule="evenodd" d="M7.146 2.146a.5.5 0 0 1 .708 0l4 4a.5.5 0 0 1-.708.708L8 3.707V12.5a.5.5 0 0 1-1 0V3.707L3.854 6.854a.5.5 0 1 1-.708-.708z" clip-rule="evenodd" />
        </svg>
    </button>

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
