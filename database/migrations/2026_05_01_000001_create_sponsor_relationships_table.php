<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sponsor_relationships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('sponsor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('sponsor_code', 64)->nullable()->index();
            $table->boolean('is_current')->default(true)->index();
            $table->timestamp('linked_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->json('metadata')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['member_id', 'is_current']);
            $table->index(['sponsor_id', 'is_current']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sponsor_relationships');
    }
};