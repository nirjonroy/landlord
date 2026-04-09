<div class="cs_profile cs_white_bg cs_radius_10">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 cs_mb_40">
        <div>
            <h2 class="cs_profile_title cs_fs_28 cs_semibold mb-2">Property Dashboard</h2>
            <p class="mb-0">Track your active property posts, see sale versus rent totals, and jump into the next property step quickly.</p>
        </div>
        <div class="cs_profile_action_group">
            <a href="{{ route('profile.edit', ['tab' => 'add_property']) }}#add_property" class="cs_btn cs_style_1 cs_accent_bg cs_white_color cs_medium cs_radius_7">
                <span>Add Property</span>
            </a>
            <a href="{{ route('profile.edit', ['tab' => 'my_property']) }}#my_property" class="cs_btn cs_style_1 cs_type_1 cs_accent_color cs_medium cs_radius_7">
                <span>View My Property</span>
            </a>
        </div>
    </div>

    <div class="cs_property_stat_grid cs_mb_40">
        <div class="cs_property_stat_card">
            <span class="cs_property_stat_label">Total Listed</span>
            <h3 class="cs_property_stat_value">{{ $propertyAnalytics['total_posts'] }}</h3>
            <p class="mb-0">All properties currently connected to your account.</p>
        </div>
        <div class="cs_property_stat_card">
            <span class="cs_property_stat_label">For Sale</span>
            <h3 class="cs_property_stat_value">{{ $propertyAnalytics['sale_posts'] }}</h3>
            <p class="mb-0">Properties that are currently marked for sale.</p>
        </div>
        <div class="cs_property_stat_card">
            <span class="cs_property_stat_label">For Rent</span>
            <h3 class="cs_property_stat_value">{{ $propertyAnalytics['rent_posts'] }}</h3>
            <p class="mb-0">Properties that are currently marked for rent.</p>
        </div>
    </div>

    <div class="cs_dashboard_note cs_mb_30">
        <strong>Status:</strong> {{ $propertyAnalytics['message'] }}
    </div>

    @if ($propertyAnalytics['listings']->isNotEmpty())
        <div class="cs_property_table_wrap">
            <table class="cs_property_table">
                <thead>
                    <tr>
                        <th class="cs_medium cs_heading_color">Recent Properties</th>
                        <th class="cs_medium cs_heading_color">Type</th>
                        <th class="cs_medium cs_heading_color">Status</th>
                        <th class="cs_medium cs_heading_color">Submitted</th>
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
                            <td>{{ $listing['submitted_at'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="cs_empty_state">
            <h3 class="cs_fs_24 cs_semibold cs_mb_12">No property is listed yet</h3>
            <p class="mb-0">Once you start adding sale or rent properties, their status will appear here for quick review.</p>
        </div>
    @endif
</div>
