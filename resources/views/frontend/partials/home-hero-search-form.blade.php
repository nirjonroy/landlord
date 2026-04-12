@php
    $locationDatalistId = $tabId.'_location_options';
@endphp

<form method="GET" action="{{ route('properties.index') }}" class="cs_property_filter_form cs_home_hero_filter_form position-relative">
    <input type="hidden" name="purpose" value="{{ $purpose }}">

    <div class="cs_property_search_input_wrapper">
        <div class="cs_property_search_input">
            <input
                type="text"
                name="search"
                id="{{ $tabId }}_search"
                placeholder="Enter an address, neighborhood, city, or ZIP code"
                aria-label="Address, neighborhood, city, or ZIP code"
                autocomplete="off"
                data-location-search="search"
            >
            <span class="cs_property_search_input_icon"><i class="fa-solid fa-magnifying-glass"></i></span>
        </div>
        <p class="cs_property_search_hint">Search by road, neighborhood, city, district, or postal area across Bangladesh.</p>
    </div>

    <div class="cs_custom_select_wrapper">
        <label for="{{ $tabId }}_property_type">Type</label>
        <select class="cs_fs_20 cs_semibold cs_custom_select" name="property_type" id="{{ $tabId }}_property_type">
            <option value="">Any Property</option>
            @foreach ($heroPropertyTypes as $propertyType)
                <option value="{{ $propertyType['value'] }}">{{ $propertyType['label'] }}</option>
            @endforeach
        </select>
    </div>

    <div class="cs_custom_select_wrapper cs_text_input_wrapper">
        <label for="{{ $tabId }}_location">Location</label>
        <input
            type="text"
            name="location"
            id="{{ $tabId }}_location"
            list="{{ $locationDatalistId }}"
            class="cs_form_field cs_radius_7"
            placeholder="City or neighborhood"
            value="{{ $defaultLocation }}"
            data-location-search="location"
        >
        <datalist id="{{ $locationDatalistId }}">
            @foreach ($heroLocations as $heroLocation)
                <option value="{{ $heroLocation }}"></option>
            @endforeach
        </datalist>
    </div>

    <div class="cs_custom_select_wrapper cs_text_input_wrapper">
        <label for="{{ $tabId }}_postal_code">ZIP Code</label>
        <input
            type="text"
            name="postal_code"
            id="{{ $tabId }}_postal_code"
            class="cs_form_field cs_radius_7"
            placeholder="1209"
            inputmode="numeric"
            autocomplete="postal-code"
            data-location-search="postal"
        >
    </div>

    <div class="cs_btns_wrapper">
        <button
            type="button"
            aria-label="Use current location button"
            class="cs_btn cs_style_1 cs_type_1 cs_white_bg cs_radius_7 js-use-current-location"
        >
            <span class="cs_btn_icon"><i class="fa-solid fa-location-crosshairs"></i></span>
            <span class="cs_btn_text">Current Location</span>
        </button>
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
            <div class="col-lg-6 col-sm-6">
                <input type="text" name="min_price" placeholder="Min Price (BDT)">
            </div>
            <div class="col-lg-6 col-sm-6">
                <input type="text" name="max_price" placeholder="Max Price (BDT)">
            </div>
        </div>
    </div>
</form>
