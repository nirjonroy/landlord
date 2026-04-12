@if ($homePropertyTypes->isNotEmpty())
    <section id="featured-properties">
        <div class="cs_height_120 cs_height_lg_80"></div>
        <div class="container">
            <div class="cs_section_heading cs_style_1 text-center">
                <h2 class="cs_section_title cs_fs_48 cs_semibold mb-0 wow fadeInDown">Discover the Property Types</h2>
            </div>
            <div class="cs_height_40 cs_height_lg_30"></div>
            <div class="cs_features_content_wrapper">
                <div class="row">
                    @foreach ($homePropertyTypes as $propertyType)
                        <div class="col-lg-3 col-sm-6 wow zoomIn">
                            <a href="{{ route('properties.index', ['property_type' => $propertyType->filter_value]) }}" aria-label="Click to visit {{ $propertyType->name }} listings" class="cs_iconbox cs_style_1 cs_white_bg cs_radius_15 cs_hover_3 text-center">
                                <span class="cs_iconbox_icon cs_center cs_mb_31">
                                    <img src="{{ $propertyType->iconUrl() }}" alt="{{ $propertyType->name }} Icon">
                                </span>
                                <h3 class="cs_iconbox_title cs_fs_24 cs_normal cs_body_font mb-0 text-uppercase">{{ $propertyType->name }}</h3>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="cs_height_80 cs_height_lg_50"></div>
    </section>
@endif
