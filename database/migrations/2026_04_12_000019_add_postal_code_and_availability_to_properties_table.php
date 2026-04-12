<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->string('availability_status', 30)->default('available')->after('status');
            $table->string('postal_code', 20)->nullable()->after('division');

            $table->index('postal_code');
            $table->index(['status', 'availability_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropIndex(['status', 'availability_status']);
            $table->dropIndex(['postal_code']);
            $table->dropColumn(['availability_status', 'postal_code']);
        });
    }
};
