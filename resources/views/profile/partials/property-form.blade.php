@php
    $editing = isset($property) && $property !== null;
    $defaultLocation = collect([$user->area_name, $user->district])->filter()->implode(', ');
    $selectedPropertyType = old('property_type', $editing ? $property->property_type : $user->home_type);
@endphp

<form method="POST" action="{{ $formAction }}" enctype="multipart/form-data" class="row cs_profile_form cs_row_gap_40 cs_gap_y_20">
    @csrf
    @if (($formMethod ?? 'POST') !== 'POST')
        @method($formMethod)
    @endif

    <input type="hidden" name="property_form" value="{{ $editing ? 'edit' : 'create' }}">

    <div class="col-lg-12">
        <label for="property_title">Property Title</label>
        <input type="text" name="title" id="property_title" class="cs_form_field cs_radius_7 @error('title') is-invalid @enderror" value="{{ old('title', $editing ? $property->title : $user->home_name) }}" placeholder="Example: Dhanmondi Family Apartment" required>
        @error('title')<div class="cs_form_error">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label for="purpose">Listing Purpose</label>
        <select name="purpose" id="purpose" class="cs_form_field cs_radius_7 @error('purpose') is-invalid @enderror" required>
            <option value="">Select purpose</option>
            <option value="sale" @selected(old('purpose', $editing ? $property->purpose : null) === 'sale')>For Sale</option>
            <option value="rent" @selected(old('purpose', $editing ? $property->purpose : null) === 'rent')>For Rent</option>
        </select>
        @error('purpose')<div class="cs_form_error">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label for="property_type">Property Type</label>
        <select name="property_type" id="property_type" class="cs_form_field cs_radius_7 @error('property_type') is-invalid @enderror" required>
            <option value="">Select property type</option>
            @if ($selectedPropertyType && ! $propertyTypes->contains(fn ($propertyType) => $propertyType->filter_value === $selectedPropertyType))
                <option value="{{ $selectedPropertyType }}" selected>{{ $selectedPropertyType }}</option>
            @endif
            @foreach ($propertyTypes as $propertyType)
                <option value="{{ $propertyType->filter_value }}" @selected($selectedPropertyType === $propertyType->filter_value)>{{ $propertyType->name }}</option>
            @endforeach
        </select>
        @error('property_type')<div class="cs_form_error">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label for="price">Price (BDT)</label>
        <input type="number" name="price" id="price" min="0" step="1" class="cs_form_field cs_radius_7 @error('price') is-invalid @enderror" value="{{ old('price', $editing ? $property->price : null) }}" placeholder="45000" required>
        @error('price')<div class="cs_form_error">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label for="area_size">Area (sqft)</label>
        <input type="number" name="area_size" id="area_size" min="0" step="0.01" class="cs_form_field cs_radius_7 @error('area_size') is-invalid @enderror" value="{{ old('area_size', $editing ? $property->area_size : null) }}" placeholder="1450">
        @error('area_size')<div class="cs_form_error">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label for="contact_phone">Contact Phone</label>
        <input type="text" name="contact_phone" id="contact_phone" class="cs_form_field cs_radius_7 @error('contact_phone') is-invalid @enderror" value="{{ old('contact_phone', $editing ? $property->contact_phone : $user->phone) }}" placeholder="01XXXXXXXXX">
        @error('contact_phone')<div class="cs_form_error">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label for="bedrooms">Bedrooms</label>
        <input type="number" name="bedrooms" id="bedrooms" min="0" step="1" class="cs_form_field cs_radius_7 @error('bedrooms') is-invalid @enderror" value="{{ old('bedrooms', $editing ? $property->bedrooms : null) }}" placeholder="3">
        @error('bedrooms')<div class="cs_form_error">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label for="bathrooms">Bathrooms</label>
        <input type="number" name="bathrooms" id="bathrooms" min="0" step="1" class="cs_form_field cs_radius_7 @error('bathrooms') is-invalid @enderror" value="{{ old('bathrooms', $editing ? $property->bathrooms : null) }}" placeholder="2">
        @error('bathrooms')<div class="cs_form_error">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label for="garages">Garages / Parking</label>
        <input type="number" name="garages" id="garages" min="0" step="1" class="cs_form_field cs_radius_7 @error('garages') is-invalid @enderror" value="{{ old('garages', $editing ? $property->garages : null) }}" placeholder="1">
        @error('garages')<div class="cs_form_error">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label for="location">Location Label</label>
        <input type="text" name="location" id="location" class="cs_form_field cs_radius_7 @error('location') is-invalid @enderror" value="{{ old('location', $editing ? $property->location : $defaultLocation) }}" placeholder="Dhanmondi, Dhaka" required>
        @error('location')<div class="cs_form_error">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label for="district_property">District</label>
        <input type="text" name="district" id="district_property" class="cs_form_field cs_radius_7 @error('district') is-invalid @enderror" value="{{ old('district', $editing ? $property->district : $user->district) }}" placeholder="Dhaka">
        @error('district')<div class="cs_form_error">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label for="division_property">Division</label>
        <input type="text" name="division" id="division_property" class="cs_form_field cs_radius_7 @error('division') is-invalid @enderror" value="{{ old('division', $editing ? $property->division : ($user->division ?: 'Dhaka')) }}" placeholder="Dhaka">
        @error('division')<div class="cs_form_error">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label for="postal_code_property">ZIP / Postal Code</label>
        <input type="text" name="postal_code" id="postal_code_property" class="cs_form_field cs_radius_7 @error('postal_code') is-invalid @enderror" value="{{ old('postal_code', $editing ? $property->postal_code : $user->postal_code) }}" placeholder="1209">
        @error('postal_code')<div class="cs_form_error">{{ $message }}</div>@enderror
    </div>

    <div class="col-lg-12">
        <label for="property_address">Full Address</label>
        <textarea name="address" id="property_address" rows="4" class="cs_form_field cs_radius_7 @error('address') is-invalid @enderror" placeholder="House, road, area, thana, district">{{ old('address', $editing ? $property->address : $user->present_address) }}</textarea>
        @error('address')<div class="cs_form_error">{{ $message }}</div>@enderror
    </div>

    <div class="col-lg-12">
        <label for="property_description">Property Description</label>
        <textarea name="description" id="property_description" rows="5" class="cs_form_field cs_radius_7 @error('description') is-invalid @enderror" placeholder="Describe the home, access road, nearby facilities, and any landlord notes.">{{ old('description', $editing ? $property->description : null) }}</textarea>
        @error('description')<div class="cs_form_error">{{ $message }}</div>@enderror
    </div>

    <div class="col-lg-6">
        <div class="cs_file_card">
            <h3 class="cs_fs_20 cs_semibold cs_mb_15">Cover Image</h3>
            @if ($editing && $propertyThumbnailUrl)
                <div class="cs_file_preview">
                    <img src="{{ $propertyThumbnailUrl }}" alt="Current cover image for {{ $property->title }}">
                </div>
                <label class="cs_remove_check">
                    <input type="checkbox" name="remove_thumbnail_image" value="1" @checked(old('remove_thumbnail_image'))>
                    Remove current cover image
                </label>
            @endif
            <input type="file" name="thumbnail_image" class="cs_form_field cs_radius_7 @error('thumbnail_image') is-invalid @enderror" accept=".jpg,.jpeg,.png,.webp">
            <p class="cs_form_hint">{{ $editing ? 'Upload a new cover image to replace the current one.' : 'Upload one main image for the listing card.' }}</p>
            @error('thumbnail_image')<div class="cs_form_error">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="col-lg-6">
        <div class="cs_file_card">
            <h3 class="cs_fs_20 cs_semibold cs_mb_15">Gallery Images</h3>
            @if ($editing && count($propertyGalleryUrls) > 0)
                <div class="cs_profile_gallery cs_mb_20">
                    @foreach ($propertyGalleryUrls as $galleryUrl)
                        <div class="cs_profile_gallery_item">
                            <img src="{{ $galleryUrl }}" alt="Current property gallery image">
                        </div>
                    @endforeach
                </div>
                <label class="cs_remove_check cs_mb_15">
                    <input type="checkbox" name="reset_gallery_images" value="1" @checked(old('reset_gallery_images'))>
                    Remove current gallery
                </label>
            @endif
            <input type="file" name="gallery_images[]" class="cs_form_field cs_radius_7 @error('gallery_images') is-invalid @enderror @error('gallery_images.*') is-invalid @enderror" accept=".jpg,.jpeg,.png,.webp" multiple>
            <p class="cs_form_hint">{{ $editing ? 'Upload up to 6 new gallery images. New uploads replace the current gallery.' : 'Upload up to 6 additional images for rooms, exterior, or land views.' }}</p>
            @error('gallery_images')<div class="cs_form_error">{{ $message }}</div>@enderror
            @error('gallery_images.*')<div class="cs_form_error">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="col-lg-12">
        <div class="cs_dashboard_note cs_mb_30">
            <strong>Review flow:</strong> {{ $reviewFlowMessage }}
        </div>
    </div>

    <div class="col-lg-12">
        <button type="submit" aria-label="{{ $submitLabel }}" class="cs_btn cs_style_1 cs_accent_bg cs_white_color cs_medium cs_radius_7">
            <span>{{ $submitLabel }}</span>
        </button>
    </div>
</form>
