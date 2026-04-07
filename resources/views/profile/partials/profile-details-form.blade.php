<form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="row cs_profile_form cs_row_gap_40 cs_gap_y_20">
    @csrf
    @method('PATCH')
    <input type="hidden" name="profile_section" value="profile_details">

    <div class="col-sm-6">
        <label for="name">Full Name</label>
        <input type="text" name="name" class="cs_form_field cs_radius_7" id="name" value="{{ old('name', $user->name) }}" required>
        @error('name')<div class="cs_form_error">{{ $message }}</div>@enderror
    </div>

    <div class="col-sm-6">
        <label for="account_type">Account Type</label>
        <select name="account_type" id="account_type" class="cs_form_field cs_radius_7">
            <option value="">Select type</option>
            <option value="landlord" @selected(old('account_type', $user->account_type) === 'landlord')>Landlord</option>
            <option value="owner" @selected(old('account_type', $user->account_type) === 'owner')>Owner</option>
            <option value="landlord-owner" @selected(old('account_type', $user->account_type) === 'landlord-owner')>Landlord & Owner</option>
        </select>
        @error('account_type')<div class="cs_form_error">{{ $message }}</div>@enderror
    </div>

    <div class="col-sm-6">
        <label for="email">Email</label>
        <input type="email" name="email" class="cs_form_field cs_radius_7" id="email" value="{{ old('email', $user->email) }}" required>
        @error('email')<div class="cs_form_error">{{ $message }}</div>@enderror
    </div>

    <div class="col-sm-6">
        <label for="phone">Primary Mobile</label>
        <input type="text" name="phone" class="cs_form_field cs_radius_7" id="phone" value="{{ old('phone', $user->phone) }}" placeholder="01XXXXXXXXX">
        @error('phone')<div class="cs_form_error">{{ $message }}</div>@enderror
    </div>

    <div class="col-sm-6">
        <label for="alternative_phone">Alternative Mobile</label>
        <input type="text" name="alternative_phone" class="cs_form_field cs_radius_7" id="alternative_phone" value="{{ old('alternative_phone', $user->alternative_phone) }}">
        @error('alternative_phone')<div class="cs_form_error">{{ $message }}</div>@enderror
    </div>

    <div class="col-sm-6">
        <label for="profession">Profession</label>
        <input type="text" name="profession" class="cs_form_field cs_radius_7" id="profession" value="{{ old('profession', $user->profession) }}" placeholder="Business, service, agriculture, etc.">
        @error('profession')<div class="cs_form_error">{{ $message }}</div>@enderror
    </div>

    <div class="col-sm-6">
        <label for="date_of_birth">Date of Birth</label>
        <input type="date" name="date_of_birth" class="cs_form_field cs_radius_7" id="date_of_birth" value="{{ old('date_of_birth', optional($user->date_of_birth)->format('Y-m-d')) }}">
        @error('date_of_birth')<div class="cs_form_error">{{ $message }}</div>@enderror
    </div>

    <div class="col-sm-6">
        <label for="gender">Gender</label>
        <select name="gender" id="gender" class="cs_form_field cs_radius_7">
            <option value="">Select gender</option>
            <option value="male" @selected(old('gender', $user->gender) === 'male')>Male</option>
            <option value="female" @selected(old('gender', $user->gender) === 'female')>Female</option>
            <option value="other" @selected(old('gender', $user->gender) === 'other')>Other</option>
        </select>
        @error('gender')<div class="cs_form_error">{{ $message }}</div>@enderror
    </div>

    <div class="col-sm-6">
        <label for="emergency_contact_name">Emergency Contact Name</label>
        <input type="text" name="emergency_contact_name" class="cs_form_field cs_radius_7" id="emergency_contact_name" value="{{ old('emergency_contact_name', $user->emergency_contact_name) }}">
        @error('emergency_contact_name')<div class="cs_form_error">{{ $message }}</div>@enderror
    </div>

    <div class="col-sm-6">
        <label for="emergency_contact_phone">Emergency Contact Phone</label>
        <input type="text" name="emergency_contact_phone" class="cs_form_field cs_radius_7" id="emergency_contact_phone" value="{{ old('emergency_contact_phone', $user->emergency_contact_phone) }}">
        @error('emergency_contact_phone')<div class="cs_form_error">{{ $message }}</div>@enderror
    </div>

    <div class="col-lg-12">
        <label for="bio">Profile Summary</label>
        <textarea name="bio" id="bio" rows="5" class="cs_form_field cs_radius_7" placeholder="Describe your ownership background, number of homes, and operating area in Bangladesh.">{{ old('bio', $user->bio) }}</textarea>
        @error('bio')<div class="cs_form_error">{{ $message }}</div>@enderror
    </div>

    <div class="col-lg-12">
        <div class="cs_file_card">
            <h3 class="cs_fs_20 cs_semibold cs_mb_15">Profile Photo</h3>
            <div class="cs_file_preview">
                <img src="{{ $profilePhotoUrl }}" alt="Profile Photo">
            </div>
            <input type="file" name="profile_photo" class="cs_form_field cs_radius_7" accept=".jpg,.jpeg,.png,.webp">
            <p class="cs_form_hint">Upload a clear face photo for landlord or owner verification.</p>
            <div class="cs_file_actions">
                <label class="cs_remove_check">
                    <input type="checkbox" name="remove_profile_photo" value="1">
                    <span>Remove current photo</span>
                </label>
            </div>
            @error('profile_photo')<div class="cs_form_error">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="col-lg-12">
        <button type="submit" aria-label="Submit button" class="cs_btn cs_style_1 cs_accent_bg cs_white_color cs_medium cs_radius_7">
            <span>Update Profile</span>
        </button>
    </div>
</form>
