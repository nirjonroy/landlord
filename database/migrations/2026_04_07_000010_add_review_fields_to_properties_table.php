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
            $table->text('review_note')->nullable()->after('status');
            $table->timestamp('reviewed_at')->nullable()->after('review_note');
            $table->foreignId('reviewed_by_admin_id')
                ->nullable()
                ->after('reviewed_at')
                ->constrained('admins')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropConstrainedForeignId('reviewed_by_admin_id');
            $table->dropColumn(['review_note', 'reviewed_at']);
        });
    }
};
