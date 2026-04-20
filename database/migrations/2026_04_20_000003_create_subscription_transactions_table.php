<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subscription_package_id')->nullable()->constrained()->nullOnDelete();
            $table->string('gateway', 50)->default('sslcommerz');
            $table->string('status', 30)->default('pending');
            $table->string('tran_id')->unique();
            $table->string('val_id')->nullable()->index();
            $table->string('gateway_status', 50)->nullable();
            $table->string('package_name');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 10)->default('BDT');
            $table->unsignedInteger('duration_days');
            $table->unsignedInteger('property_limit')->nullable();
            $table->json('gateway_response')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_transactions');
    }
};
