@extends('layouts.frontend-user')

@section('title', 'My Profile | '.$siteName)

@section('head_styles')
    <style>
        .cs_profile_form .cs_btn,
        .cs_change_password_form_wraper .cs_btn,
        .cs_danger_box .cs_btn {
            min-width: 200px;
            justify-content: center;
        }

        .cs_profile_gallery {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
        }

        .cs_profile_gallery_item {
            height: 160px;
            overflow: hidden;
            border-radius: 12px;
            background: #f8fafc;
        }

        .cs_profile_gallery_item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
@endsection

@section('content')
    @php
        $profilePhotoUrl = $profileFileUrls['profile_photo'] ?? asset('frontend-assets/img/team_1.jpg');
    @endphp

    <section class="cs_page_heading position-relative">
        <div class="container">
            <div class="cs_page_heading_content_wrapper cs_type_4 cs_heading_bg cs_bg_filed cs_center_column" data-src="{{ asset('frontend-assets/img/page_header_2.jpg') }}">
                <h1 class="cs_fs_48 cs_semibold cs_mb_7 wow zoomIn">Landlord Profile</h1>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" aria-label="Back to home button">Home</a></li>
                    <li class="breadcrumb-item active">{{ $user->name }}</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="cs_tabs cs_light_gray_bg">
        <div class="cs_height_130 cs_height_lg_80"></div>
        <div class="container">
            <div class="cs_profile_wrapper">
                @if (session('status') === 'profile-updated')
                    <div class="cs_status_alert cs_status_alert_success">Your landlord profile has been updated successfully.</div>
                @endif

                @if (session('status') === 'password-updated')
                    <div class="cs_status_alert cs_status_alert_success">Your password has been updated successfully.</div>
                @endif

                @if ($errors->any())
                    <div class="cs_status_alert cs_status_alert_error">Please review the highlighted fields before saving your profile.</div>
                @endif

                <div class="row cs_gap_y_30">
                    <div class="col-lg-3">
                        <div class="cs_profile_menu cs_white_bg cs_radius_10">
                            <div class="cs_center_column text-center cs_mb_40 cs_mb_lg_30">
                                <div class="cs_profile_avatar cs_mb_20">
                                    <img src="{{ $profilePhotoUrl }}" alt="{{ $user->name }}">
                                </div>
                                <h2 class="cs_profile_title cs_fs_24 cs_semibold mb-0">{{ $user->name }}</h2>
                                <p class="cs_profile_subtitle mb-0">{{ ucfirst(str_replace('-', ' ', $user->account_type ?: 'landlord')) }}</p>
                            </div>

                            <div class="cs_profile_summary_card">
                                <h3 class="cs_fs_20 cs_semibold">Profile Completion</h3>
                                <p class="cs_fs_28 cs_semibold cs_accent_color mb-1">{{ $profileCompletionPercent }}%</p>
                                <p class="mb-0">Complete your Bangladesh landlord details for faster trust and onboarding.</p>
                            </div>

                            <div class="cs_profile_summary_card">
                                <h3 class="cs_fs_20 cs_semibold">Verification Summary</h3>
                                <ul class="cs_profile_summary_meta">
                                    <li><span>Identity</span><strong>{{ $verificationSummary['identity_ready'] ? 'Ready' : 'Pending' }}</strong></li>
                                    <li><span>Ownership Proof</span><strong>{{ $verificationSummary['ownership_ready'] ? 'Ready' : 'Pending' }}</strong></li>
                                    <li><span>Home Gallery</span><strong>{{ $verificationSummary['gallery_count'] }} files</strong></li>
                                    <li><span>Primary Phone</span><strong>{{ $user->phone ?: 'Not set' }}</strong></li>
                                </ul>
                            </div>

                            <ul class="cs_tab_links cs_style_5 cs_mp_0">
                                <li class="active"><a href="#profile" aria-label="Tab button">Profile Details</a></li>
                                <li><a href="#verification" aria-label="Tab button">Verification</a></li>
                                <li><a href="#home_info" aria-label="Tab button">Home Info</a></li>
                                <li><a href="#password_update" aria-label="Tab button">Change Password</a></li>
                                <li><a href="#account_security" aria-label="Tab button">Account Security</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-9">
                        <div class="cs_tab_body">
                            <div class="cs_tab active" id="profile">
                                <div class="cs_profile cs_white_bg cs_radius_10">
                                    <h2 class="cs_profile_title cs_fs_28 cs_semibold cs_mb_40">Landlord Identity & Contact</h2>
                                    <div class="cs_avatar cs_style_2 cs_mb_40">
                                        <div class="cs_avatar_icon cs_center cs_radius_50 position-relative">
                                            <img src="{{ $profilePhotoUrl }}" alt="Profile Pic">
                                        </div>
                                        <div class="cs_avatar_info">
                                            <h2 class="cs_fs_24 cs_medium cs_mb_10">{{ $user->name }}</h2>
                                            <p class="cs_profile_subtitle mb-0">Use the details below to identify the landlord or property owner account.</p>
                                        </div>
                                    </div>
                                    @include('profile.partials.profile-details-form')
                                </div>
                            </div>

                            <div class="cs_tab" id="verification">
                                <div class="cs_profile cs_white_bg cs_radius_10">
                                    <h2 class="cs_profile_title cs_fs_28 cs_semibold cs_mb_40">Bangladesh Verification Documents</h2>
                                    @include('profile.partials.verification-form')
                                </div>
                            </div>

                            <div class="cs_tab" id="home_info">
                                <div class="cs_profile cs_white_bg cs_radius_10">
                                    <h2 class="cs_profile_title cs_fs_28 cs_semibold cs_mb_40">Home Information & Elevation Gallery</h2>
                                    @include('profile.partials.home-info-form')
                                </div>
                            </div>

                            <div class="cs_tab" id="password_update">
                                @include('profile.partials.password-form')
                            </div>

                            <div class="cs_tab" id="account_security">
                                @include('profile.partials.delete-account-form')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="cs_height_120 cs_height_lg_80"></div>
    </section>
@endsection
