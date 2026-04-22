<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('logo')->nullable();
            $table->foreignId('theme_id')->nullable()->constrained('themes')->nullOnDelete();
            $table->text('short_description')->nullable();
            $table->text('full_description')->nullable();
            $table->text('banner')->nullable();
            $table->string('category')->nullable();
            $table->string('website_link')->nullable();
            $table->string('comp_plan_link')->nullable();
            $table->string('signup_link')->nullable();
            $table->string('backoffice_link')->nullable();
            $table->string('intro_video_link')->nullable();
            $table->string('webinar_link')->nullable();
            $table->string('location')->nullable();
            $table->string('phone')->nullable();
            $table->string('tagline')->nullable();
            $table->string('ceo_name')->nullable();
            $table->enum('status', [0, 1])->default(0)->comment('0 = inactive, 1 = active');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->boolean('is_publish')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
