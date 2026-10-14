<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calendar_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calendar_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shared_with_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('permission', 10)->default('view'); // 'view' | 'edit'
            $table->timestamps();

            $table->unique(['calendar_id', 'shared_with_user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calendar_shares');
    }
};
