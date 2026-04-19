@extends('layouts.frontend-public')

@section('title', 'Listings | '.$siteName)
@section('meta_description', 'Browse rent and sale listings across Bangladesh from '.$siteName.'.')

@push('styles')
    <style>
        .cs_listing_notice {
            padding: 18px 20px;
            border-radius: 16px;
            margin-bottom: 30px;
            font-size: 15px;
            line-height: 1.7em;
        }

        .cs_listing_notice_info {
            color: #0c5460;
            background: #d1ecf1;
            border: 1px solid #bee5eb;
        }

        .cs_listing_notice_success {
            color: #0f5132;
            background: #d1e7dd;
            border: 1px solid #badbcc;
        }

        .cs_listing_metric_grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .cs_listing_metric {
            padding: 22px 24px;
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
        }

        .cs_listing_metric span {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #64748b;
        }

        .cs_listing_metric strong {
            display: block;
            font-size: 34px;
            line-height: 1em;
            color: var(--heading-color);
        }

        .cs_listing_filter_grid {
            display: grid;
            gap: 18px;
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        .cs_listing_filter_field label {
            display: block;
            margin-bottom: 10px;
            color: #64748b;
            font-size: 14px;
            font-weight: 600;
        }

        .cs_listing_filter_field input,
        .cs_listing_filter_field select {
            width: 100%;
            min-height: 60px;
            border: 1px solid rgba(15, 23, 42, 0.12);
            border-radius: 14px;
            padding: 14px 18px;
            background: #fff;
            color: var(--heading-color);
        }

        .cs_listing_filter_field .cs_custom_select {
            margin-left: 0;
        }

        .cs_listing_filter_actions {
            display: flex;
            align-items: end;
            gap: 12px;
            flex-wrap: wrap;
        }

        .cs_listing_filter_actions .cs_btn {
            min-width: 150px;
            justify-content: center;
        }

        .cs_listing_hint {
            margin-top: 10px;
            color: #64748b;
            font-size: 14px;
            line-height: 1.6em;
        }

        .cs_listing_card {
            height: 100%;
        }

        .cs_listing_card .cs_card_thumbnail {
            display: block;
            height: 250px;
            overflow: hidden;
        }

        .cs_listing_card .cs_card_thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .cs_listing_badge {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 2;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 92px;
            padding: 10px 16px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 700;
            background: #fff;
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.12);
        }

        .cs_listing_badge_success {
            color: #0f5132;
        }

        .cs_listing_badge_info {
            color: #0c5460;
        }

        .cs_listing_owner {
            margin: -6px 0 14px;
            color: #64748b;
            font-size: 14px;
        }

        .cs_listing_card .cs_card_content {
            display: flex;
            flex-direction: column;
            height: calc(100% - 250px);
        }

        .cs_listing_card_actions {
            display: flex;
            gap: 10px;
            align-items: stretch;
        }

        .cs_listing_card_actions .cs_card_price {
            flex: 1 1 auto;
            min-width: 0;
            min-height: 102px;
            padding: 16px 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .cs_listing_card_actions .cs_card_price_for {
            font-size: 15px !important;
            line-height: 1.2em;
        }

        .cs_listing_card_actions .cs_card_price_value {
            margin-top: 6px;
            font-size: 18px !important;
            line-height: 1.2em;
            word-break: break-word;
        }

        .cs_listing_detail_btn {
            flex: 0 0 118px;
            min-width: 118px;
            min-height: 102px;
            padding: 0 16px;
            justify-content: center;
            text-align: center;
        }

        .cs_listing_detail_btn .cs_btn_text {
            font-size: 15px;
            font-weight: 600;
            line-height: 1.2em;
        }

        .cs_listing_empty_state {
            padding: 40px 34px;
            border: 1px dashed rgba(15, 23, 42, 0.18);
            border-radius: 20px;
            background: #fff;
        }

        .cs_listing_empty_state h3 {
            margin-bottom: 12px;
        }

        .cs_listing_map_box {
            position: sticky;
            top: 130px;
            overflow: hidden;
            border-radius: 20px;
            min-height: 760px;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
        }

        .cs_listing_map_box iframe {
            width: 100%;
            min-height: 760px;
            border: 0;
        }

        .cs_listing_meta_row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 18px;
            flex-wrap: wrap;
        }

        .cs_listing_meta_row p {
            margin: 0;
            color: #64748b;
        }

        .cs_pagination_box .disabled {
            opacity: 0.4;
            pointer-events: none;
        }

        @media (max-width: 1199px) {
            .cs_listing_metric_grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .cs_listing_filter_grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .cs_listing_map_box,
            .cs_listing_map_box iframe {
                min-height: 520px;
            }
        }

        @media (max-width: 767px) {
            .cs_listing_metric_grid,
            .cs_listing_filter_grid {
                grid-template-columns: 1fr;
            }

            .cs_listing_filter_actions {
                align-items: stretch;
            }

            .cs_listing_filter_actions .cs_btn {
                width: 100%;
            }

            .cs_listing_card_actions {
                flex-direction: column;
            }

            .cs_listing_card_actions .cs_card_price {
                min-height: auto;
            }

            .cs_listing_detail_btn {
                width: 100%;
                min-height: 60px;
            }

            .cs_listing_map_box,
            .cs_listing_map_box iframe {
                min-height: 420px;
            }
        }
    </style>
