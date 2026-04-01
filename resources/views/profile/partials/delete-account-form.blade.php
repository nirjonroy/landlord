<div class="cs_profile cs_white_bg cs_radius_10">
    <h2 class="cs_profile_title cs_fs_28 cs_semibold cs_mb_40">Account Security</h2>
    <div class="cs_danger_box">
        <h3 class="cs_fs_24 cs_semibold cs_mb_15">Delete Account</h3>
        <p class="cs_mb_20">This permanently removes your user record and all uploaded landlord documents from the application.</p>
        <form method="POST" action="{{ route('profile.destroy') }}" class="row cs_gap_y_20">
            @csrf
            @method('DELETE')

            <div class="col-lg-8">
                <label for="delete_password">Confirm Password</label>
                <input type="password" name="password" id="delete_password" class="cs_form_field cs_radius_7">
                @error('password', 'userDeletion')<div class="cs_form_error">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-12">
                <button type="submit" aria-label="Delete account button" class="cs_btn cs_style_1 cs_accent_bg cs_white_color cs_medium cs_radius_7">
                    <span>Delete Account</span>
                </button>
            </div>
        </form>
    </div>
</div>
