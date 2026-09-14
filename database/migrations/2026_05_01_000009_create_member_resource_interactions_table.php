<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_resource_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_resource_id')->constrained('member_resources')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('status', 40)->default('new')->index();
            $table->timestamp('last_viewed_at')->nullable();
            $table->unsignedInteger('view_count')->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique(['member_resource_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_resource_interactions');
    }
};