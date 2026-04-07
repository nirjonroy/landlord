<section id="featured-properties" class="cs_tabs cs_style_2">
    <div class="cs_height_120 cs_height_lg_80"></div>
    <div class="container">
        <div class="cs_section_heading cs_style_1 text-center">
            <h2 class="cs_fs_48 cs_semibold mb-0 wow fadeInUp">Bangladesh Listings in BDT</h2>
        </div>
        <div class="cs_height_50 cs_height_lg_30"></div>
        <ul class="cs_tab_links cs_style_2 cs_center cs_mp_0">
            <li class="active"><a href="#for_rent" aria-label="For rent tab button">For Rent</a></li>
            <li><a href="#for_sale" aria-label="For sale tab button">For Sale</a></li>
        </ul>
        <div class="cs_tab_body">
            <div class="cs_tab active" id="for_rent">
                <div class="row cs_gap_y_24">
                    @forelse ($rentProperties as $property)
                        <div class="col-xl-4 col-md-6">
                            <div class="cs_card cs_style_1 cs_white_bg cs_radius_15 position-relative">
                                <a href="#featured-properties" aria-label="Click to visit property details" class="cs_card_thumbnail cs_radius_20">
                                    <img src="{{ asset($property->image_path) }}" alt="{{ $property->title }}">
                                </a>
                                <div class="cs_card_content">
                                    <div class="cs_card_text cs_mb_16">
                                        <h3 class="cs_card_title cs_fs_28 cs_semibold cs_body_font cs_mb_10">
                                            <a href="#featured-properties" aria-label="Click to visit property details">{{ $property->title }}</a>
                                        </h3>
                                        <p class="cs_card_subtitle cs_fs_14 cs_mb_18"><i class="fa-solid fa-location-dot"></i>{{ $property->location }}</p>
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
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="cs_card cs_style_1 cs_white_bg cs_radius_15">
                                <div class="cs_card_content p-4">
                                    <p class="mb-0">No Bangladesh rent listings are seeded yet.</p>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="cs_tab" id="for_sale">
                <div class="row cs_gap_y_24">
                    @forelse ($saleProperties as $property)
                        <div class="col-xl-4 col-md-6">
                            <div class="cs_card cs_style_1 cs_white_bg cs_radius_15 position-relative">
                                <a href="#featured-properties" aria-label="Click to visit property details" class="cs_card_thumbnail cs_radius_20">
                                    <img src="{{ asset($property->image_path) }}" alt="{{ $property->title }}">
                                </a>
                                <div class="cs_card_content">
                                    <div class="cs_card_text cs_mb_16">
                                        <h3 class="cs_card_title cs_fs_28 cs_semibold cs_body_font cs_mb_10">
                                            <a href="#featured-properties" aria-label="Click to visit property details">{{ $property->title }}</a>
                                        </h3>
                                        <p class="cs_card_subtitle cs_fs_14 cs_mb_18"><i class="fa-solid fa-location-dot"></i>{{ $property->location }}</p>
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
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="cs_card cs_style_1 cs_white_bg cs_radius_15">
                                <div class="cs_card_content p-4">
                                    <p class="mb-0">No Bangladesh sale listings are seeded yet.</p>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <div class="cs_height_120 cs_height_lg_80"></div>
</section>
