@extends('layouts.frontend-public')

@section('title', $property->title.' | '.$siteName)
@section('meta_description', $property->description ?: ('View full property details for '.$property->title.' on '.$siteName.'.'))

@push('styles')
    <style>
        .cs_property_status_badges {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 24px;
        }

        .cs_property_status_badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 16px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 700;
            line-height: 1;
        }

        .cs_property_status_badge_success {
            color: #166534;
            background: #dcfce7;
        }

        .cs_property_status_badge_warning {
            color: #92400e;
            background: #fef3c7;
        }

        .cs_property_status_badge_danger {
            color: #991b1b;
            background: #fee2e2;
        }

        .cs_property_status_badge_neutral {
            color: #0f172a;
            background: #e2e8f0;
        }

        .cs_property_detail_panel {
            padding: 32px;
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
            margin-bottom: 24px;
        }

        .cs_property_detail_grid {
            display: grid;
            gap: 18px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .cs_property_detail_item {
            padding: 18px 20px;
            border-radius: 14px;
            background: #f8fafc;
            border: 1px solid rgba(15, 23, 42, 0.08);
        }

        .cs_property_detail_item span {
            display: block;
            margin-bottom: 6px;
            color: #64748b;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .cs_property_description_box {
            padding: 32px;
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
        }

        .cs_property_management_card {
            padding: 28px;
            border-radius: 18px;
            background: #f8fafc;
            border: 1px solid rgba(15, 23, 42, 0.08);
        }

        .cs_property_management_actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .cs_property_gallery_thumb {
            border-radius: 15px;
            overflow: hidden;
        }

        .cs_property_gallery_thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .cs_property_note {
            padding: 18px 20px;
            border-radius: 14px;
            margin-top: 20px;
        }

        .cs_property_note_warning {
            background: #fef3c7;
            color: #92400e;
        }

        .cs_property_note_danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .cs_property_note_success {
            background: #dcfce7;
            color: #166534;
        }

        @media (max-width: 767px) {
            .cs_property_detail_grid {
                grid-template-columns: 1fr;
            }

            .cs_property_detail_panel,
            .cs_property_description_box,
            .cs_property_management_card {
                padding: 24px 20px;
            }

            .cs_property_management_actions .cs_btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
@endpush

@section('content')
    <section>
        <div class="cs_height_50 cs_height_lg_50"></div>
        <div class="container">
            <ol class="breadcrumb cs_mb_37">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('properties.index') }}">Listing</a></li>
                <li class="breadcrumb-item active">{{ $property->title }}</li>
            </ol>

            @if (session('status') === 'property-availability-updated')
                <div class="cs_property_note cs_property_note_success">The market availability was updated successfully.</div>
            @endif

            <div class="cs_property_header cs_mb_43">
                <div class="cs_header_text">
                    <h1 class="cs_fs_48 cs_semibold cs_black_color cs_mb_5 wow fadeInDown">{{ $property->title }}</h1>
                    <p class="cs_card_subtitle cs_fs_14 mb-0">
                        <i class="fa-solid fa-location-dot"></i> {{ $propertyLocation }}
                    </p>
                </div>
                <div class="cs_btns_wrapper wow fadeInRight">
                    <a href="{{ route('properties.index') }}" class="cs_action_btn cs_center cs_radius_5" aria-label="Back to listings">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <a href="{{ route('contact', ['property' => $property->title]) }}" class="cs_action_btn cs_center cs_radius_5" aria-label="Contact about property">
                        <i class="fa-solid fa-envelope"></i>
                    </a>
                    @if ($adminCanManage)
                        <a href="{{ route('admin.properties.show', $property) }}" class="cs_action_btn cs_center cs_radius_5" aria-label="Open admin property view">
                            <i class="fa-solid fa-shield"></i>
                        </a>
                    @endif
                </div>
            </div>

            <div class="cs_property_status_badges">
                <span class="cs_property_status_badge cs_property_status_badge_{{ $reviewStatusTone }}">Review: {{ $reviewStatusLabel }}</span>
                <span class="cs_property_status_badge cs_property_status_badge_{{ $availabilityTone }}">Market: {{ $availabilityLabel }}</span>
                <span class="cs_property_status_badge cs_property_status_badge_neutral">{{ $propertyPurposeLabel }}</span>
            </div>

            <div class="cs_single_property_slider_wrapper">
                <div class="cs_single_property_slider_2 cs_mb_20 wow fadeInDown">
                    @foreach ($galleryItems as $galleryItem)
                        <div class="cs_property_gallery_thumb">
                            <img src="{{ $galleryItem['url'] }}" alt="{{ $galleryItem['label'] }}">
                        </div>
                    @endforeach
                </div>
                @if (count($galleryItems) > 1)
                    <div class="cs_single_property_nav_2 wow fadeInUp">
                        @foreach ($galleryItems as $galleryItem)
                            <div class="cs_property_gallery_thumb">
                                <img src="{{ $galleryItem['url'] }}" alt="{{ $galleryItem['label'] }}">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="cs_height_40 cs_height_lg_30"></div>

            <div class="row cs_gap_y_30">
                <div class="col-xl-8">
                    <div class="cs_property_detail_panel wow fadeInLeft">
                        <div class="cs_property_info_wrapper mb-0">
                            <ul class="cs_card_features_list cs_mp_0 cs_radius_15 cs_mb_30">
                                <li><p class="mb-0">Bedrooms</p><span class="cs_accent_color">{{ $property->bedrooms ?? 0 }}</span></li>
                                <li><p class="mb-0">Bathrooms</p><span class="cs_accent_color">{{ $property->bathrooms ?? 0 }}</span></li>
                                <li><p class="mb-0">Garage</p><span class="cs_accent_color">{{ $property->garages ?? 0 }}</span></li>
                                <li><p class="mb-0">Size</p><span class="cs_accent_color">{{ $property->area_size ? number_format((float) $property->area_size).' sqft' : 'On request' }}</span></li>
                            </ul>
                            <div class="cs_card_btns_wrapper cs_radius_15">
                                <div class="cs_card_price cs_radius_10">
                                    <div class="cs_price_btn_text">
                                        <h2 class="cs_card_price_for cs_fs_24 cs_normal cs_body_font cs_mb_5">{{ $propertyPurposeLabel }}</h2>
                                        <h3 class="cs_card_price_value cs_fs_36 cs_semibold cs_body_font mb-0">{{ $propertyPriceLabel }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="cs_property_description_box wow fadeInUp">
                        <h3 class="cs_fs_24 cs_semibold cs_body_font cs_mb_25">Full Property Details</h3>
                        <div class="cs_property_detail_grid cs_mb_30">
                            <div class="cs_property_detail_item">
                                <span>Property Type</span>
                                <strong>{{ $property->property_type }}</strong>
                            </div>
                            <div class="cs_property_detail_item">
                                <span>ZIP Code</span>
                                <strong>{{ $property->postal_code ?: 'Not added yet' }}</strong>
                            </div>
                            <div class="cs_property_detail_item">
                                <span>District</span>
                                <strong>{{ $property->district ?: 'Not added yet' }}</strong>
                            </div>
                            <div class="cs_property_detail_item">
                                <span>Division</span>
                                <strong>{{ $property->division ?: 'Not added yet' }}</strong>
                            </div>
                            <div class="cs_property_detail_item">
                                <span>Address</span>
                                <strong>{{ $property->address ?: 'Not added yet' }}</strong>
                            </div>
                            <div class="cs_property_detail_item">
                                <span>Contact Phone</span>
                                <strong>{{ $property->contact_phone ?: 'Not added yet' }}</strong>
                            </div>
                        </div>

                        <h3 class="cs_fs_24 cs_semibold cs_body_font cs_mb_20">Description</h3>
                        <p class="mb-0">{{ $property->description ?: 'No description was added for this property yet.' }}</p>

                        @if ($property->status === 'rejected' && $property->review_note)
                            <div class="cs_property_note cs_property_note_danger">
                                <strong>Admin note:</strong> {{ $property->review_note }}
                            </div>
                        @elseif ($property->status === 'approved' && $property->review_note)
                            <div class="cs_property_note cs_property_note_success">
                                <strong>Admin note:</strong> {{ $property->review_note }}
                            </div>
                        @elseif ($property->status === 'pending')
                            <div class="cs_property_note cs_property_note_warning">
                                <strong>Review in progress:</strong> This listing is waiting for admin approval before it appears in the public marketplace.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="cs_sidebar cs_style_1">
                        <div class="cs_property_management_card wow fadeInRight" id="management-panel">
                            <h3 class="cs_fs_24 cs_semibold cs_mb_15">Owner & Status</h3>
                            <p class="mb-2"><strong>Owner:</strong> {{ $property->user?->name ?: 'Unknown owner' }}</p>
                            <p class="mb-2"><strong>Email:</strong> {{ $property->user?->email ?: 'Not available' }}</p>
                            <p class="mb-4"><strong>Market:</strong> {{ $availabilityLabel }}</p>

                            @if ($ownerCanManage)
                                <h4 class="cs_fs_20 cs_semibold cs_mb_15">Update availability</h4>
                                <div class="cs_property_management_actions">
                                    @foreach ($availabilityOptions as $value => $label)
                                        <form method="POST" action="{{ route('properties.availability.update', $property) }}">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="availability_status" value="{{ $value }}">
                                            <button type="submit" class="cs_btn {{ $property->availability_status === $value ? 'cs_style_1 cs_accent_bg cs_white_color' : 'cs_style_1 cs_type_1 cs_accent_color' }} cs_radius_7">
                                                <span>{{ $label }}</span>
                                            </button>
                                        </form>
                                    @endforeach
                                </div>
                            @endif

                            @if ($adminCanManage)
                                <div class="cs_height_20 cs_height_lg_20"></div>
                                <a href="{{ route('admin.properties.show', $property) }}" class="cs_btn cs_style_1 cs_type_1 cs_accent_color cs_radius_7">
                                    <span>Open Admin Review</span>
                                </a>
                            @endif
                        </div>

                        <div class="cs_property_management_card wow fadeInRight">
                            <h3 class="cs_fs_24 cs_semibold cs_mb_15">Map & Contact</h3>
                            <iframe src="{{ $mapEmbedUrl }}" title="Property location map" loading="lazy" style="width: 100%; min-height: 260px; border: 0; border-radius: 14px;"></iframe>
                            <div class="cs_height_20 cs_height_lg_20"></div>
                            <a href="{{ route('contact', ['property' => $property->title]) }}" class="cs_btn cs_style_1 cs_accent_bg cs_white_color cs_radius_7 w-100 justify-content-center">
                                <span>Contact About This Property</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="cs_height_120 cs_height_lg_80"></div>
    </section>
@endsection
