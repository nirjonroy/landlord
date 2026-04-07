<form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="row cs_profile_form cs_row_gap_40 cs_gap_y_20">
    @csrf
    @method('PATCH')
    <input type="hidden" name="profile_section" value="verification">

    <div class="col-sm-6">
        <label for="nid_number">NID Number</label>
        <input type="text" name="nid_number" class="cs_form_field cs_radius_7" id="nid_number" value="{{ old('nid_number', $user->nid_number) }}">
        <p class="cs_form_hint">Useful for local identity matching and land-service verification.</p>
        @error('nid_number')<div class="cs_form_error">{{ $message }}</div>@enderror
    </div>

    <div class="col-sm-6">
        <label for="passport_number">Passport Number</label>
        <input type="text" name="passport_number" class="cs_form_field cs_radius_7" id="passport_number" value="{{ old('passport_number', $user->passport_number) }}">
        <p class="cs_form_hint">Keep this if the owner uses passport instead of NID for identification.</p>
        @error('passport_number')<div class="cs_form_error">{{ $message }}</div>@enderror
    </div>

    <div class="col-sm-6">
        <div class="cs_file_card">
            <h3 class="cs_fs_20 cs_semibold cs_mb_15">NID Front Image</h3>
            @if ($profileFileUrls['nid_front_image'])
                <div class="cs_file_preview"><img src="{{ $profileFileUrls['nid_front_image'] }}" alt="NID Front"></div>
            @endif
            <input type="file" name="nid_front_image" class="cs_form_field cs_radius_7" accept=".jpg,.jpeg,.png,.webp">
            <div class="cs_file_actions">
                <label class="cs_remove_check">
                    <input type="checkbox" name="remove_nid_front_image" value="1">
                    <span>Remove file</span>
                </label>
            </div>
            @error('nid_front_image')<div class="cs_form_error">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="col-sm-6">
        <div class="cs_file_card">
            <h3 class="cs_fs_20 cs_semibold cs_mb_15">NID Back Image</h3>
            @if ($profileFileUrls['nid_back_image'])
                <div class="cs_file_preview"><img src="{{ $profileFileUrls['nid_back_image'] }}" alt="NID Back"></div>
            @endif
            <input type="file" name="nid_back_image" class="cs_form_field cs_radius_7" accept=".jpg,.jpeg,.png,.webp">
            <div class="cs_file_actions">
                <label class="cs_remove_check">
                    <input type="checkbox" name="remove_nid_back_image" value="1">
                    <span>Remove file</span>
                </label>
            </div>
            @error('nid_back_image')<div class="cs_form_error">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="col-sm-6">
        <div class="cs_file_card">
            <h3 class="cs_fs_20 cs_semibold cs_mb_15">Passport Copy</h3>
            @if ($profileFileUrls['passport_image'])
                <div class="cs_file_actions cs_mb_15">
                    <a href="{{ $profileFileUrls['passport_image'] }}" target="_blank" class="cs_btn cs_style_1 cs_type_1 cs_accent_color cs_medium cs_radius_7">
                        <span class="cs_btn_text">View Current File</span>
                    </a>
                </div>
            @endif
            <input type="file" name="passport_image" class="cs_form_field cs_radius_7" accept=".jpg,.jpeg,.png,.webp,.pdf">
            <div class="cs_file_actions">
                <label class="cs_remove_check">
                    <input type="checkbox" name="remove_passport_image" value="1">
                    <span>Remove file</span>
                </label>
            </div>
            @error('passport_image')<div class="cs_form_error">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="col-sm-6">
        <label for="ownership_document_type">Ownership Document Type</label>
        <select name="ownership_document_type" id="ownership_document_type" class="cs_form_field cs_radius_7">
            <option value="">Select document type</option>
            <option value="title-deed" @selected(old('ownership_document_type', $user->ownership_document_type) === 'title-deed')>Title Deed</option>
            <option value="mutation-certificate" @selected(old('ownership_document_type', $user->ownership_document_type) === 'mutation-certificate')>Mutation Certificate</option>
            <option value="land-development-tax" @selected(old('ownership_document_type', $user->ownership_document_type) === 'land-development-tax')>Land Development Tax Receipt</option>
            <option value="utility-bill" @selected(old('ownership_document_type', $user->ownership_document_type) === 'utility-bill')>Utility Bill</option>
            <option value="other" @selected(old('ownership_document_type', $user->ownership_document_type) === 'other')>Other</option>
        </select>
        @error('ownership_document_type')<div class="cs_form_error">{{ $message }}</div>@enderror
    </div>

    <div class="col-sm-6">
        <div class="cs_file_card">
            <h3 class="cs_fs_20 cs_semibold cs_mb_15">Ownership Proof</h3>
            @if ($profileFileUrls['ownership_proof'])
                <div class="cs_file_actions cs_mb_15">
                    <a href="{{ $profileFileUrls['ownership_proof'] }}" target="_blank" class="cs_btn cs_style_1 cs_type_1 cs_accent_color cs_medium cs_radius_7">
                        <span class="cs_btn_text">View Current File</span>
                    </a>
                </div>
            @endif
            <input type="file" name="ownership_proof" class="cs_form_field cs_radius_7" accept=".jpg,.jpeg,.png,.webp,.pdf">
            <p class="cs_form_hint">Upload deed, mutation certificate, tax receipt, or another ownership-supporting file.</p>
            <div class="cs_file_actions">
                <label class="cs_remove_check">
                    <input type="checkbox" name="remove_ownership_proof" value="1">
                    <span>Remove file</span>
                </label>
            </div>
            @error('ownership_proof')<div class="cs_form_error">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="col-lg-12">
        <button type="submit" aria-label="Save verification button" class="cs_btn cs_style_1 cs_accent_bg cs_white_color cs_medium cs_radius_7">
            <span>Save Verification</span>
        </button>
    </div>
</form>
