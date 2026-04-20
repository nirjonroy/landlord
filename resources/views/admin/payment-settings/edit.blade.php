@extends('layouts.admin')

@section('page_title', 'Payment Settings')
@section('page_heading', 'Payment Settings')
@section('breadcrumb_current', 'Payment Settings')

@section('content')
  @if (session('status') === 'payment-settings-updated')
    <div class="alert alert-success">Payment settings updated successfully.</div>
  @endif

  @if ($errors->any())
    <div class="alert alert-danger">Please review the highlighted payment settings and try again.</div>
  @endif

  <div class="card dashboard-hero mb-4">
    <div class="card-body p-4 p-lg-5 position-relative">
      <div class="row align-items-center g-4">
        <div class="col-lg-8">
          <span class="badge rounded-pill text-bg-light text-primary mb-3">SSLCommerz configuration</span>
          <h1 class="h2 fw-bold mb-3">Store the SSLCommerz credentials used for subscription checkout.</h1>
          <p class="hero-meta mb-0">Users can only pay for subscription packages after SSLCommerz is enabled here with a valid Store ID and Store Password.</p>
        </div>
        <div class="col-lg-4">
          <div class="info-grid">
            <div class="info-tile bg-white bg-opacity-10 border-0 text-white">
              <span class="label text-white-50">Gateway status</span>
              <div class="value text-white">{{ $paymentSetting->isSslCommerzConfigured() ? 'Configured' : 'Not configured' }}</div>
            </div>
            <div class="info-tile bg-white bg-opacity-10 border-0 text-white">
              <span class="label text-white-50">Mode</span>
              <div class="value text-white">{{ ucfirst($paymentSetting->sslcommerz_mode) }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-xl-8">
      <div class="card card-outline card-primary">
        <div class="card-header">
          <h3 class="card-title fw-semibold mb-0">Edit Payment Settings</h3>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('admin.payment-settings.update') }}">
            @csrf
            @method('PUT')

            <div class="form-check form-switch mb-4">
              <input class="form-check-input" type="checkbox" role="switch" id="sslcommerz_enabled" name="sslcommerz_enabled" value="1" {{ old('sslcommerz_enabled', $paymentSetting->sslcommerz_enabled) ? 'checked' : '' }}>
              <label class="form-check-label" for="sslcommerz_enabled">Enable SSLCommerz subscription checkout</label>
            </div>

            <div class="row g-3">
              <div class="col-md-4">
                <label for="sslcommerz_mode" class="form-label">Mode</label>
                <select id="sslcommerz_mode" name="sslcommerz_mode" class="form-select @error('sslcommerz_mode') is-invalid @enderror" required>
                  <option value="sandbox" @selected(old('sslcommerz_mode', $paymentSetting->sslcommerz_mode) === 'sandbox')>Sandbox</option>
                  <option value="live" @selected(old('sslcommerz_mode', $paymentSetting->sslcommerz_mode) === 'live')>Live</option>
                </select>
                @error('sslcommerz_mode')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-4">
                <label for="currency" class="form-label">Currency</label>
                <input id="currency" name="currency" type="text" class="form-control @error('currency') is-invalid @enderror" value="{{ old('currency', $paymentSetting->currency) }}" required>
                @error('currency')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-12">
                <label for="sslcommerz_store_id" class="form-label">Store ID</label>
                <input id="sslcommerz_store_id" name="sslcommerz_store_id" type="text" class="form-control @error('sslcommerz_store_id') is-invalid @enderror" value="{{ old('sslcommerz_store_id', $paymentSetting->sslcommerz_store_id) }}" placeholder="testbox">
                @error('sslcommerz_store_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-12">
                <label for="sslcommerz_store_password" class="form-label">Store Password</label>
                <input id="sslcommerz_store_password" name="sslcommerz_store_password" type="password" class="form-control @error('sslcommerz_store_password') is-invalid @enderror" placeholder="{{ $paymentSetting->sslcommerz_store_password ? 'Leave blank to keep the current password' : 'Enter store password' }}">
                @error('sslcommerz_store_password')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Leave this blank if you do not want to replace the currently saved password.</div>
              </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i>
                Save Payment Settings
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-xl-4">
      <div class="card card-outline card-secondary mb-4">
        <div class="card-header">
          <h3 class="card-title fw-semibold">Current Gateway Snapshot</h3>
        </div>
        <div class="card-body">
          <p class="mb-3"><strong>Configured:</strong><br>{{ $paymentSetting->isSslCommerzConfigured() ? 'Yes' : 'No' }}</p>
          <p class="mb-3"><strong>Enabled:</strong><br>{{ $paymentSetting->sslcommerz_enabled ? 'Yes' : 'No' }}</p>
          <p class="mb-3"><strong>Mode:</strong><br>{{ ucfirst($paymentSetting->sslcommerz_mode) }}</p>
          <p class="mb-3"><strong>Currency:</strong><br>{{ $paymentSetting->currency }}</p>
          <p class="mb-0"><strong>Store ID:</strong><br>{{ $paymentSetting->sslcommerz_store_id ?: 'Not set yet' }}</p>
        </div>
      </div>

      <div class="card card-outline card-success">
        <div class="card-header">
          <h3 class="card-title fw-semibold">Callback URLs</h3>
        </div>
        <div class="card-body">
          <p class="mb-3"><strong>Success:</strong><br>{{ route('subscriptions.sslcommerz.success') }}</p>
          <p class="mb-3"><strong>Fail:</strong><br>{{ route('subscriptions.sslcommerz.fail') }}</p>
          <p class="mb-3"><strong>Cancel:</strong><br>{{ route('subscriptions.sslcommerz.cancel') }}</p>
          <p class="mb-0"><strong>IPN:</strong><br>{{ route('subscriptions.sslcommerz.ipn') }}</p>
        </div>
      </div>
    </div>
  </div>
@endsection
