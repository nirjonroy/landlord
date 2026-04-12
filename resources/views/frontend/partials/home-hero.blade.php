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

    $heroPropertyTypes = $homePropertyTypes
        ->map(fn ($propertyType) => [
            'label' => $propertyType->name,
            'value' => $propertyType->filter_value,
        ])
        ->values();

    if ($heroPropertyTypes->isEmpty()) {
        $heroPropertyTypes = collect([
            ['label' => 'House', 'value' => 'House'],
            ['label' => 'Apartment', 'value' => 'Apartment'],
            ['label' => 'Land', 'value' => 'Land'],
            ['label' => 'Office', 'value' => 'Office'],
        ]);
    }

    $heroLocations = $popularCities
        ->pluck('name')
        ->filter()
        ->unique()
        ->values();

    if ($heroLocations->isEmpty()) {
        $heroLocations = collect([
            'Dhaka',
            'Chattogram',
            'Sylhet',
            'Rajshahi',
            'Khulna',
            "Cox's Bazar",
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
                        @include('frontend.partials.home-hero-search-form', [
                            'tabId' => 'buy',
                            'purpose' => 'sale',
                            'defaultLocation' => 'Dhaka',
                            'heroPropertyTypes' => $heroPropertyTypes,
                            'heroLocations' => $heroLocations,
                        ])
                    </div>
                    <div class="cs_tab" id="co_living">
                        @include('frontend.partials.home-hero-search-form', [
                            'tabId' => 'co_living',
                            'purpose' => 'rent',
                            'defaultLocation' => 'Dhaka',
                            'heroPropertyTypes' => $heroPropertyTypes,
                            'heroLocations' => $heroLocations,
                        ])
                    </div>
                    <div class="cs_tab" id="rent">
                        @include('frontend.partials.home-hero-search-form', [
                            'tabId' => 'rent',
                            'purpose' => 'rent',
                            'defaultLocation' => 'Dhaka',
                            'heroPropertyTypes' => $heroPropertyTypes,
                            'heroLocations' => $heroLocations,
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
