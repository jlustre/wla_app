<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_event_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_event_id')->constrained('member_events')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('status', 40)->default('reserved')->index();
            $table->timestamp('reserved_at')->nullable();
            $table->timestamp('attended_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique(['member_event_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_event_registrations');
    }
};