@php
    $defaultLocation = collect([$user->area_name, $user->district])->filter()->implode(', ');
@endphp

<div class="cs_profile cs_white_bg cs_radius_10">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 cs_mb_40">
        <div>
            <h2 class="cs_profile_title cs_fs_28 cs_semibold mb-2">Add New Property</h2>
            <p class="mb-0">Post a Bangladesh rent or sale property from your account. New submissions start with a pending review status.</p>
        </div>
        <span class="cs_inline_badge">Initial Status: Pending</span>
    </div>

    <form method="POST" action="{{ route('properties.store') }}" enctype="multipart/form-data" class="row cs_profile_form cs_row_gap_40 cs_gap_y_20">
        @csrf
        <input type="hidden" name="property_form" value="create">

        <div class="col-lg-12">
            <label for="property_title">Property Title</label>
            <input type="text" name="title" id="property_title" class="cs_form_field cs_radius_7 @error('title') is-invalid @enderror" value="{{ old('title', $user->home_name) }}" placeholder="Example: Dhanmondi Family Apartment" required>
            @error('title')<div class="cs_form_error">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label for="purpose">Listing Purpose</label>
            <select name="purpose" id="purpose" class="cs_form_field cs_radius_7 @error('purpose') is-invalid @enderror" required>
                <option value="">Select purpose</option>
                <option value="sale" @selected(old('purpose') === 'sale')>For Sale</option>
                <option value="rent" @selected(old('purpose') === 'rent')>For Rent</option>
            </select>
            @error('purpose')<div class="cs_form_error">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label for="property_type">Property Type</label>
            <select name="property_type" id="property_type" class="cs_form_field cs_radius_7 @error('property_type') is-invalid @enderror" required>
                <option value="">Select property type</option>
                <option value="Apartment" @selected(old('property_type', $user->home_type) === 'Apartment')>Apartment</option>
                <option value="House" @selected(old('property_type', $user->home_type) === 'House')>House</option>
                <option value="Duplex" @selected(old('property_type', $user->home_type) === 'Duplex')>Duplex</option>
                <option value="Office" @selected(old('property_type', $user->home_type) === 'Office')>Office</option>
                <option value="Shop" @selected(old('property_type', $user->home_type) === 'Shop')>Shop</option>
                <option value="Land" @selected(old('property_type', $user->home_type) === 'Land')>Land</option>
                <option value="Plot" @selected(old('property_type', $user->home_type) === 'Plot')>Plot</option>
                <option value="Commercial Space" @selected(old('property_type', $user->home_type) === 'Commercial Space')>Commercial Space</option>
            </select>
            @error('property_type')<div class="cs_form_error">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label for="price">Price (BDT)</label>
            <input type="number" name="price" id="price" min="0" step="1" class="cs_form_field cs_radius_7 @error('price') is-invalid @enderror" value="{{ old('price') }}" placeholder="45000" required>
            @error('price')<div class="cs_form_error">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label for="area_size">Area (sqft)</label>
            <input type="number" name="area_size" id="area_size" min="0" step="0.01" class="cs_form_field cs_radius_7 @error('area_size') is-invalid @enderror" value="{{ old('area_size') }}" placeholder="1450">
            @error('area_size')<div class="cs_form_error">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label for="contact_phone">Contact Phone</label>
            <input type="text" name="contact_phone" id="contact_phone" class="cs_form_field cs_radius_7 @error('contact_phone') is-invalid @enderror" value="{{ old('contact_phone', $user->phone) }}" placeholder="01XXXXXXXXX">
            @error('contact_phone')<div class="cs_form_error">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label for="bedrooms">Bedrooms</label>
            <input type="number" name="bedrooms" id="bedrooms" min="0" step="1" class="cs_form_field cs_radius_7 @error('bedrooms') is-invalid @enderror" value="{{ old('bedrooms') }}" placeholder="3">
            @error('bedrooms')<div class="cs_form_error">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label for="bathrooms">Bathrooms</label>
            <input type="number" name="bathrooms" id="bathrooms" min="0" step="1" class="cs_form_field cs_radius_7 @error('bathrooms') is-invalid @enderror" value="{{ old('bathrooms') }}" placeholder="2">
            @error('bathrooms')<div class="cs_form_error">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label for="garages">Garages / Parking</label>
            <input type="number" name="garages" id="garages" min="0" step="1" class="cs_form_field cs_radius_7 @error('garages') is-invalid @enderror" value="{{ old('garages') }}" placeholder="1">
            @error('garages')<div class="cs_form_error">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label for="location">Location Label</label>
            <input type="text" name="location" id="location" class="cs_form_field cs_radius_7 @error('location') is-invalid @enderror" value="{{ old('location', $defaultLocation) }}" placeholder="Dhanmondi, Dhaka" required>
            @error('location')<div class="cs_form_error">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-3">
            <label for="district_property">District</label>
            <input type="text" name="district" id="district_property" class="cs_form_field cs_radius_7 @error('district') is-invalid @enderror" value="{{ old('district', $user->district) }}" placeholder="Dhaka">
            @error('district')<div class="cs_form_error">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-3">
            <label for="division_property">Division</label>
            <input type="text" name="division" id="division_property" class="cs_form_field cs_radius_7 @error('division') is-invalid @enderror" value="{{ old('division', $user->division ?: 'Dhaka') }}" placeholder="Dhaka">
            @error('division')<div class="cs_form_error">{{ $message }}</div>@enderror
        </div>

        <div class="col-lg-12">
            <label for="property_address">Full Address</label>
            <textarea name="address" id="property_address" rows="4" class="cs_form_field cs_radius_7 @error('address') is-invalid @enderror" placeholder="House, road, area, thana, district">{{ old('address', $user->present_address) }}</textarea>
            @error('address')<div class="cs_form_error">{{ $message }}</div>@enderror
        </div>

        <div class="col-lg-12">
            <label for="property_description">Property Description</label>
            <textarea name="description" id="property_description" rows="5" class="cs_form_field cs_radius_7 @error('description') is-invalid @enderror" placeholder="Describe the home, access road, nearby facilities, and any landlord notes.">{{ old('description') }}</textarea>
            @error('description')<div class="cs_form_error">{{ $message }}</div>@enderror
        </div>

        <div class="col-lg-6">
            <div class="cs_file_card">
                <h3 class="cs_fs_20 cs_semibold cs_mb_15">Cover Image</h3>
                <input type="file" name="thumbnail_image" class="cs_form_field cs_radius_7 @error('thumbnail_image') is-invalid @enderror" accept=".jpg,.jpeg,.png,.webp">
                <p class="cs_form_hint">Upload one main image for the listing card.</p>
                @error('thumbnail_image')<div class="cs_form_error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="col-lg-6">
            <div class="cs_file_card">
                <h3 class="cs_fs_20 cs_semibold cs_mb_15">Gallery Images</h3>
                <input type="file" name="gallery_images[]" class="cs_form_field cs_radius_7 @error('gallery_images') is-invalid @enderror @error('gallery_images.*') is-invalid @enderror" accept=".jpg,.jpeg,.png,.webp" multiple>
                <p class="cs_form_hint">Upload up to 6 additional images for rooms, exterior, or land views.</p>
                @error('gallery_images')<div class="cs_form_error">{{ $message }}</div>@enderror
                @error('gallery_images.*')<div class="cs_form_error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="col-lg-12">
            <div class="cs_dashboard_note cs_mb_30">
                <strong>Review flow:</strong> Your property will be saved immediately and marked as <strong>Pending</strong> so it can appear in your dashboard and be reviewed later from the admin side.
            </div>
        </div>

        <div class="col-lg-12">
            <button type="submit" aria-label="Add property button" class="cs_btn cs_style_1 cs_accent_bg cs_white_color cs_medium cs_radius_7">
                <span>Submit Property</span>
            </button>
        </div>
    </form>
</div>
