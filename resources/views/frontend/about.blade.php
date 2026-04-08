@extends('layouts.frontend-public')

@section('title', 'About Us | '.$siteName)
@section('meta_description', $aboutPage->mission_section_intro ?: ($siteInfo->short_description ?: $siteName))

@section('content')
    <section class="cs_page_heading cs_style_1">
        <div class="container">
            <div class="cs_page_heading_content_wrapper cs_type_2 cs_heading_bg cs_bg_filed cs_radius_20" data-src="{{ $aboutPage->imageUrlFor('hero') ?: asset('frontend-assets/img/page_header_3.jpg') }}">
                <h1 class="cs_fs_48 cs_semibold cs_mb_7 wow fadeInLeft">{{ $aboutPage->hero_title }}</h1>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" aria-label="Back to home button">Home</a></li>
                    <li class="breadcrumb-item active">About</li>
                </ol>
            </div>
        </div>
    </section>

    <section>
        <div class="cs_height_120 cs_height_lg_80"></div>
        <div class="container">
            <div class="cs_section_heading cs_style_1 text-center">
                <h2 class="cs_fs_48 cs_semibold cs_mb_3 wow fadeInDown">{{ $aboutPage->mission_section_title }}</h2>
                @if ($aboutPage->mission_section_intro)
                    <p class="mb-0">{{ $aboutPage->mission_section_intro }}</p>
                @endif
            </div>
            <div class="cs_height_80 cs_height_lg_50"></div>
            <div class="row cs_gap_y_40 align-items-center">
                <div class="col-lg-6 wow fadeInLeft">
                    <div class="cs_mission_thumbnail position-relative">
                        <img src="{{ $aboutPage->imageUrlFor('mission') ?: asset('frontend-assets/img/about_img_4.png') }}" alt="{{ $aboutPage->mission_heading }}">
                        <div class="cs_mission_shape_1 cs_accent_color position-absolute">
                            <img src="{{ asset('frontend-assets/img/dots_shape.svg') }}" alt="Dots Shape">
                        </div>
                        <div class="cs_mission_shape_2 cs_accent_color position-absolute">
                            <img src="{{ asset('frontend-assets/img/dots_shape.svg') }}" alt="Dots Shape">
                        </div>
                        <div class="cs_mission_shape_3 cs_accent_bg cs_radius_15 position-absolute"></div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="cs_mission_text">
                        <h3 class="cs_fs_28 cs_semibold cs_body_font cs_mb_30 wow fadeInDown">{{ $aboutPage->mission_heading }}</h3>
                        <div class="mb-0">{!! nl2br(e($aboutPage->mission_body)) !!}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="cs_height_130 cs_height_lg_80"></div>
    </section>

    <div class="container">
        <div class="cs_counter_wrapper cs_light_gray_bg cs_radius_15 wow fadeInUp">
            @foreach ($stats as $stat)
                <div class="cs_funfact cs_style_1 cs_center_column">
                    <div class="cs_fs_48 cs_semibold cs_accent_color">
                        <span class="odometer" data-count-to="{{ (int) ($stat['value'] ?? 0) }}"></span>
                    </div>
                    <p class="mb-0">{{ $stat['label'] ?? '' }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <section>
        <div class="cs_height_120 cs_height_lg_80"></div>
        <div class="container">
            <div class="cs_section_heading cs_style_1 text-center">
                <h2 class="cs_fs_48 cs_semibold cs_mb_3 wow fadeInDown">{{ $aboutPage->vision_section_title }}</h2>
                @if ($aboutPage->vision_section_intro)
                    <p class="mb-0">{{ $aboutPage->vision_section_intro }}</p>
                @endif
            </div>
            <div class="cs_height_80 cs_height_lg_50"></div>
            <div class="row cs_gap_y_40 align-items-center">
                <div class="col-lg-6">
                    <div class="cs_vission_text">
                        <h3 class="cs_fs_28 cs_semibold cs_body_font cs_mb_30 wow fadeInUp">{{ $aboutPage->vision_heading }}</h3>
                        <div class="mb-0">{!! nl2br(e($aboutPage->vision_body)) !!}</div>
                    </div>
                </div>
                <div class="col-lg-6 wow fadeInRight">
                    <div class="cs_vission_thumbnail cs_radius_15 position-relative">
                        <img src="{{ $aboutPage->imageUrlFor('vision') ?: asset('frontend-assets/img/about_img_5.jpg') }}" alt="{{ $aboutPage->vision_heading }}">
                        <div class="cs_vission_shape_1 cs_accent_color position-absolute">
                            <img src="{{ asset('frontend-assets/img/dots_shape.svg') }}" alt="Dots Shape">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="cs_height_130 cs_height_lg_80"></div>
    </section>

    <section class="cs_team_area position-relative">
        <div class="container">
            <div class="cs_section_heading cs_style_1 text-center">
                <h2 class="cs_fs_48 cs_semibold mb-0 wow zoomIn">{{ $aboutPage->team_section_title }}</h2>
            </div>
            <div class="cs_height_80 cs_height_lg_50"></div>
            <div class="cs_slider cs_style_1 cs_slider_gap_20 cs_ptb_12">
                <div class="cs_full_screen_right cs_variable_width_wrap cs_remove_overflow">
                    <div class="cs_slider_container" data-autoplay="0" data-loop="1" data-speed="800" data-center="0" data-variable-width="1" data-slides-per-view="responsive" data-xs-slides="2" data-sm-slides="3" data-md-slides="3" data-lg-slides="4" data-add-slides="5">
                        <div class="cs_slider_wrapper">
                            @foreach ($teamMembers as $index => $member)
                                <div class="cs_slide">
                                    <div class="cs_card cs_style_10 cs_white_bg cs_radius_15 text-center">
                                        <div class="cs_card_thumbnail">
                                            <img src="{{ $aboutPage->imageUrlFor('team', $index) ?: asset('frontend-assets/img/team_1.jpg') }}" alt="{{ $member['name'] ?? 'Team member' }} image">
                                        </div>
                                        <div class="cs_card_info">
                                            <h3 class="cs_fs_28 cs_semibold cs_body_font cs_mb_4">{{ $member['name'] ?? '' }}</h3>
                                            <p class="mb-0">{{ $member['role'] ?? '' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="cs_height_80 cs_height_lg_50"></div>
                <div class="d-flex align-items-center justify-content-between gap-4">
                    <div class="cs_slider_arrows cs_style_1 cs_type_1">
                        <div class="cs_left_arrow rounded-circle cs_center cs_accent_color wow fadeInLeft">
                            <i class="fa-solid fa-arrow-left"></i>
                        </div>
                        <div class="cs_right_arrow rounded-circle cs_center cs_accent_color wow fadeInRight">
                            <i class="fa-solid fa-arrow-right"></i>
                        </div>
                    </div>
                    <div class="cs_pagination cs_style_1 wow fadeInUp"></div>
                </div>
            </div>
        </div>
        <div class="cs_team_shape_1 position-absolute">
            <img src="{{ asset('frontend-assets/img/team_bg_shape_1.png') }}" alt="Wave Shape">
        </div>
        <div class="cs_height_130 cs_height_lg_80"></div>
    </section>

    <section>
        <div class="container">
            <div class="cs_section_heading cs_style_1 text-center">
                <h2 class="cs_fs_48 cs_semibold mb-0 wow fadeInDown">{{ $aboutPage->services_section_title }}</h2>
            </div>
        </div>
        <div class="cs_height_80 cs_height_lg_50"></div>
        <div class="cs_slider cs_style_1 cs_slider_gap_20 cs_align_center position-relative">
            <div class="cs_slider_container" data-autoplay="0" data-loop="1" data-speed="800" data-center="1" data-variable-width="1" data-slides-per-view="1">
                <div class="cs_slider_wrapper">
                    @foreach ($services as $index => $service)
                        <div class="cs_slide">
                            <div class="cs_card_wrapper">
                                <div class="cs_card cs_style_2 cs_radius_15 text-center position-relative">
                                    <div class="cs_card_overlay cs_heading_bg position-absolute"></div>
                                    <div class="cs_card_thumbnail cs_radius_15">
                                        <img src="{{ $aboutPage->imageUrlFor('services', $index) ?: asset('frontend-assets/img/city_10.jpg') }}" alt="{{ $service['title'] ?? 'Service image' }}">
                                    </div>
                                    <div class="cs_card_info">
                                        <h3 class="cs_fs_28 cs_semibold cs_white_color cs_body_font cs_mb_3">{{ $service['title'] ?? '' }}</h3>
                                        <p class="cs_white_color mb-0">{{ $service['subtitle'] ?? '' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="container">
                <div class="cs_height_80 cs_height_lg_50"></div>
                <div class="d-flex align-items-center justify-content-between gap-4">
                    <div class="cs_slider_arrows cs_style_1 cs_type_1">
                        <div class="cs_left_arrow rounded-circle cs_center cs_accent_color wow fadeInLeft">
                            <i class="fa-solid fa-arrow-left"></i>
                        </div>
                        <div class="cs_right_arrow rounded-circle cs_center cs_accent_color wow fadeInRight">
                            <i class="fa-solid fa-arrow-right"></i>
                        </div>
                    </div>
                    <div class="cs_pagination cs_style_1 wow fadeInDown"></div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="cs_height_130 cs_height_lg_80"></div>
        <div class="container">
            <div class="cs_review_content_wrapper cs_slider cs_style_1 cs_slider_gap_20 cs_ptb_12 cs_testimonial_slider position-relative">
                <div class="cs_review_shape_1 cs_accent_bg cs_radius_15 position-absolute"></div>
                <div class="cs_review_shape_2 position-absolute">
                    <img src="{{ asset('frontend-assets/img/dots_shape.svg') }}" alt="Dots Shape">
                </div>
                <div class="cs_review_shape_3 position-absolute">
                    <img src="{{ asset('frontend-assets/img/icons/quote_1.svg') }}" alt="Quote Shape">
                </div>
                <div class="row align-items-center">
                    <div class="col-xxl-3 col-lg-4">
                        <div class="cs_section_heading cs_style_1 cs_mb_30">
                            <h2 class="cs_fs_48 cs_semibold mb-0 wow fadeInLeft">{{ $aboutPage->testimonial_section_title }}</h2>
                        </div>
                    </div>
                    <div class="col-xxl-8 col-lg-8 offset-xxl-1">
                        <div class="cs_full_screen_right cs_remove_overflow">
                            <div class="cs_slider_container" data-autoplay="0" data-loop="1" data-speed="800" data-center="0" data-variable-width="0" data-slides-per-view="responsive" data-xs-slides="1" data-sm-slides="2" data-md-slides="3" data-lg-slides="4" data-add-slides="4">
                                <div class="cs_slider_wrapper">
                                    @foreach ($testimonials as $index => $testimonial)
                                        <div class="cs_slide">
                                            <div class="cs_card cs_style_3 cs_white_bg cs_radius_15">
                                                <div class="cs_avatar cs_style_1 cs_mb_40 cs_mb_lg_24">
                                                    <div class="cs_avatar_thumbnail cs_center cs_accent_bg cs_fs_20 cs_white_color cs_radius_50">
                                                        <img src="{{ $aboutPage->imageUrlFor('testimonials', $index) ?: asset('frontend-assets/img/avatar_1.jpg') }}" alt="{{ $testimonial['name'] ?? 'Avatar' }}">
                                                    </div>
                                                    <div class="cs_avatar_info">
                                                        <h3 class="cs_fs_18 cs_semibold cs_body_font">{{ $testimonial['name'] ?? '' }}</h3>
                                                        <p class="cs_fs_14 mb-0">{{ $testimonial['location'] ?? '' }}</p>
                                                    </div>
                                                </div>
                                                <blockquote>{{ $testimonial['quote'] ?? '' }}</blockquote>
                                                <div class="cs_rating cs_accent_color" data-rating="{{ rtrim(rtrim(number_format((float) ($testimonial['rating'] ?? 5), 1, '.', ''), '0'), '.') }}">
                                                    <div class="cs_rating_percentage"></div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
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

    <div class="container wow fadeInRight">
        <div class="cs_horizontal_slider_wrapper cs_radius_15 position-relative">
            <div class="cs_horizontal_in">
                @for ($loopIndex = 0; $loopIndex < 2; $loopIndex++)
                    <div class="cs_brands_wrapper">
                        @foreach ($brands as $index => $brand)
                            <div class="cs_brand">
                                <img src="{{ $aboutPage->imageUrlFor('brands', $index) ?: asset('frontend-assets/img/brand_1.svg') }}" alt="{{ $brand['name'] ?? 'Brand logo' }}">
                            </div>
                        @endforeach
                    </div>
                @endfor
            </div>
        </div>
    </div>

    <section>
        <div class="cs_height_120 cs_height_lg_80"></div>
        <div class="container">
            <div class="cs_section_heading cs_style_1 text-center">
                <h2 class="cs_fs_48 cs_semibold mb-0 wow fadeInDown">{{ $aboutPage->faq_section_title }}</h2>
            </div>
            <div class="cs_height_80 cs_height_lg_50"></div>
            <div class="row">
                <div class="col-xl-8 offset-xl-2">
                    <div class="cs_accordians_wrapper cs_style_1 cs_radius_15">
                        @foreach ($faqs as $index => $faq)
                            <div class="cs_accordian cs_style_1 cs_white_bg {{ $index === 0 ? 'active' : '' }} wow fadeInLeft" @if($index > 0) data-wow-delay="{{ $index * 150 }}ms" @endif>
                                <div class="cs_accordian_overlay cs_accent_bg position-absolute"></div>
                                <div class="cs_accordian_head">
                                    <h2 class="cs_accordian_title cs_fs_24 cs_medium cs_body_font mb-0">{{ $faq['question'] ?? '' }}</h2>
                                    <span class="cs_accordian_toggle cs_center cs_accent_color cs_radius_50 position-absolute"><i class="fa-solid fa-arrow-down"></i></span>
                                </div>
                                <div class="cs_accordian_body">
                                    <p>{{ $faq['answer'] ?? '' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="cs_height_70 cs_height_lg_50"></div>
            <div class="cs_center">
                <a href="{{ route('contact') }}" aria-label="Click to contact" class="cs_btn cs_style_1 cs_accent_bg cs_white_color cs_medium cs_radius_7 wow fadeInUp">
                    <span class="cs_btn_text">Other Questions</span>
                </a>
            </div>
        </div>
        <div class="cs_height_130 cs_height_lg_80"></div>
    </section>
@endsection
