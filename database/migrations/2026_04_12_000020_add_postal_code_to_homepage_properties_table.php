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
        Schema::table('homepage_properties', function (Blueprint $table) {
            $table->string('postal_code', 20)->nullable()->after('location');
            $table->index('postal_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('homepage_properties', function (Blueprint $table) {
            $table->dropIndex(['postal_code']);
            $table->dropColumn('postal_code');
        });
    }
};
