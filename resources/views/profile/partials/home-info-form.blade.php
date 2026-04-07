<form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="row cs_profile_form cs_row_gap_40 cs_gap_y_20">
    @csrf
    @method('PATCH')
    <input type="hidden" name="profile_section" value="home_info">

    <div class="col-sm-6">
        <label for="home_name">Home / Building Name</label>
        <input type="text" name="home_name" class="cs_form_field cs_radius_7" id="home_name" value="{{ old('home_name', $user->home_name) }}" placeholder="Example: Rahman Villa">
        @error('home_name')<div class="cs_form_error">{{ $message }}</div>@enderror
    </div>

    <div class="col-sm-6">
        <label for="home_type">Home Type</label>
        <input type="text" name="home_type" class="cs_form_field cs_radius_7" id="home_type" value="{{ old('home_type', $user->home_type) }}" placeholder="Apartment, duplex, tin-shed, etc.">
        @error('home_type')<div class="cs_form_error">{{ $message }}</div>@enderror
    </div>

    <div class="col-lg-12">
        <label for="present_address">Present Address</label>
        <textarea name="present_address" id="present_address" rows="4" class="cs_form_field cs_radius_7">{{ old('present_address', $user->present_address) }}</textarea>
        @error('present_address')<div class="cs_form_error">{{ $message }}</div>@enderror
    </div>

    <div class="col-lg-12">
        <label for="permanent_address">Permanent Address</label>
        <textarea name="permanent_address" id="permanent_address" rows="4" class="cs_form_field cs_radius_7">{{ old('permanent_address', $user->permanent_address) }}</textarea>
        @error('permanent_address')<div class="cs_form_error">{{ $message }}</div>@enderror
    </div>

    <div class="col-sm-6">
        <label for="area_name">Area / Road / Village</label>
        <input type="text" name="area_name" class="cs_form_field cs_radius_7" id="area_name" value="{{ old('area_name', $user->area_name) }}">
        @error('area_name')<div class="cs_form_error">{{ $message }}</div>@enderror
    </div>

    <div class="col-sm-6">
        <label for="post_office">Post Office</label>
        <input type="text" name="post_office" class="cs_form_field cs_radius_7" id="post_office" value="{{ old('post_office', $user->post_office) }}">
        @error('post_office')<div class="cs_form_error">{{ $message }}</div>@enderror
    </div>

    <div class="col-sm-4">
        <label for="postal_code">Postal Code</label>
        <input type="text" name="postal_code" class="cs_form_field cs_radius_7" id="postal_code" value="{{ old('postal_code', $user->postal_code) }}">
        @error('postal_code')<div class="cs_form_error">{{ $message }}</div>@enderror
    </div>

    <div class="col-sm-4">
        <label for="upazila">Upazila / Thana</label>
        <input type="text" name="upazila" class="cs_form_field cs_radius_7" id="upazila" value="{{ old('upazila', $user->upazila) }}">
        @error('upazila')<div class="cs_form_error">{{ $message }}</div>@enderror
    </div>

    <div class="col-sm-4">
        <label for="district">District</label>
        <input type="text" name="district" class="cs_form_field cs_radius_7" id="district" value="{{ old('district', $user->district) }}">
        @error('district')<div class="cs_form_error">{{ $message }}</div>@enderror
    </div>

    <div class="col-sm-6">
        <label for="division">Division</label>
        <input type="text" name="division" class="cs_form_field cs_radius_7" id="division" value="{{ old('division', $user->division) }}">
        @error('division')<div class="cs_form_error">{{ $message }}</div>@enderror
    </div>

    <div class="col-sm-6">
        <label for="country">Country</label>
        <input type="text" name="country" class="cs_form_field cs_radius_7" id="country" value="{{ old('country', $user->country ?: 'Bangladesh') }}">
        @error('country')<div class="cs_form_error">{{ $message }}</div>@enderror
    </div>

    <div class="col-lg-12">
        <div class="cs_file_card">
            <h3 class="cs_fs_20 cs_semibold cs_mb_15">Home Elevation Images</h3>
            @if (count($homeElevationFileUrls))
                <div class="cs_profile_gallery cs_mb_20">
                    @foreach ($homeElevationFileUrls as $galleryImage)
                        <div class="cs_profile_gallery_item">
                            <img src="{{ $galleryImage }}" alt="Home Elevation Image">
                        </div>
                    @endforeach
                </div>
            @endif
            <input type="file" name="home_elevation_images[]" class="cs_form_field cs_radius_7" accept=".jpg,.jpeg,.png,.webp" multiple>
            <p class="cs_form_hint">Upload up to 6 front, side, or elevation photos. New uploads replace the current gallery.</p>
            <div class="cs_file_actions">
                <label class="cs_remove_check">
                    <input type="checkbox" name="reset_home_elevation_images" value="1">
                    <span>Remove current gallery</span>
                </label>
            </div>
            @error('home_elevation_images')<div class="cs_form_error">{{ $message }}</div>@enderror
            @error('home_elevation_images.*')<div class="cs_form_error">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="col-lg-12">
        <button type="submit" aria-label="Save home info button" class="cs_btn cs_style_1 cs_accent_bg cs_white_color cs_medium cs_radius_7">
            <span>Save Home Information</span>
        </button>
    </div>
</form>
