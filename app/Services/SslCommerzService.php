<?php

namespace App\Services;

use App\Models\PaymentSetting;
use App\Models\SubscriptionTransaction;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SslCommerzService
{
    public function initiateCheckout(SubscriptionTransaction $transaction): array
    {
        $settings = PaymentSetting::current();

        if (! $settings->isSslCommerzConfigured()) {
            return [
                'ok' => false,
                'message' => 'SSLCommerz credentials are not configured yet.',
            ];
        }

        $user = $transaction->user()->firstOrFail();

        $payload = [
            'store_id' => $settings->sslcommerz_store_id,
            'store_passwd' => $settings->sslcommerz_store_password,
            'total_amount' => number_format((float) $transaction->amount, 2, '.', ''),
            'currency' => $transaction->currency ?: $settings->currency,
            'tran_id' => $transaction->tran_id,
            'success_url' => route('subscriptions.sslcommerz.success'),
            'fail_url' => route('subscriptions.sslcommerz.fail'),
            'cancel_url' => route('subscriptions.sslcommerz.cancel'),
            'ipn_url' => route('subscriptions.sslcommerz.ipn'),
            'shipping_method' => 'NO',
            'product_name' => $transaction->package_name,
            'product_category' => 'Subscription',
            'product_profile' => 'non-physical-goods',
            'cus_name' => $user->name ?: 'Land Site User',
            'cus_email' => $user->email ?: 'customer@landsite.test',
            'cus_add1' => $user->present_address ?: ($user->permanent_address ?: 'Bangladesh'),
            'cus_city' => $user->district ?: 'Dhaka',
            'cus_state' => $user->division ?: 'Dhaka',
            'cus_postcode' => $user->postal_code ?: '1205',
            'cus_country' => $user->country ?: 'Bangladesh',
            'cus_phone' => $user->phone ?: '01700000000',
            'value_a' => (string) $user->id,
            'value_b' => (string) $transaction->subscription_package_id,
            'value_c' => (string) $transaction->id,
        ];

        try {
            $response = $this->request()
                ->asForm()
                ->post($settings->gatewayBaseUrl().'/gwprocess/v4/api.php', $payload);
        } catch (\Throwable $exception) {
            Log::warning('SSLCommerz checkout initiation failed.', [
                'transaction_id' => $transaction->id,
                'tran_id' => $transaction->tran_id,
                'message' => $exception->getMessage(),
            ]);

            return [
                'ok' => false,
                'message' => 'Unable to connect to SSLCommerz right now.',
            ];
        }

        $data = $response->json();

        if (! $response->successful() || ! is_array($data)) {
            Log::warning('SSLCommerz checkout initiation returned an invalid response.', [
                'transaction_id' => $transaction->id,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'ok' => false,
                'message' => 'SSLCommerz returned an invalid checkout response.',
            ];
        }

        if (blank($data['GatewayPageURL'] ?? null)) {
            return [
                'ok' => false,
                'message' => $data['failedreason'] ?? 'SSLCommerz could not create a hosted payment page.',
                'data' => $data,
            ];
        }

        return [
            'ok' => true,
            'data' => $data,
            'gateway_url' => $data['GatewayPageURL'],
        ];
    }

    public function validatePayment(string $valId): array
    {
        $settings = PaymentSetting::current();

        if (! $settings->isSslCommerzConfigured()) {
            return [
                'ok' => false,
                'message' => 'SSLCommerz credentials are not configured yet.',
            ];
        }

        try {
            $response = $this->request()->get($settings->gatewayBaseUrl().'/validator/api/validationserverAPI.php', [
                'val_id' => $valId,
                'store_id' => $settings->sslcommerz_store_id,
                'store_passwd' => $settings->sslcommerz_store_password,
                'v' => 1,
                'format' => 'json',
            ]);
        } catch (\Throwable $exception) {
            Log::warning('SSLCommerz payment validation failed.', [
                'val_id' => $valId,
                'message' => $exception->getMessage(),
            ]);

            return [
                'ok' => false,
                'message' => 'Unable to validate the SSLCommerz payment.',
            ];
        }

        $data = $response->json();

        if (! $response->successful() || ! is_array($data)) {
            Log::warning('SSLCommerz validation returned an invalid response.', [
                'val_id' => $valId,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'ok' => false,
                'message' => 'SSLCommerz returned an invalid payment validation response.',
            ];
        }

        return [
            'ok' => true,
            'data' => $data,
        ];
    }

    private function request(): PendingRequest
    {
        $request = Http::acceptJson()->timeout(30);

        if (app()->environment('local')) {
            $request = $request->withoutVerifying();
        }

        return $request;
    }
}
