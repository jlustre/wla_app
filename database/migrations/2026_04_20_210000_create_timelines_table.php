<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('timelines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prospect_id')->constrained()->onDelete('cascade');
            $table->enum('action', ['Added', 'Contacted', 'Invited', 'Presented', 'Followed Up','Joined', 'Closed'])->default('Added'); // e.g., Contacted, Sent Video, etc.
            $table->text('notes')->nullable();
            $table->timestamp('action_at')->nullable();
            $table->dateTime('next_follow_up_dt')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timelines');
    }
};
