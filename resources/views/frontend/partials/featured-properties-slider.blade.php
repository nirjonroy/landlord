<!-- Start Featured Properties Section -->
<section>
    <div class="container">
        <div class="cs_featured_properties position-relative">
            <div class="cs_height_150 cs_height_lg_80"></div>
            <div class="row">
                <div class="col-xxl-5 col-xl-6 col-lg-7 offset-lg-1">
                    <div class="cs_section_heading cs_style_1 cs_mb_28">
                        <h2 class="cs_fs_48 cs_semibold mb-0 wow fadeInLeft">Explore Featured Properties in Bangladesh</h2>
                    </div>
                </div>
            </div>
            <div class="cs_slider cs_style_1 cs_slider_gap_20 cs_ptb_12 cs_overflow_visible position-relative">
                <div class="row">
                    <div class="col-xl-3 col-lg-3 position-relative">
                        <div class="cs_slider_arrows cs_style_1">
                            <div class="cs_left_arrow rounded-circle cs_center cs_accent_color wow fadeInLeft">
                                <i class="fa-solid fa-arrow-left"></i>
                            </div>
                            <div class="cs_right_arrow rounded-circle cs_center cs_accent_color wow fadeInRight">
                                <i class="fa-solid fa-arrow-right"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-9 col-lg-9 wow fadeInUp">
                        <div class="cs_full_screen_right cs_variable_width_wrap">
                            <div class="cs_slider_container" data-autoplay="0" data-loop="1" data-speed="600" data-center="0" data-variable-width="1" data-slides-per-view="responsive" data-xs-slides="1" data-sm-slides="2" data-md-slides="2" data-lg-slides="2" data-add-slides="3">
                                <div class="cs_slider_wrapper">
                                    @forelse ($featuredProperties as $property)
                                        <div class="cs_slide">
                                            <div class="cs_card cs_style_1 cs_white_bg cs_radius_15 position-relative">
                                                <a href="#featured-properties" aria-label="Click to visit property details" class="cs_card_thumbnail cs_radius_20">
                                                    <img src="{{ asset($property->image_path) }}" alt="{{ $property->title }}">
                                                </a>
                                                <div class="cs_card_content">
                                                    <div class="cs_card_text cs_mb_16">
                                                        <h3 class="cs_card_title cs_fs_28 cs_semibold cs_body_font cs_mb_10">
                                                            <a href="#featured-properties" aria-label="Click to visit property details">{{ $property->title }}</a>
                                                        </h3>
                                                        <p class="cs_card_subtitle cs_fs_14 cs_mb_18">
                                                            <i class="fa-solid fa-location-dot"></i> {{ $property->location }}
                                                        </p>
                                                        <ul class="cs_card_features_list cs_mp_0">
                                                            <li><span class="cs_accent_color"><i class="fa-solid fa-bed"></i></span> {{ $property->beds_label }}</li>
                                                            <li><span class="cs_accent_color"><i class="fa-solid fa-car"></i></span> {{ $property->garage_label }}</li>
                                                            <li><span class="cs_accent_color"><i class="fa-solid fa-bath"></i></span> {{ $property->baths_label }}</li>
                                                            <li><span class="cs_accent_color"><i class="fa-solid fa-expand"></i></span> {{ $property->area_label }}</li>
                                                        </ul>
                                                    </div>
                                                    <div class="cs_card_btns_wrapper">
                                                        <div class="cs_card_price cs_radius_10">
                                                            <h4 class="cs_card_price_for cs_fs_16 cs_normal cs_body_font mb-0">{{ $property->purpose_label }}</h4>
                                                            <h3 class="cs_card_price_value cs_fs_24 cs_semibold cs_body_font mb-0">{{ $property->formatted_price }}</h3>
                                                        </div>
                                                        <a href="#featured-properties" aria-label="Click to visit property details" class="cs_card_btn cs_center cs_accent_bg cs_white_color cs_radius_7">
                                                            <span><i class="fa-solid fa-arrow-right"></i></span>
                                                            <span><i class="fa-solid fa-arrow-right"></i></span>
                                                        </a>
                                                    </div>
                                                </div>
                                                <button type="button" aria-label="Heart button" class="cs_heart_icon cs_center cs_white_bg cs_accent_color cs_radius_50 position-absolute">
                                                    <i class="fa-regular fa-heart"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="cs_slide">
                                            <div class="cs_card cs_style_1 cs_white_bg cs_radius_15 position-relative">
                                                <div class="cs_card_content p-4">
                                                    <h3 class="cs_card_title cs_fs_28 cs_semibold cs_body_font cs_mb_10">Bangladesh properties will appear here</h3>
                                                    <p class="cs_card_subtitle cs_fs_14 mb-0">Run the database seeder to load the Dhaka, Chattogram, Sylhet, and Rajshahi demo properties.</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="cs_featured_shape_1 cs_accent_color position-absolute">
                <svg width="88" height="127" viewBox="0 0 88 127" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="3" cy="3" r="3" transform="matrix(1 0 0 -1 0 126.863)" fill="currentColor"/>
                </svg>
            </div>
        </div>
    </div>
</section>
<!-- End Featured Properties Section -->
