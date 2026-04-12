<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('filter_value')->unique();
            $table->string('icon_path')->nullable();
            $table->string('icon_source', 20)->default('asset');
            $table->unsignedInteger('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('show_on_home')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_types');
    }
};
