@php
    $heroBanners = $homepageBanners
        ->map(fn ($banner) => [
            'heading' => $banner->heading,
            'sub_text' => $banner->sub_text,
            'image_url' => $banner->image_url,
        ])
        ->filter(fn ($banner) => filled($banner['image_url']))
        ->values();

    if ($heroBanners->isEmpty()) {
        $heroBanners = collect([
            [
                'heading' => 'Find verified homes across Bangladesh',
                'sub_text' => 'Search rent and sale properties from trusted landlords, owners, and verified listings in Dhaka, Chattogram, Sylhet, and beyond.',
                'image_url' => asset('frontend-assets/img/hero_img_1.jpg'),
            ],
        ]);
    }
@endphp

<section class="cs_hero cs_style_1">
    <div class="container">
        <div class="cs_slider cs_style_1 cs_hero_banner_slider position-relative">
            <div class="cs_slider_container" data-autoplay="{{ $heroBanners->count() > 1 ? 1 : 0 }}" data-loop="{{ $heroBanners->count() > 1 ? 1 : 0 }}" data-speed="800" data-center="0" data-variable-width="0" data-slides-per-view="1">
                <div class="cs_slider_wrapper">
                    @foreach ($heroBanners as $banner)
                        <div class="cs_slide">
                            <div class="cs_hero_content_wrapper cs_center_column cs_bg_filed cs_radius_25" data-src="{{ $banner['image_url'] }}">
                                <div class="cs_hero_text text-center">
                                    <h1 class="cs_hero_title cs_fs_64 cs_mb_34 wow fadeInDown">{{ $banner['heading'] }}</h1>
                                    @if (filled($banner['sub_text']))
                                        <p class="cs_fs_24 mb-0 wow fadeInUp">{{ $banner['sub_text'] }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            @if ($heroBanners->count() > 1)
                <div class="cs_slider_arrows cs_style_1 cs_hero_banner_arrows">
                    <div class="cs_left_arrow rounded-circle cs_center cs_accent_color">
                        <i class="fa-solid fa-arrow-left"></i>
                    </div>
                    <div class="cs_right_arrow rounded-circle cs_center cs_accent_color">
                        <i class="fa-solid fa-arrow-right"></i>
                    </div>
                </div>
            @endif
        </div>

        <div class="cs_tabs cs_style_1 wow fadeInUp">
            <div class="cs_filter_content_wrapper cs_type_1">
                <ul class="cs_tab_links cs_style_1 cs_center cs_mp_0">
                    <li class="active"><a href="#buy" aria-label="Tab button">Buy</a></li>
                    <li><a href="#co_living" aria-label="Tab Link button">Co-Living</a></li>
                    <li><a href="#rent" aria-label="Tab button">Rent</a></li>
                </ul>
                <div class="cs_tab_body cs_white_bg cs_radius_20">
                    <div class="cs_tab active" id="buy">
                        <form class="cs_property_filter_form position-relative">
                            <div class="cs_custom_select_wrapper">
                                <label for="buy_house">Type</label>
                                <select class="cs_fs_20 cs_semibold cs_custom_select" name="house" id="buy_house">
                                    <option value="houses" selected>Houses</option>
                                    <option value="open_house">Open House</option>
                                    <option value="rent_house">Rent House</option>
                                    <option value="sale_house">Sale House</option>
                                    <option value="buy_house">Buy House</option>
                                </select>
                            </div>
                            <div class="cs_custom_select_wrapper">
                                <label for="buy_location">Location</label>
                                <select class="cs_fs_20 cs_semibold cs_custom_select" name="location" id="buy_location">
                                    <option value="dhaka" selected>Dhaka</option>
                                    <option value="chattogram">Chattogram</option>
                                    <option value="sylhet">Sylhet</option>
                                    <option value="rajshahi">Rajshahi</option>
                                    <option value="khulna">Khulna</option>
                                    <option value="coxs_bazar">Cox's Bazar</option>
                                </select>
                            </div>
                            <div class="cs_btns_wrapper">
                                <button type="button" aria-label="Advanced Search Button" class="cs_btn cs_style_1 cs_type_1 cs_white_bg advanced_search cs_radius_7">
                                    <span class="cs_btn_icon"><i class="fa-solid fa-sliders"></i></span>
                                    <span class="cs_btn_text">Advanced Search</span>
                                </button>
                                <button type="submit" aria-label="Search Button" class="cs_btn cs_style_1 cs_accent_bg cs_white_color cs_radius_7">
                                    <span class="cs_btn_icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                                    <span class="cs_btn_text">Search</span>
                                </button>
                            </div>
                            <div class="cs_advanced_options_wrapper">
                                <div class="row cs_gap_y_24">
                                    <div class="col-lg-3 col-sm-6">
                                        <select aria-label="Property Location" name="location" class="cs_fs_20 cs_semibold cs_custom_select">
                                            <option selected>Any Location</option>
                                            <option value="dhaka">Dhaka</option>
                                            <option value="chattogram">Chattogram</option>
                                            <option value="sylhet">Sylhet</option>
                                            <option value="rajshahi">Rajshahi</option>
                                            <option value="khulna">Khulna</option>
                                            <option value="coxs_bazar">Cox's Bazar</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <select aria-label="Property Type" name="house" class="cs_fs_20 cs_semibold cs_custom_select">
                                            <option selected>Any Property</option>
                                            <option value="open_house">Open House</option>
                                            <option value="rent_house">Rent House</option>
                                            <option value="sale_house">Sale House</option>
                                            <option value="buy_house">Buy House</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <select aria-label="Property Bedrooms" name="bedroom" class="cs_fs_20 cs_semibold cs_custom_select">
                                            <option selected>Bedrooms</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <select aria-label="Property Bathrooms" name="bathroom" class="cs_fs_20 cs_semibold cs_custom_select">
                                            <option selected>Bathrooms</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cs_range-slider-wrap">
                                            <div class="cs_amount-wrap">
                                                <input type="text" name="pvalue-range" class="cs_amount" readonly>
                                            </div>
                                            <div class="cs_slider_range"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <input type="text" name="minarea" placeholder="Min Area (sqft)">
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <input type="text" name="maxarea" placeholder="Max Area (sqft)">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="cs_tab" id="co_living">
                        <form class="cs_property_filter_form position-relative">
                            <div class="cs_custom_select_wrapper">
                                <label for="co_living_house">Type</label>
                                <select class="cs_fs_20 cs_semibold cs_custom_select" name="house" id="co_living_house">
                                    <option value="houses" selected>Houses</option>
                                    <option value="open_house">Open House</option>
                                    <option value="rent_house">Rent House</option>
                                    <option value="sale_house">Sale House</option>
                                    <option value="buy_house">Buy House</option>
                                </select>
                            </div>
                            <div class="cs_custom_select_wrapper">
                                <label for="co_living_location">Location</label>
                                <select class="cs_fs_20 cs_semibold cs_custom_select" name="location" id="co_living_location">
                                    <option value="dhaka" selected>Dhaka</option>
                                    <option value="chattogram">Chattogram</option>
                                    <option value="sylhet">Sylhet</option>
                                    <option value="rajshahi">Rajshahi</option>
                                    <option value="khulna">Khulna</option>
                                    <option value="coxs_bazar">Cox's Bazar</option>
                                </select>
                            </div>
                            <div class="cs_btns_wrapper">
                                <button type="button" aria-label="Advanced Search Button" class="cs_btn cs_style_1 cs_type_1 cs_white_bg advanced_search cs_radius_7">
                                    <span class="cs_btn_icon"><i class="fa-solid fa-sliders"></i></span>
                                    <span class="cs_btn_text">Advanced Search</span>
                                </button>
                                <button type="submit" aria-label="Search Button" class="cs_btn cs_style_1 cs_accent_bg cs_white_color cs_radius_7">
                                    <span class="cs_btn_icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                                    <span class="cs_btn_text">Search</span>
                                </button>
                            </div>
                            <div class="cs_advanced_options_wrapper">
                                <div class="row cs_gap_y_24">
                                    <div class="col-lg-3 col-sm-6">
                                        <select aria-label="Property Location" name="location" class="cs_fs_20 cs_semibold cs_custom_select">
                                            <option selected>Any Location</option>
                                            <option value="dhaka">Dhaka</option>
                                            <option value="chattogram">Chattogram</option>
                                            <option value="sylhet">Sylhet</option>
                                            <option value="rajshahi">Rajshahi</option>
                                            <option value="khulna">Khulna</option>
                                            <option value="coxs_bazar">Cox's Bazar</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <select aria-label="Property Type" name="house" class="cs_fs_20 cs_semibold cs_custom_select">
                                            <option selected>Any Property</option>
                                            <option value="open_house">Open House</option>
                                            <option value="rent_house">Rent House</option>
                                            <option value="sale_house">Sale House</option>
                                            <option value="buy_house">Buy House</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <select aria-label="Property Bedrooms" name="bedroom" class="cs_fs_20 cs_semibold cs_custom_select">
                                            <option selected>Bedrooms</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <select aria-label="Property Bathrooms" name="bathroom" class="cs_fs_20 cs_semibold cs_custom_select">
                                            <option selected>Bathrooms</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cs_range-slider-wrap">
                                            <div class="cs_amount-wrap">
                                                <input type="text" name="pvalue-range" class="cs_amount" readonly>
                                            </div>
                                            <div class="cs_slider_range"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <input type="text" name="minarea" placeholder="Min Area (sqft)">
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <input type="text" name="maxarea" placeholder="Max Area (sqft)">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="cs_tab" id="rent">
                        <form class="cs_property_filter_form position-relative">
                            <div class="cs_custom_select_wrapper">
                                <label for="rent_house_type">Type</label>
                                <select class="cs_fs_20 cs_semibold cs_custom_select" name="house" id="rent_house_type">
                                    <option value="houses" selected>Houses</option>
                                    <option value="open_house">Open House</option>
                                    <option value="rent_house">Rent House</option>
                                    <option value="sale_house">Sale House</option>
                                    <option value="buy_house">Buy House</option>
                                </select>
                            </div>
                            <div class="cs_custom_select_wrapper">
                                <label for="rent_location">Location</label>
                                <select class="cs_fs_20 cs_semibold cs_custom_select" name="location" id="rent_location">
                                    <option value="dhaka" selected>Dhaka</option>
                                    <option value="chattogram">Chattogram</option>
                                    <option value="sylhet">Sylhet</option>
                                    <option value="rajshahi">Rajshahi</option>
                                    <option value="khulna">Khulna</option>
                                    <option value="coxs_bazar">Cox's Bazar</option>
                                </select>
                            </div>
                            <div class="cs_btns_wrapper">
                                <button type="button" aria-label="Advanced Search Button" class="cs_btn cs_style_1 cs_type_1 cs_white_bg advanced_search cs_radius_7">
                                    <span class="cs_btn_icon"><i class="fa-solid fa-sliders"></i></span>
                                    <span class="cs_btn_text">Advanced Search</span>
                                </button>
                                <button type="submit" aria-label="Search Button" class="cs_btn cs_style_1 cs_accent_bg cs_white_color cs_radius_7">
                                    <span class="cs_btn_icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                                    <span class="cs_btn_text">Search</span>
                                </button>
                            </div>
                            <div class="cs_advanced_options_wrapper">
                                <div class="row cs_gap_y_24">
                                    <div class="col-lg-3 col-sm-6">
                                        <select aria-label="Property Location" name="location" class="cs_fs_20 cs_semibold cs_custom_select">
                                            <option selected>Any Location</option>
                                            <option value="dhaka">Dhaka</option>
                                            <option value="chattogram">Chattogram</option>
                                            <option value="sylhet">Sylhet</option>
                                            <option value="rajshahi">Rajshahi</option>
                                            <option value="khulna">Khulna</option>
                                            <option value="coxs_bazar">Cox's Bazar</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <select aria-label="Property Type" name="house" class="cs_fs_20 cs_semibold cs_custom_select">
                                            <option selected>Any Property</option>
                                            <option value="open_house">Open House</option>
                                            <option value="rent_house">Rent House</option>
                                            <option value="sale_house">Sale House</option>
                                            <option value="buy_house">Buy House</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <select aria-label="Property Bedrooms" name="bedroom" class="cs_fs_20 cs_semibold cs_custom_select">
                                            <option selected>Bedrooms</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <select aria-label="Property Bathrooms" name="bathroom" class="cs_fs_20 cs_semibold cs_custom_select">
                                            <option selected>Bathrooms</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cs_range-slider-wrap">
                                            <div class="cs_amount-wrap">
                                                <input type="text" name="pvalue-range" class="cs_amount" readonly>
                                            </div>
                                            <div class="cs_slider_range"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <input type="text" name="minarea" placeholder="Min Area (sqft)">
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <input type="text" name="maxarea" placeholder="Max Area (sqft)">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
