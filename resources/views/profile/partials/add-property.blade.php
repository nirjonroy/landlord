<div class="cs_profile cs_white_bg cs_radius_10">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 cs_mb_40">
        <div>
            <h2 class="cs_profile_title cs_fs_28 cs_semibold mb-2">Add New Property</h2>
            <p class="mb-0">Post a Bangladesh rent or sale property from your account. New submissions start with a pending review status.</p>
        </div>
        <span class="cs_inline_badge">Initial Status: Pending</span>
    </div>

    @include('profile.partials.property-form', [
        'formAction' => route('properties.store'),
        'formMethod' => 'POST',
        'submitLabel' => 'Submit Property',
        'property' => null,
        'propertyThumbnailUrl' => null,
        'propertyGalleryUrls' => [],
        'reviewFlowMessage' => 'Your property will be saved immediately and marked as Pending so it can appear in your dashboard and be reviewed later from the admin side.',
    ])
</div>
