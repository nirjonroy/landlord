@extends('layouts.frontend-auth')

@section('title', 'User Login | '.$siteName)

@section('auth_content')
    <h2 class="cs_fs_28 cs_semibold cs_body_font cs_mb_20 wow fadeInDown">Login To {{ $siteName }}</h2>
    <p class="cs_auth_intro">Access your account to manage your profile and continue using the land site.</p>

    @if (session('status'))
        <div class="cs_auth_notice cs_auth_notice_success">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="cs_auth_notice cs_auth_notice_error">
            Please review the highlighted login fields and try again.
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="cs_contact_form cs_row_gap_40 row cs_gap_y_20 cs_mb_20">
        @csrf

        <div class="col-sm-6">
            <label for="email">Email</label>
            <input type="email" name="email" class="cs_form_field cs_radius_7" id="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
            @error('email')
                <div class="cs_form_error">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-sm-6">
            <label for="password">Password</label>
            <input type="password" name="password" class="cs_form_field cs_radius_7" id="password" autocomplete="current-password" required>
            @error('password')
                <div class="cs_form_error">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-sm-6 d-flex align-items-end">
            <label for="remember" class="cs_auth_checkbox">
                <input type="checkbox" name="remember" id="remember" @checked(old('remember'))>
                <span>Remember me</span>
            </label>
        </div>

        <div class="col-sm-6 d-flex align-items-end justify-content-sm-end">
            <div class="cs_auth_links">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">Forgot your password?</a>
                @endif
                <a href="{{ route('admin.login') }}">Admin login</a>
            </div>
        </div>

        <div class="col-12">
            <div class="cs_auth_submit_row">
                <button type="submit" aria-label="Login button" class="cs_btn cs_style_1 cs_accent_bg cs_white_color cs_medium cs_radius_7">
                    <span>Login Account</span>
                </button>
                <div class="cs_auth_links">
                    <a href="{{ route('register') }}">Don't have an account? Register</a>
                </div>
            </div>
        </div>
    </form>
@endsection
