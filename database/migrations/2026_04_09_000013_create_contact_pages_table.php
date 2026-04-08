<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_pages', function (Blueprint $table) {
            $table->id();
            $table->string('hero_title')->nullable();
            $table->string('hero_background_path')->nullable();
            $table->string('hero_background_source')->nullable();
            $table->string('form_title')->nullable();
            $table->text('form_intro')->nullable();
            $table->string('submit_button_text')->nullable();
            $table->string('success_message')->nullable();
            $table->string('testimonial_section_title')->nullable();
            $table->json('testimonials')->nullable();
            $table->json('brands')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contact_pages');
    }
};
