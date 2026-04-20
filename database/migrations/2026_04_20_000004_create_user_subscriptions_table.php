<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('subscription_package_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('last_transaction_id')->nullable()->constrained('subscription_transactions')->nullOnDelete();
            $table->string('status', 30)->default('inactive');
            $table->string('package_name')->nullable();
            $table->unsignedInteger('property_limit')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_subscriptions');
    }
};
