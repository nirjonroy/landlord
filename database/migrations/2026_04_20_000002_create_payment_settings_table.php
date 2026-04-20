<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('sslcommerz_enabled')->default(false);
            $table->string('sslcommerz_mode', 20)->default('sandbox');
            $table->string('sslcommerz_store_id')->nullable();
            $table->text('sslcommerz_store_password')->nullable();
            $table->string('currency', 10)->default('BDT');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_settings');
    }
};
