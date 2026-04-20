<?php

namespace App\Services;

use App\Models\PaymentSetting;
use App\Models\SubscriptionTransaction;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Support\Carbon;

class SubscriptionAccessService
{
    public function summaryForUser(User $user): array
    {
        $subscription = $this->currentSubscription($user);
        $activeListingCount = $this->activeListingCount($user);
        $remainingSlots = $this->remainingSlots($subscription, $activeListingCount);
        $paymentSetting = PaymentSetting::current();

        return [
            'subscription' => $subscription,
            'has_active_subscription' => $subscription?->isActive() ?? false,
            'active_listing_count' => $activeListingCount,
            'remaining_slots' => $remainingSlots,
            'can_create_property' => $this->canCreateProperty($user)['allowed'],
            'gateway_ready' => $paymentSetting->isSslCommerzConfigured(),
        ];
    }

    public function currentSubscription(User $user): ?UserSubscription
    {
        $subscription = $user->subscription()
            ->with('package', 'lastTransaction')
            ->first();

        return $this->syncSubscriptionStatus($subscription);
    }

    public function canCreateProperty(User $user): array
    {
        $subscription = $this->currentSubscription($user);

        if (! $subscription?->isActive()) {
            return [
                'allowed' => false,
                'status' => 'subscription-required',
                'message' => 'You need an active subscription before posting a property.',
            ];
        }

        $activeListingCount = $this->activeListingCount($user);

        if ($subscription->property_limit !== null && $activeListingCount >= $subscription->property_limit) {
            return [
                'allowed' => false,
                'status' => 'subscription-limit-reached',
                'message' => 'Your current subscription has no remaining active listing slots.',
            ];
        }

        return [
            'allowed' => true,
            'status' => 'subscription-active',
            'message' => 'Your subscription is active and can be used for a new listing.',
        ];
    }

    public function canUpdateProperty(User $user): array
    {
        $subscription = $this->currentSubscription($user);

        if (! $subscription?->isActive()) {
            return [
                'allowed' => false,
                'status' => 'subscription-required',
                'message' => 'You need an active subscription before updating a property listing.',
            ];
        }

        return [
            'allowed' => true,
            'status' => 'subscription-active',
            'message' => 'Your subscription is active.',
        ];
    }

    public function activateTransaction(SubscriptionTransaction $transaction): UserSubscription
    {
        $transaction->loadMissing('package');

        $subscription = UserSubscription::query()->firstOrNew([
            'user_id' => $transaction->user_id,
        ]);

        $now = now();
        $hasActivePeriod = $subscription->exists
            && $subscription->status === 'active'
            && $subscription->ends_at !== null
            && $subscription->ends_at->isFuture();

        $baseDate = $hasActivePeriod ? $subscription->ends_at->copy() : $now->copy();

        $subscription->fill([
            'subscription_package_id' => $transaction->subscription_package_id,
            'last_transaction_id' => $transaction->id,
            'status' => 'active',
            'package_name' => $transaction->package_name,
            'property_limit' => $transaction->property_limit,
            'started_at' => $hasActivePeriod
                ? ($subscription->started_at ?: $now->copy())
                : $now->copy(),
            'ends_at' => $baseDate->addDays($transaction->duration_days),
        ]);

        $subscription->save();

        return $subscription->fresh(['package', 'lastTransaction']);
    }

    public function remainingSlots(?UserSubscription $subscription, int $activeListingCount): ?int
    {
        if (! $subscription?->isActive()) {
            return null;
        }

        if ($subscription->property_limit === null) {
            return null;
        }

        return max(0, $subscription->property_limit - $activeListingCount);
    }

    public function syncSubscriptionStatus(?UserSubscription $subscription): ?UserSubscription
    {
        if (! $subscription) {
            return null;
        }

        if (
            $subscription->status === 'active'
            && $subscription->ends_at !== null
            && $subscription->ends_at->isPast()
        ) {
            $subscription->status = 'expired';
            $subscription->save();
        }

        return $subscription;
    }

    public function activeListingCount(User $user): int
    {
        return $user->properties()
            ->where('availability_status', 'available')
            ->count();
    }
}
