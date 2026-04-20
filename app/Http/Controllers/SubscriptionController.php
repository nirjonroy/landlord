<?php

namespace App\Http\Controllers;

use App\Models\PaymentSetting;
use App\Models\SubscriptionPackage;
use App\Models\SubscriptionTransaction;
use App\Services\SslCommerzService;
use App\Services\SubscriptionAccessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionController extends Controller
{
    private SslCommerzService $sslCommerzService;

    private SubscriptionAccessService $subscriptionAccessService;

    public function __construct(
        SslCommerzService $sslCommerzService,
        SubscriptionAccessService $subscriptionAccessService,
    ) {
        $this->sslCommerzService = $sslCommerzService;
        $this->subscriptionAccessService = $subscriptionAccessService;
    }

    public function checkout(Request $request, SubscriptionPackage $package): RedirectResponse
    {
        abort_unless($package->is_active, 404);

        $paymentSetting = PaymentSetting::current();

        if (! $paymentSetting->isSslCommerzConfigured()) {
            return Redirect::to(route('profile.edit', ['tab' => 'subscription']).'#subscription')
                ->with('status', 'subscription-gateway-not-configured');
        }

        $user = $request->user();

        $transaction = SubscriptionTransaction::query()->create([
            'user_id' => $user->id,
            'subscription_package_id' => $package->id,
            'gateway' => 'sslcommerz',
            'status' => 'pending',
            'tran_id' => 'SUB-'.$user->id.'-'.strtoupper(Str::random(18)),
            'package_name' => $package->name,
            'amount' => $package->price,
            'currency' => $paymentSetting->currency ?: 'BDT',
            'duration_days' => $package->duration_days,
            'property_limit' => $package->property_limit,
        ]);

        $result = $this->sslCommerzService->initiateCheckout($transaction);

        if (! $result['ok']) {
            $transaction->fill([
                'status' => 'failed',
                'failed_at' => now(),
                'gateway_response' => [
                    'initiate' => $result['data'] ?? null,
                    'error' => $result['message'] ?? 'Unknown SSLCommerz initiation error.',
                ],
            ])->save();

            return Redirect::to(route('profile.edit', ['tab' => 'subscription']).'#subscription')
                ->with('status', 'subscription-payment-init-failed');
        }

        $transaction->fill([
            'status' => 'initiated',
            'gateway_status' => 'INITIATED',
            'gateway_response' => [
                'initiate' => $result['data'],
            ],
        ])->save();

        return redirect()->away($result['gateway_url']);
    }

    public function success(Request $request): RedirectResponse
    {
        $transaction = $this->transactionFromRequest($request);

        if (! $transaction) {
            return Redirect::to(route('profile.edit', ['tab' => 'subscription']).'#subscription')
                ->with('status', 'subscription-transaction-missing');
        }

        $wasActivated = $this->finalizeValidatedPayment($transaction, (string) $request->input('val_id'), $request->all());

        return Redirect::to(route('profile.edit', ['tab' => 'subscription']).'#subscription')
            ->with('status', $wasActivated ? 'subscription-activated' : 'subscription-payment-validation-failed');
    }

    public function fail(Request $request): RedirectResponse
    {
        $transaction = $this->transactionFromRequest($request);

        $this->markTransaction($transaction, 'failed', [
            'callback' => $request->all(),
        ]);

        return Redirect::to(route('profile.edit', ['tab' => 'subscription']).'#subscription')
            ->with('status', 'subscription-payment-failed');
    }

    public function cancel(Request $request): RedirectResponse
    {
        $transaction = $this->transactionFromRequest($request);

        $this->markTransaction($transaction, 'cancelled', [
            'callback' => $request->all(),
        ]);

        return Redirect::to(route('profile.edit', ['tab' => 'subscription']).'#subscription')
            ->with('status', 'subscription-payment-cancelled');
    }

    public function ipn(Request $request): Response
    {
        $transaction = $this->transactionFromRequest($request);

        if (! $transaction) {
            return response('TRANSACTION_NOT_FOUND', 404);
        }

        $wasActivated = $this->finalizeValidatedPayment($transaction, (string) $request->input('val_id'), $request->all());

        return response($wasActivated ? 'OK' : 'INVALID', $wasActivated ? 200 : 422);
    }

    private function transactionFromRequest(Request $request): ?SubscriptionTransaction
    {
        $tranId = (string) $request->input('tran_id');

        if ($tranId === '') {
            return null;
        }

        return SubscriptionTransaction::query()
            ->where('tran_id', $tranId)
            ->first();
    }

    private function finalizeValidatedPayment(SubscriptionTransaction $transaction, string $valId, array $callbackPayload): bool
    {
        if ($transaction->status === 'paid') {
            return true;
        }

        if ($valId === '') {
            $this->markTransaction($transaction, 'failed', [
                'callback' => $callbackPayload,
                'error' => 'Missing validation ID from SSLCommerz callback.',
            ]);

            return false;
        }

        $result = $this->sslCommerzService->validatePayment($valId);

        if (! $result['ok']) {
            $this->markTransaction($transaction, 'failed', [
                'callback' => $callbackPayload,
                'validation_error' => $result['message'] ?? 'Unable to validate payment.',
            ]);

            return false;
        }

        $validationData = $result['data'];
        $validationStatus = strtoupper((string) ($validationData['status'] ?? ''));
        $validatedAmount = (float) ($validationData['amount'] ?? 0);
        $expectedAmount = (float) $transaction->amount;
        $validatedTranId = (string) ($validationData['tran_id'] ?? '');

        $isValid = in_array($validationStatus, ['VALID', 'VALIDATED'], true)
            && $validatedTranId === $transaction->tran_id
            && abs($validatedAmount - $expectedAmount) < 0.01;

        if (! $isValid) {
            $this->markTransaction($transaction, 'failed', [
                'callback' => $callbackPayload,
                'validate' => $validationData,
            ], $validationStatus !== '' ? $validationStatus : 'INVALID');

            return false;
        }

        $transaction->fill([
            'status' => 'paid',
            'val_id' => $valId,
            'gateway_status' => $validationStatus,
            'paid_at' => now(),
            'failed_at' => null,
            'cancelled_at' => null,
            'gateway_response' => $this->mergedGatewayResponse($transaction, [
                'callback' => $callbackPayload,
                'validate' => $validationData,
            ]),
        ])->save();

        $this->subscriptionAccessService->activateTransaction($transaction);

        return true;
    }

    private function markTransaction(?SubscriptionTransaction $transaction, string $status, array $payload = [], ?string $gatewayStatus = null): void
    {
        if (! $transaction || $transaction->status === 'paid') {
            return;
        }

        $transaction->status = $status;
        $transaction->gateway_status = $gatewayStatus ?: strtoupper($status);
        $transaction->gateway_response = $this->mergedGatewayResponse($transaction, $payload);

        if ($status === 'failed') {
            $transaction->failed_at = now();
        }

        if ($status === 'cancelled') {
            $transaction->cancelled_at = now();
        }

        $transaction->save();
    }

    private function mergedGatewayResponse(SubscriptionTransaction $transaction, array $payload): array
    {
        $existing = is_array($transaction->gateway_response) ? $transaction->gateway_response : [];

        return array_merge($existing, Arr::where($payload, fn ($value) => $value !== null));
    }
}
