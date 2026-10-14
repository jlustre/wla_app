<?php

use App\Models\User;
use App\Services\OnboardingService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('onboarding_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('step_key');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'step_key']);
        });

        // Backfill existing members with progress rows (and mark whatever
        // they've already naturally done as complete) without sending the
        // new-member welcome notification or seeding fresh onboarding
        // calendar reminders — those are only for brand-new accounts.
        User::query()->select('id')->chunkById(100, function ($users) {
            foreach ($users as $user) {
                OnboardingService::backfillProgressOnly($user);
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('onboarding_progress');
    }
};
