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
        Schema::create('about_pages', function (Blueprint $table) {
            $table->id();
            $table->string('hero_title')->nullable();
            $table->string('hero_background_path')->nullable();
            $table->string('hero_background_source')->nullable();
            $table->string('mission_section_title')->nullable();
            $table->text('mission_section_intro')->nullable();
            $table->string('mission_heading')->nullable();
            $table->text('mission_body')->nullable();
            $table->string('mission_image_path')->nullable();
            $table->string('mission_image_source')->nullable();
            $table->string('vision_section_title')->nullable();
            $table->text('vision_section_intro')->nullable();
            $table->string('vision_heading')->nullable();
            $table->text('vision_body')->nullable();
            $table->string('vision_image_path')->nullable();
            $table->string('vision_image_source')->nullable();
            $table->string('team_section_title')->nullable();
            $table->json('stats')->nullable();
            $table->json('team_members')->nullable();
            $table->string('services_section_title')->nullable();
            $table->json('services')->nullable();
            $table->string('testimonial_section_title')->nullable();
            $table->json('testimonials')->nullable();
            $table->string('faq_section_title')->nullable();
            $table->json('faqs')->nullable();
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
        Schema::dropIfExists('about_pages');
    }
};
