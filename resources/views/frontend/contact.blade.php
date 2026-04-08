@extends('layouts.frontend-public')

@section('title', 'Contact | '.$siteName)
@section('meta_description', $contactPage->form_intro ?: ($siteInfo->short_description ?: $siteName))

@push('styles')
  <style>
    .cs_contact_notice {
      width: 100%;
      padding: 16px 18px;
      margin-bottom: 24px;
      border-radius: 12px;
      font-size: 15px;
      line-height: 1.6em;
    }

    .cs_contact_notice_success {
      color: #0f5132;
      background-color: #d1e7dd;
      border: 1px solid #badbcc;
    }

    .cs_contact_notice_error {
      color: #842029;
      background-color: #f8d7da;
      border: 1px solid #f5c2c7;
    }

    .cs_form_error {
      margin-top: 8px;
      color: #dc3545;
      font-size: 14px;
      line-height: 1.5em;
    }

    .cs_contact_intro {
      margin-bottom: 24px;
      max-width: 760px;
      text-align: center;
    }

    .cs_contact_meta {
      display: flex;
      gap: 12px 24px;
      flex-wrap: wrap;
      justify-content: center;
      margin-bottom: 28px;
      color: var(--heading-color);
      font-weight: 500;
    }
  </style>
@endpush

@section('content')
    <section class="cs_page_heading cs_style_2 position-relative">
        <div class="container">
            <div class="cs_page_heading_content_wrapper cs_type_1 cs_heading_bg cs_bg_filed cs_center_column" data-src="{{ $contactPage->imageUrlFor('hero') ?: asset('frontend-assets/img/page_header_2.jpg') }}">
                <h1 class="cs_fs_48 cs_semibold cs_mb_7 wow zoomIn">{{ $contactPage->hero_title }}</h1>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" aria-label="Back to home button">Home</a></li>
                    <li class="breadcrumb-item active">Contact</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="cs_contact position-relative">
        <div class="container">
            <div class="cs_contact_form_wrapper cs_white_bg cs_center_column">
                <h2 class="cs_fs_28 cs_semibold cs_body_font cs_mb_20 wow fadeInDown">{{ $contactPage->form_title }}</h2>

                @if ($contactPage->form_intro)
                    <p class="cs_contact_intro">{{ $contactPage->form_intro }}</p>
                @endif

                <div class="cs_contact_meta">
                    @if ($siteInfo->contact_phone)
                        <span><i class="fa-solid fa-phone"></i> {{ $siteInfo->contact_phone }}</span>
                    @endif
                    @if ($siteInfo->contact_email)
                        <span><i class="fa-solid fa-envelope"></i> {{ $siteInfo->contact_email }}</span>
                    @endif
                </div>

                @if (session('status') === 'contact-message-sent')
                    <div class="cs_contact_notice cs_contact_notice_success">
                        {{ $contactPage->success_message }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="cs_contact_notice cs_contact_notice_error">
                        Please review the highlighted contact fields and try again.
                    </div>
                @endif

                <form method="POST" action="{{ route('contact.store') }}" class="cs_contact_form row cs_row_gap_40 cs_gap_y_20" id="cs_form">
                    @csrf
                    <div class="col-sm-6 wow fadeInLeft">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="cs_form_field cs_radius_8" id="name" value="{{ old('name') }}" autocomplete="name" required>
                        @error('name')
                            <div class="cs_form_error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-6 wow fadeInRight">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="cs_form_field cs_radius_8" id="email" value="{{ old('email') }}" autocomplete="email" required>
                        @error('email')
                            <div class="cs_form_error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-12 wow fadeInLeft">
                        <label for="message">Message</label>
                        <textarea name="message" rows="4" class="cs_form_field cs_radius_8" id="message" required>{{ old('message') }}</textarea>
                        @error('message')
                            <div class="cs_form_error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-12 wow fadeInUp">
                        <button type="submit" aria-label="Submit button" class="cs_btn cs_style_1 cs_accent_bg cs_medium cs_white_color cs_radius_7">
                            <span class="cs_btn_text">{{ $contactPage->submit_button_text }}</span>
                        </button>
                    </div>
                </form>
            </div>
            <div class="cs_contact_shape_1 cs_accent_color position-absolute">
                <img src="{{ asset('frontend-assets/img/dots_shape.svg') }}" alt="Dots shape">
            </div>
            <div class="cs_contact_shape_2 cs_accent_color position-absolute">
                <img src="{{ asset('frontend-assets/img/dots_shape.svg') }}" alt="Dots shape">
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
                            <h2 class="cs_fs_48 cs_semibold mb-0 wow fadeInLeft">{{ $contactPage->testimonial_section_title }}</h2>
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
                                                        <img src="{{ $contactPage->imageUrlFor('testimonials', $index) ?: asset('frontend-assets/img/avatar_1.jpg') }}" alt="{{ $testimonial['name'] ?? 'Avatar' }}">
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

    <div class="container">
        <div class="cs_horizontal_slider_wrapper cs_radius_15 position-relative">
            <div class="cs_horizontal_in">
                @for ($loopIndex = 0; $loopIndex < 2; $loopIndex++)
                    <div class="cs_brands_wrapper">
                        @foreach ($brands as $index => $brand)
                            <div class="cs_brand">
                                <img src="{{ $contactPage->imageUrlFor('brands', $index) ?: asset('frontend-assets/img/brand_1.svg') }}" alt="{{ $brand['name'] ?? 'Brand logo' }}">
                            </div>
                        @endforeach
                    </div>
                @endfor
            </div>
        </div>
    </div>
    <div class="cs_height_130 cs_height_lg_80"></div>
@endsection
