@php
    $currentSubscription = $subscriptionSummary['subscription'] ?? null;
    $hasActiveSubscription = $subscriptionSummary['has_active_subscription'] ?? false;
    $activeListingCount = $subscriptionSummary['active_listing_count'] ?? 0;
    $remainingSlots = $subscriptionSummary['remaining_slots'];
    $gatewayReady = $subscriptionSummary['gateway_ready'] ?? false;
@endphp

<div class="cs_profile cs_white_bg cs_radius_10">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 cs_mb_40">
        <div>
            <h2 class="cs_profile_title cs_fs_28 cs_semibold mb-2">Subscription</h2>
            <p class="mb-0">Buy a package before posting rent or sale properties. Your active package controls how long your listing access stays open and how many active listings you can keep at once.</p>
        </div>
        <span class="cs_inline_badge">
            {{ $hasActiveSubscription ? 'Active until '.optional($currentSubscription?->ends_at)->format('d M Y') : 'No active subscription' }}
        </span>
    </div>

    <div class="cs_property_stat_grid cs_mb_40">
        <div class="cs_property_stat_card">
            <span class="cs_property_stat_label">Current Package</span>
            <h3 class="cs_fs_24 cs_semibold mb-2">{{ $currentSubscription?->package_name ?: 'None' }}</h3>
            <p class="mb-0">{{ $hasActiveSubscription ? 'Your listing access is currently active.' : 'Buy a package below to unlock listing access.' }}</p>
        </div>
        <div class="cs_property_stat_card">
            <span class="cs_property_stat_label">Active Listings</span>
            <h3 class="cs_property_stat_value">{{ $activeListingCount }}</h3>
            <p class="mb-0">Properties still marked as available under your account.</p>
        </div>
        <div class="cs_property_stat_card">
            <span class="cs_property_stat_label">Listing Limit</span>
            <h3 class="cs_fs_24 cs_semibold mb-2">{{ $hasActiveSubscription ? ($currentSubscription->property_limit === null ? 'Unlimited' : $currentSubscription->property_limit) : '--' }}</h3>
            <p class="mb-0">{{ $hasActiveSubscription ? ($currentSubscription->property_limit === null ? 'Your current package does not cap active listings.' : 'Current package limit for active listings.') : 'A package decides your available listing slots.' }}</p>
        </div>
        <div class="cs_property_stat_card">
            <span class="cs_property_stat_label">Remaining Slots</span>
            <h3 class="cs_property_stat_value">{{ $hasActiveSubscription ? ($remainingSlots === null ? '∞' : $remainingSlots) : 0 }}</h3>
            <p class="mb-0">{{ $hasActiveSubscription ? 'Delete or mark listings sold or rented to free slots when needed.' : 'No slots are available until a package is active.' }}</p>
        </div>
    </div>

    @if (! $gatewayReady)
        <div class="cs_status_alert cs_status_alert_error cs_mb_30">
            SSLCommerz is not configured yet by admin. Package checkout will work as soon as the gateway credentials are added from the admin panel.
        </div>
    @elseif ($hasActiveSubscription)
        <div class="cs_dashboard_note cs_mb_30">
            <strong>Current access:</strong> Your package is active until {{ optional($currentSubscription?->ends_at)->format('d M Y, h:i A') ?: 'N/A' }}. Buying another package now will extend your access window and replace the active listing limit with the newly purchased package.
        </div>
    @else
        <div class="cs_status_alert cs_status_alert_error cs_mb_30">
            You need an active subscription before adding or updating a property listing.
        </div>
    @endif

    @if ($subscriptionPackages->isNotEmpty())
        <div class="cs_property_stat_grid cs_mb_40">
            @foreach ($subscriptionPackages as $package)
                <div class="cs_property_stat_card">
                    <span class="cs_property_stat_label">{{ $package->is_featured ? 'Featured Package' : 'Subscription Package' }}</span>
                    <h3 class="cs_fs_24 cs_semibold mb-2">{{ $package->name }}</h3>
                    <p class="mb-3">{{ $package->description ?: 'Use this package to unlock property listing access from your profile.' }}</p>
                    <ul class="mb-4" style="padding-left: 18px;">
                        <li>Price: BDT {{ number_format((float) $package->price, 2) }}</li>
                        <li>Duration: {{ $package->duration_days }} day{{ $package->duration_days === 1 ? '' : 's' }}</li>
                        <li>{{ $package->property_limit === null ? 'Unlimited active listings' : $package->property_limit.' active listing'.($package->property_limit === 1 ? '' : 's') }}</li>
                    </ul>
                    @if ($gatewayReady)
                        <form method="POST" action="{{ route('subscriptions.checkout', $package) }}">
                            @csrf
                            <button type="submit" class="cs_btn cs_style_1 {{ $package->is_featured ? 'cs_accent_bg cs_white_color' : 'cs_type_1 cs_accent_color' }} cs_medium cs_radius_7">
                                <span>{{ $hasActiveSubscription ? 'Renew / Switch Package' : 'Pay with SSLCommerz' }}</span>
                            </button>
                        </form>
                    @else
                        <button type="button" class="cs_btn cs_style_1 cs_type_1 cs_accent_color cs_medium cs_radius_7" disabled>
                            <span>Waiting for Gateway Setup</span>
                        </button>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="cs_empty_state cs_mb_40">
            <h3 class="cs_fs_24 cs_semibold cs_mb_12">No subscription package is available yet</h3>
            <p class="mb-0">An admin still needs to create the first package from the backend before users can subscribe.</p>
        </div>
    @endif

    <div>
        <h3 class="cs_fs_24 cs_semibold cs_mb_20">Recent Payment Attempts</h3>
        @if ($subscriptionTransactions->isNotEmpty())
            <div class="cs_property_table_wrap">
                <table class="cs_property_table">
                    <thead>
                        <tr>
                            <th class="cs_medium cs_heading_color">Package</th>
                            <th class="cs_medium cs_heading_color">Amount</th>
                            <th class="cs_medium cs_heading_color">Status</th>
                            <th class="cs_medium cs_heading_color">Transaction ID</th>
                            <th class="cs_medium cs_heading_color">Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subscriptionTransactions as $transaction)
                            @php
                                $statusTone = match ($transaction->status) {
                                    'paid' => 'success',
                                    'failed', 'cancelled' => 'danger',
                                    default => 'warning',
                                };
                            @endphp
                            <tr>
                                <td>{{ $transaction->package_name }}</td>
                                <td>BDT {{ number_format((float) $transaction->amount, 2) }}</td>
                                <td><span class="cs_listing_status cs_listing_status_{{ $statusTone }}">{{ ucwords(str_replace('_', ' ', $transaction->status)) }}</span></td>
                                <td>{{ $transaction->tran_id }}</td>
                                <td>{{ optional($transaction->created_at)->format('d M Y, h:i A') ?: 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="cs_empty_state">
                <h3 class="cs_fs_24 cs_semibold cs_mb_12">No payment attempt yet</h3>
                <p class="mb-0">Your subscription purchase history will appear here after the first SSLCommerz checkout starts.</p>
            </div>
        @endif
    </div>
</div>
