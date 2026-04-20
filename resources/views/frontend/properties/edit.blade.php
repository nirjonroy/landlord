@extends('layouts.frontend-user')

@section('title', 'Edit Property | '.$siteName)

@section('head_styles')
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

        .cs_property_edit_note {
            padding: 18px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
        }

        .cs_property_edit_note_warning {
            color: #92400e;
            background: #fef3c7;
            border: 1px solid #fde68a;
        }

        .cs_property_edit_note_danger {
            color: #991b1b;
            background: #fee2e2;
            border: 1px solid #fecaca;
        }

        .cs_property_edit_actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }
    </style>
@endsection

@section('content')
    <section class="cs_light_gray_bg">
        <div class="cs_height_60 cs_height_lg_50"></div>
        <div class="container">
            <ol class="breadcrumb cs_mb_30">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('profile.edit', ['tab' => 'my_property']) }}#my_property">My Property</a></li>
                <li class="breadcrumb-item"><a href="{{ route('properties.show', $property) }}">{{ $property->title }}</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>

            @if ($errors->any())
                <div class="cs_status_alert cs_status_alert_error">Please review the highlighted property fields before saving your changes.</div>
            @endif

            <div class="cs_profile cs_white_bg cs_radius_10">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 cs_mb_30">
                    <div>
                        <h1 class="cs_profile_title cs_fs_28 cs_semibold mb-2">Edit Property</h1>
                        <p class="mb-0">Update any listing field here. Saving changes will send the property back to admin review.</p>
                    </div>
                    <div class="cs_property_edit_actions">
                        <a href="{{ route('properties.show', $property) }}" class="cs_btn cs_style_1 cs_type_1 cs_accent_color cs_medium cs_radius_7">
                            <span>Back to Details</span>
                        </a>
                        <a href="{{ route('profile.edit', ['tab' => 'my_property']) }}#my_property" class="cs_btn cs_style_1 cs_type_1 cs_accent_color cs_medium cs_radius_7">
                            <span>Back to My Property</span>
                        </a>
                    </div>
                </div>

                <div class="cs_property_status_badges">
                    <span class="cs_property_status_badge cs_property_status_badge_{{ $reviewStatusTone }}">Current Review: {{ $reviewStatusLabel }}</span>
                    <span class="cs_property_status_badge cs_property_status_badge_{{ $availabilityTone }}">Market: {{ $availabilityLabel }}</span>
                </div>

                @if ($property->status === 'rejected' && $property->review_note)
                    <div class="cs_property_edit_note cs_property_edit_note_danger">
                        <strong>Previous admin note:</strong> {{ $property->review_note }}
                    </div>
                @elseif ($property->status === 'approved')
                    <div class="cs_property_edit_note cs_property_edit_note_warning">
                        <strong>Approval reset:</strong> This property is currently approved, but once you save changes it will move back to pending until admin reviews it again.
                    </div>
                @endif

                @if (! $subscriptionSummary['has_active_subscription'])
                    <div class="cs_property_edit_note cs_property_edit_note_danger">
                        <strong>Subscription required:</strong> You can review this property, but saving changes requires an active subscription package on your account.
                    </div>
                @endif

                @include('profile.partials.property-form', [
                    'formAction' => route('properties.update', $property),
                    'formMethod' => 'PUT',
                    'submitLabel' => 'Save Changes',
                    'property' => $property,
                    'propertyThumbnailUrl' => $propertyThumbnailUrl,
                    'propertyGalleryUrls' => $propertyGalleryUrls,
                    'reviewFlowMessage' => 'After you save changes, this property will return to Pending and must be approved again before it can go live publicly.',
                ])
            </div>
        </div>
        <div class="cs_height_120 cs_height_lg_80"></div>
    </section>
@endsection
