<div class="cs_profile cs_white_bg cs_radius_10">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 cs_mb_40">
        <div>
            <h2 class="cs_profile_title cs_fs_28 cs_semibold mb-2">My Property</h2>
            <p class="mb-0">See which properties are already listed under your account and what status each one currently has.</p>
        </div>
        <a href="{{ route('profile.edit', ['tab' => 'add_property']) }}#add_property" class="cs_btn cs_style_1 cs_accent_bg cs_white_color cs_medium cs_radius_7">
            <span>Add Property</span>
        </a>
    </div>

    @if ($propertyAnalytics['listings']->isNotEmpty())
        <div class="cs_property_table_wrap">
            <table class="cs_property_table">
                <thead>
                    <tr>
                        <th class="cs_medium cs_heading_color">Property</th>
                        <th class="cs_medium cs_heading_color">Type</th>
                        <th class="cs_medium cs_heading_color">Status</th>
                        <th class="cs_medium cs_heading_color">Price</th>
                        <th class="cs_medium cs_heading_color">Submitted</th>
                        @if ($propertyAnalytics['table'] === 'properties')
                            <th class="cs_medium cs_heading_color">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($propertyAnalytics['listings'] as $listing)
                        <tr>
                            <td>
                                <div class="cs_property_info">
                                    <h3 class="cs_fs_20 cs_semibold cs_mb_3">{{ $listing['title'] }}</h3>
                                    <p class="cs_fs_14 mb-0">{{ $listing['location'] ?: 'Location not set yet' }}</p>
                                </div>
                            </td>
                            <td>{{ $listing['purpose'] }}</td>
                            <td>
                                <span class="cs_listing_status cs_listing_status_{{ $listing['status_tone'] }}">{{ $listing['status'] }}</span>
                            </td>
                            <td>{{ $listing['price'] ?: 'Price not set' }}</td>
                            <td>{{ $listing['submitted_at'] }}</td>
                            @if ($propertyAnalytics['table'] === 'properties' && $listing['id'])
                                <td>
                                    <form method="POST" action="{{ route('properties.destroy', $listing['id']) }}" onsubmit="return confirm('Delete this property from your account?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="cs_btn cs_style_1 cs_type_1 cs_accent_color cs_medium cs_radius_7 cs_table_action_btn">
                                            <span>Delete</span>
                                        </button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="cs_empty_state">
            <h3 class="cs_fs_24 cs_semibold cs_mb_12">No property has been added yet</h3>
            <p class="cs_mb_20">{{ $propertyAnalytics['message'] }}</p>
            <a href="{{ route('profile.edit', ['tab' => 'add_property']) }}#add_property" class="cs_btn cs_style_1 cs_accent_bg cs_white_color cs_medium cs_radius_7">
                <span>Open Add Property</span>
            </a>
        </div>
    @endif
</div>
