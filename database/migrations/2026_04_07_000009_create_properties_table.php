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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('purpose', 20);
            $table->string('property_type');
            $table->string('status', 30)->default('pending');
            $table->unsignedBigInteger('price');
            $table->decimal('area_size', 10, 2)->nullable();
            $table->unsignedSmallInteger('bedrooms')->nullable();
            $table->unsignedSmallInteger('bathrooms')->nullable();
            $table->unsignedSmallInteger('garages')->nullable();
            $table->string('location')->nullable();
            $table->string('district')->nullable();
            $table->string('division')->nullable();
            $table->text('address')->nullable();
            $table->text('description')->nullable();
            $table->string('contact_phone', 30)->nullable();
            $table->string('thumbnail_path')->nullable();
            $table->json('gallery_paths')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'purpose']);
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