@endpush

@section('content')
    <section class="cs_map_view position-relative">
        <div class="cs_height_60 cs_height_lg_60"></div>
        <div class="container">
            <div class="cs_products_wrap">
                <h1 class="cs_property_heaading cs_fs_48 cs_semibold cs_mb_30 wow fadeInLeft">
                    Property Listing <span>{{ number_format($listingStats['total']) }}</span>
                </h1>

                <div class="cs_listing_notice {{ $listingSource === 'approved' ? 'cs_listing_notice_success' : 'cs_listing_notice_info' }}">
                    <strong>{{ $listingSourceLabel }}:</strong> {{ $listingMessage }}
                </div>

                <div class="cs_listing_metric_grid wow fadeInUp">
                    <div class="cs_listing_metric">
                        <span>Total Results</span>
                        <strong>{{ number_format($listingStats['total']) }}</strong>
                    </div>
                    <div class="cs_listing_metric">
                        <span>For Rent</span>
                        <strong>{{ number_format($listingStats['rent']) }}</strong>
                    </div>
                    <div class="cs_listing_metric">
                        <span>For Sale</span>
                        <strong>{{ number_format($listingStats['sale']) }}</strong>
                    </div>
                    <div class="cs_listing_metric">
                        <span>Active Filters</span>
                        <strong>{{ $activeFiltersCount }}</strong>
                    </div>
                </div>

                <form method="GET" action="{{ route('properties.index') }}" class="cs_listing_filters_form">
                    <div class="cs_filter_heading cs_style_1 cs_mb_39 wow fadeInDown">
                        <div class="w-100">
                            <div class="cs_listing_filter_grid">
                                <div class="cs_listing_filter_field">
                                    <label for="listing_search">Address or neighborhood</label>
                                    <input type="text" id="listing_search" name="search" value="{{ request('search') }}" placeholder="Address, title, neighborhood, or owner" data-location-search="search">
                                </div>
                                <div class="cs_listing_filter_field">
                                    <label for="listing_location">City / Area</label>
                                    <input type="text" id="listing_location" name="location" value="{{ request('location') }}" placeholder="City or neighborhood" data-location-search="location">
                                </div>
                                <div class="cs_listing_filter_field">
                                    <label for="listing_postal_code">ZIP Code</label>
                                    <input type="text" id="listing_postal_code" name="postal_code" value="{{ request('postal_code') }}" placeholder="1209" inputmode="numeric" data-location-search="postal">
                                </div>
                                <div class="cs_listing_filter_field">
                                    <label for="listing_property_type">Property Type</label>
                                    <select id="listing_property_type" aria-label="Property type" name="property_type" class="cs_custom_select" {{ $supportsPropertyTypeFilter ? '' : 'disabled' }}>
                                        <option value="">Any Type</option>
                                        @foreach ($availablePropertyTypes as $propertyType)
                                            <option value="{{ $propertyType->filter_value }}" @selected(request('property_type') === $propertyType->filter_value)>{{ $propertyType->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="cs_listing_filter_field">
                                    <label for="listing_purpose">Listing Purpose</label>
                                    <select id="listing_purpose" aria-label="Property status" name="purpose" class="cs_custom_select">
                                        <option value="">Any Purpose</option>
                                        <option value="rent" @selected(request('purpose') === 'rent')>Rent</option>
                                        <option value="sale" @selected(request('purpose') === 'sale')>Sale</option>
                                    </select>
                                </div>
                                <div class="cs_listing_filter_field">
                                    <label for="listing_min_price">Min Price</label>
                                    <input type="text" id="listing_min_price" name="min_price" value="{{ request('min_price') }}" placeholder="BDT 10,000">
                                </div>
                                <div class="cs_listing_filter_field">
                                    <label for="listing_max_price">Max Price</label>
                                    <input type="text" id="listing_max_price" name="max_price" value="{{ request('max_price') }}" placeholder="BDT 5,00,00,000">
                                </div>
                                <div class="cs_listing_filter_actions">
                                    <button type="button" class="cs_btn cs_style_1 cs_type_1 cs_accent_color cs_radius_7 js-use-current-location">
                                        <span class="cs_btn_text">Current Location</span>
                                    </button>
                                    <button type="submit" aria-label="Apply filter button" class="cs_btn cs_style_1 cs_accent_bg cs_white_color cs_radius_7">
                                        <span class="cs_btn_text">Search</span>
                                        <span class="cs_btn_icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                                    </button>
                                    @if ($activeFiltersCount > 0)
                                        <a href="{{ route('properties.index') }}" aria-label="Reset filters button" class="cs_btn cs_style_1 cs_type_1 cs_accent_color cs_radius_7">
                                            <span class="cs_btn_text">Reset</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @if (! $supportsPropertyTypeFilter)
                        <p class="cs_listing_hint">No property types are active yet. Add and publish them from the admin panel to enable this filter.</p>
                    @endif
                </form>

                <div class="row cs_row_gap_20 cs_gap_y_60">
                    <div class="col-xl-7" id="listing-results">
                        <div class="cs_propducts_view_area">
                            <div class="cs_listing_meta_row wow fadeInUp">
                                <p>Use address, ZIP code, city, price, or your current location to narrow the active listings.</p>
                                <p>{{ $listingStats['total'] }} result{{ $listingStats['total'] === 1 ? '' : 's' }} found</p>
                            </div>

                            @if ($listings->count() > 0)
                                <div class="row cs_row_gap_20 cs_gap_y_20">
                                    @foreach ($listings as $listing)
                                        <div class="col-md-6 wow fadeInUp" id="{{ $listing['id'] }}">
                                            <div class="cs_card cs_style_1 cs_type_2 cs_white_bg cs_radius_15 position-relative cs_listing_card">
                                                <span class="cs_listing_badge cs_listing_badge_{{ $listing['badge_tone'] }}">{{ $listing['badge'] }}</span>
                                                <div class="cs_card_thumbnail cs_radius_20">
                                                    <img src="{{ $listing['image_url'] }}" alt="{{ $listing['title'] }}">
                                                </div>
                                                <div class="cs_card_content">
                                                    <div class="cs_card_text cs_mb_16">
                                                        <h2 class="cs_card_title cs_fs_28 cs_semibold cs_body_font cs_mb_10">{{ $listing['title'] }}</h2>
                                                        <p class="cs_card_subtitle cs_fs_14 cs_mb_10">
                                                            <i class="fa-solid fa-location-dot"></i>{{ $listing['location'] }}
                                                        </p>
                                                        <p class="cs_listing_owner">{{ $listing['property_type'] }} • {{ $listing['owner_name'] }}@if(!empty($listing['postal_code'])) • ZIP {{ $listing['postal_code'] }}@endif</p>
                                                        <ul class="cs_card_features_list cs_mp_0">
                                                            <li><span class="cs_accent_color"><i class="fa-solid fa-bed"></i></span> {{ $listing['beds_label'] }}</li>
                                                            <li><span class="cs_accent_color"><i class="fa-solid fa-warehouse"></i></span> {{ $listing['garage_label'] }}</li>
                                                            <li><span class="cs_accent_color"><i class="fa-solid fa-bath"></i></span> {{ $listing['baths_label'] }}</li>
                                                            <li><span class="cs_accent_color"><i class="fa-solid fa-expand"></i></span> {{ $listing['area_label'] }}</li>
                                                        </ul>
                                                    </div>
                                                    <div class="cs_card_btns_wrapper cs_listing_card_actions">
                                                        <div class="cs_card_price cs_radius_10">
                                                            <h3 class="cs_card_price_for cs_fs_16 cs_normal cs_body_font mb-0">{{ $listing['purpose_label'] }}</h3>
                                                            <h3 class="cs_card_price_value cs_fs_24 cs_semibold cs_body_font mb-0">{{ $listing['price_label'] }}</h3>
                                                        </div>
                                                        <a href="{{ $listing['action_url'] }}" aria-label="{{ $listing['action_label'] }} for {{ $listing['title'] }}" class="cs_btn cs_style_1 cs_accent_bg cs_white_color cs_radius_7 cs_listing_detail_btn">
                                                            <span class="cs_btn_text">{{ $listing['action_label'] }}</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @if ($listings->hasPages())
                                    <div class="cs_height_70 cs_height_lg_50"></div>
                                    <ul class="cs_pagination_box wow fadeInUp">
                                        <li>
                                            @if ($listings->onFirstPage())
                                                <span class="cs_pagination_arrow cs_pagination_arrow_left cs_center cs_accent_color disabled">
                                                    <svg width="11" height="20" viewBox="0 0 11 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M11 0.96476L11 19.0353C11 19.8904 9.96356 20.326 9.34818 19.7129L0.279353 10.6777C-0.0931162 10.3066 -0.0931163 9.69347 0.279353 9.32222L9.34818 0.286953C9.96356 -0.325993 11 0.109636 11 0.96476Z" fill="currentColor"/>
                                                    </svg>
                                                </span>
                                            @else
                                                <a href="{{ $listings->previousPageUrl() }}" aria-label="Pagination arrow left" class="cs_pagination_arrow cs_pagination_arrow_left cs_center cs_accent_color">
                                                    <svg width="11" height="20" viewBox="0 0 11 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M11 0.96476L11 19.0353C11 19.8904 9.96356 20.326 9.34818 19.7129L0.279353 10.6777C-0.0931162 10.3066 -0.0931163 9.69347 0.279353 9.32222L9.34818 0.286953C9.96356 -0.325993 11 0.109636 11 0.96476Z" fill="currentColor"/>
                                                    </svg>
                                                </a>
                                            @endif
                                        </li>
                                        @foreach ($listings->linkCollection()->toArray() as $link)
                                            @continue(in_array($link['label'], ['&laquo; Previous', 'Next &raquo;'], true))
                                            <li>
                                                @if ($link['url'])
                                                    <a class="cs_pagination_item cs_center {{ $link['active'] ? 'active' : '' }}" href="{{ $link['url'] }}">{{ html_entity_decode($link['label']) }}</a>
                                                @else
                                                    <span class="cs_pagination_item cs_center">{{ html_entity_decode($link['label']) }}</span>
                                                @endif
                                            </li>
                                        @endforeach
                                        <li>
                                            @if ($listings->hasMorePages())
                                                <a href="{{ $listings->nextPageUrl() }}" aria-label="Pagination arrow right" class="cs_pagination_arrow cs_pagination_arrow_right cs_center cs_accent_color">
                                                    <svg width="11" height="20" viewBox="0 0 11 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M8.32057e-07 0.96476L4.21689e-08 19.0353C4.79022e-09 19.8904 1.03644 20.326 1.65182 19.7129L10.7206 10.6777C11.0931 10.3066 11.0931 9.69347 10.7206 9.32222L1.65182 0.286953C1.03644 -0.325993 8.69435e-07 0.109636 8.32057e-07 0.96476Z" fill="currentColor"/>
                                                    </svg>
                                                </a>
                                            @else
                                                <span class="cs_pagination_arrow cs_pagination_arrow_right cs_center cs_accent_color disabled">
                                                    <svg width="11" height="20" viewBox="0 0 11 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M8.32057e-07 0.96476L4.21689e-08 19.0353C4.79022e-09 19.8904 1.03644 20.326 1.65182 19.7129L10.7206 10.6777C11.0931 10.3066 11.0931 9.69347 10.7206 9.32222L1.65182 0.286953C1.03644 -0.325993 8.69435e-07 0.109636 8.32057e-07 0.96476Z" fill="currentColor"/>
                                                    </svg>
                                                </span>
                                            @endif
                                        </li>
                                    </ul>
                                @endif
                            @else
                                <div class="cs_listing_empty_state wow fadeInUp">
                                    <h3 class="cs_fs_30 cs_semibold">No properties matched your filters.</h3>
                                    <p class="mb-0">Try a wider search, reset the price range, or switch between rent and sale listings.</p>
                                    <div class="cs_height_24 cs_height_lg_24"></div>
                                    <a href="{{ route('properties.index') }}" class="cs_btn cs_style_1 cs_accent_bg cs_white_color cs_radius_7">
                                        <span class="cs_btn_text">Clear Filters</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-xl-5 wow fadeInRight" id="listing-map">
                        <div class="cs_listing_map_box">
                            <iframe src="{{ $mapEmbedUrl }}" title="Bangladesh property map" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="cs_height_130 cs_height_lg_80"></div>
    </section>
@endsection
