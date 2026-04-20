<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'sslcommerz_enabled',
        'sslcommerz_mode',
        'sslcommerz_store_id',
        'sslcommerz_store_password',
        'currency',
    ];

    protected $casts = [
        'sslcommerz_enabled' => 'boolean',
    ];

    public static function current(): self
    {
        return static::query()->firstOrCreate(
            ['id' => 1],
            [
                'sslcommerz_enabled' => false,
                'sslcommerz_mode' => 'sandbox',
                'currency' => 'BDT',
            ]
        );
    }

    public function isSslCommerzConfigured(): bool
    {
        return $this->sslcommerz_enabled
            && filled($this->sslcommerz_store_id)
            && filled($this->sslcommerz_store_password);
    }

    public function isLiveMode(): bool
    {
        return strtolower((string) $this->sslcommerz_mode) === 'live';
    }

    public function gatewayBaseUrl(): string
    {
        return $this->isLiveMode()
            ? 'https://securepay.sslcommerz.com'
            : 'https://sandbox.sslcommerz.com';
    }
}
