@extends('layouts.frontend-auth')

@section('title', 'Create Account | '.$siteName)

@section('auth_content')
    <h2 class="cs_fs_28 cs_semibold cs_body_font cs_mb_20 wow fadeInDown">Create Account On {{ $siteName }}</h2>
    <p class="cs_auth_intro">Register as a user to browse land listings, manage your profile, and access the app later.</p>

    @if ($errors->any())
        <div class="cs_auth_notice cs_auth_notice_error">
            Please review the highlighted registration fields and submit the form again.
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="cs_contact_form cs_row_gap_40 row cs_gap_y_20 cs_mb_20">
        @csrf

        <div class="col-sm-6">
            <label for="name">Full Name</label>
            <input type="text" name="name" class="cs_form_field cs_radius_7" id="name" value="{{ old('name') }}" autocomplete="name" required autofocus>
            @error('name')
                <div class="cs_form_error">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-sm-6">
            <label for="email">Email</label>
            <input type="email" name="email" class="cs_form_field cs_radius_7" id="email" value="{{ old('email') }}" autocomplete="username" required>
            @error('email')
                <div class="cs_form_error">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-sm-6">
            <label for="password">Password</label>
            <input type="password" name="password" class="cs_form_field cs_radius_7" id="password" autocomplete="new-password" required>
            @error('password')
                <div class="cs_form_error">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-sm-6">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" name="password_confirmation" class="cs_form_field cs_radius_7" id="password_confirmation" autocomplete="new-password" required>
            @error('password_confirmation')
                <div class="cs_form_error">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12">
            <div class="cs_auth_submit_row">
                <button type="submit" aria-label="Register button" class="cs_btn cs_style_1 cs_accent_bg cs_white_color cs_medium cs_radius_7">
                    <span>Create Account</span>
                </button>
                <div class="cs_auth_links">
                    <a href="{{ route('login') }}">Already have an account? Login</a>
                    <a href="{{ route('admin.login') }}">Admin login</a>
                </div>
            </div>
        </div>
    </form>
@endsection
