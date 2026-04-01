<div class="cs_change_password">
    <div class="row cs_row_gap_40 cs_gap_y_30">
        <div class="col-lg-6 order-lg-2">
            <div class="cs_password_change_quote">
                Use a strong password before you start onboarding owners, listing homes, or uploading identity documents.
            </div>
        </div>
        <div class="col-lg-6 order-lg-1">
            <div class="cs_change_password_form_wraper cs_white_bg cs_radius_10">
                <h3 class="cs_fs_28 cs_semibold cs_mb_30 text-center">Change Password</h3>
                <form method="POST" action="{{ route('password.update') }}" class="row cs_gap_y_20">
                    @csrf
                    @method('PUT')

                    <div class="col-sm-12">
                        <label for="current_password">Current Password</label>
                        <input type="password" name="current_password" id="current_password" class="cs_form_field cs_radius_7">
                        @error('current_password', 'updatePassword')<div class="cs_form_error">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-sm-12">
                        <label for="password">New Password</label>
                        <input type="password" name="password" id="password" class="cs_form_field cs_radius_7">
                        @error('password', 'updatePassword')<div class="cs_form_error">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-sm-12">
                        <label for="password_confirmation">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="cs_form_field cs_radius_7">
                        @error('password_confirmation', 'updatePassword')<div class="cs_form_error">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-sm-12">
                        <button type="submit" aria-label="Update password button" class="cs_btn cs_style_1 cs_accent_bg cs_white_color cs_medium cs_radius_7">
                            <span>Update Password</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
