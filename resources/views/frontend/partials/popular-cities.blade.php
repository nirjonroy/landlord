<!-- Start Popular Cities Section -->
<section>
    <div class="cs_height_120 cs_height_lg_80"></div>
    <div class="container">
        <div class="cs_section_heading cs_style_1 text-center">
            <h2 class="cs_fs_48 cs_semibold mb-0 wow fadeInUp">We Are Available Across Bangladesh</h2>
        </div>
        <div class="cs_height_80 cs_height_lg_50"></div>
        <div class="cs_slider cs_style_1 cs_slider_gap_20 cs_align_center cs_overflow_visible_2 position-relative">
            <div class="cs_slider_container" data-autoplay="0" data-loop="1" data-speed="800" data-center="1" data-variable-width="1" data-slides-per-view="1">
                <div class="cs_slider_wrapper">
                    @forelse ($popularCities as $city)
                        <div class="cs_slide">
                            <div class="cs_card_wrapper">
                                <a href="#featured-properties" aria-label="Click to visit all property" class="cs_card cs_style_2 cs_radius_15 text-center position-relative">
                                    <div class="cs_card_overlay cs_heading_bg position-absolute"></div>
                                    <div class="cs_card_thumbnail cs_radius_15">
                                        <img src="{{ asset($city->image_path) }}" alt="{{ $city->name }} City Image">
                                    </div>
                                    <div class="cs_card_info">
                                        <h3 class="cs_fs_28 cs_semibold cs_white_color cs_body_font cs_mb_3">{{ $city->name }}</h3>
                                        <p class="cs_white_color mb-0">{{ number_format($city->property_count) }} properties</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="cs_slide">
                            <div class="cs_card_wrapper">
                                <div class="cs_card cs_style_2 cs_radius_15 text-center position-relative">
                                    <div class="cs_card_overlay cs_heading_bg position-absolute"></div>
                                    <div class="cs_card_info py-5">
                                        <h3 class="cs_fs_28 cs_semibold cs_white_color cs_body_font cs_mb_3">Bangladesh Cities</h3>
                                        <p class="cs_white_color mb-0">Run the seeder to load city coverage.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforelse
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
                <div class="cs_pagination cs_style_1"></div>
            </div>
        </div>
    </div>
</section>
<!-- End Popular Cities Section -->
