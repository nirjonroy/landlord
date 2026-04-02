<div class="cs_profile cs_white_bg cs_radius_10">
    <h2 class="cs_profile_title cs_fs_28 cs_semibold cs_mb_20">Add New Property</h2>

    <div class="cs_empty_state cs_mb_30">
        <h3 class="cs_fs_24 cs_semibold cs_mb_12">Property posting entry point</h3>
        <p class="mb-0">This section is reserved for the property submission flow. For now, users can see property totals and listing status from the dashboard and My Property sections.</p>
    </div>

    <div class="cs_property_stat_grid cs_mb_30">
        <div class="cs_property_stat_card">
            <span class="cs_property_stat_label">Before posting</span>
            <h3 class="cs_fs_24 cs_semibold cs_mb_10">Complete profile</h3>
            <p class="mb-0">Make sure your basic details, phone number, and landlord account information are accurate.</p>
        </div>
        <div class="cs_property_stat_card">
            <span class="cs_property_stat_label">Verification</span>
            <h3 class="cs_fs_24 cs_semibold cs_mb_10">Upload documents</h3>
            <p class="mb-0">Keep your NID, passport, and ownership proof ready so future property submissions stay trusted.</p>
        </div>
        <div class="cs_property_stat_card">
            <span class="cs_property_stat_label">Home details</span>
            <h3 class="cs_fs_24 cs_semibold cs_mb_10">Prepare listing data</h3>
            <p class="mb-0">Home name, location, area, and elevation photos are already collected in this profile for the next step.</p>
        </div>
    </div>

    <div class="cs_dashboard_note cs_mb_30">
        <strong>Current setup:</strong> {{ $propertyAnalytics['message'] }}
    </div>

    <div class="cs_profile_action_group">
        <a href="{{ route('profile.edit', ['tab' => 'my_property']) }}#my_property" class="cs_btn cs_style_1 cs_type_1 cs_accent_color cs_medium cs_radius_7">
            <span>View Property Status</span>
        </a>
        <a href="{{ route('profile.edit', ['tab' => 'home_info']) }}#home_info" class="cs_btn cs_style_1 cs_type_1 cs_accent_color cs_medium cs_radius_7">
            <span>Update Home Info</span>
        </a>
        <a href="{{ route('profile.edit', ['tab' => 'verification']) }}#verification" class="cs_btn cs_style_1 cs_type_1 cs_accent_color cs_medium cs_radius_7">
            <span>Update Verification</span>
        </a>
    </div>
</div>
