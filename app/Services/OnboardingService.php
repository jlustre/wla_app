<?php

namespace App\Services;

use App\Models\Calendar;
use App\Models\CalendarEvent;
use App\Models\OnboardingProgress;
use App\Models\User;
use App\Notifications\OnboardingCompleted;
use App\Notifications\OnboardingStepCompleted;
use App\Notifications\OnboardingWelcome;
use App\Support\OnboardingSteps;

/**
 * Drives the new-member onboarding workflow: a checklist of auto-detected
 * steps (App\Support\OnboardingSteps), two calendar reminders seeded into
 * the member's own calendar, and database notifications at the moments
 * that matter (welcome, each step completed, fully onboarded).
 *
 * Consumed by App\Models\User::booted() (new members) and
 * App\Livewire\Member\OnboardingChecklist (re-checks progress on render).
 */
class OnboardingService
{
    /**
     * Full setup for a brand-new member: empty progress rows, two calendar
     * reminders in their personal calendar, and a welcome notification.
     */
    public static function initializeFor(User $user): void
    {
        foreach (OnboardingSteps::keys() as $key) {
            OnboardingProgress::firstOrCreate(['user_id' => $user->id, 'step_key' => $key]);
        }

        self::seedOnboardingCalendarEvents($user);

        $user->notify(new OnboardingWelcome());
    }

    /**
     * For members who already existed before this feature shipped: create
     * their progress rows and silently mark whatever they've naturally
     * already done as complete — no welcome notification, no calendar
     * reminders (those only make sense for someone just starting out).
     */
    public static function backfillProgressOnly(User $user): void
    {
        foreach (OnboardingSteps::all() as $step) {
            $progress = OnboardingProgress::firstOrCreate(['user_id' => $user->id, 'step_key' => $step['key']]);

            if (! $progress->isComplete() && ($step['check'])($user)) {
                $progress->update(['completed_at' => now()]);
            }
        }
    }

    /**
     * Re-runs every step's auto-detection, marks newly-completed steps,
     * fires a notification for each one that just flipped to complete (and
     * a final congratulatory one the moment the whole checklist is done),
     * and returns a summary ready for the checklist UI.
     *
     * @return array{steps: array, total: int, completed: int, percent: int, isComplete: bool}
     */
    public static function refresh(User $user): array
    {
        $rows = OnboardingProgress::where('user_id', $user->id)->get()->keyBy('step_key');
        $wasFullyComplete = $rows->isNotEmpty() && $rows->count() === count(OnboardingSteps::all()) && $rows->every(fn ($r) => $r->isComplete());

        $summary = [];
        $newlyCompletedTitles = [];

        foreach (OnboardingSteps::all() as $step) {
            $progress = $rows->get($step['key'])
                ?? OnboardingProgress::firstOrCreate(['user_id' => $user->id, 'step_key' => $step['key']]);

            if (! $progress->isComplete() && ($step['check'])($user)) {
                $progress->update(['completed_at' => now()]);
                $newlyCompletedTitles[] = $step['title'];
            }

            $summary[] = [
                'key' => $step['key'],
                'title' => $step['title'],
                'description' => $step['description'],
                'icon' => $step['icon'],
                'route' => $step['route'],
                'completed' => $progress->isComplete(),
                'completed_at' => $progress->completed_at,
            ];
        }

        foreach ($newlyCompletedTitles as $title) {
            $user->notify(new OnboardingStepCompleted($title));
        }

        $completedCount = collect($summary)->where('completed', true)->count();
        $total = count($summary);
        $isComplete = $total > 0 && $completedCount === $total;

        if ($isComplete && ! $wasFullyComplete) {
            $user->notify(new OnboardingCompleted());
        }

        return [
            'steps' => $summary,
            'total' => $total,
            'completed' => $completedCount,
            'percent' => $total > 0 ? (int) round($completedCount / $total * 100) : 0,
            'isComplete' => $isComplete,
        ];
    }

    protected static function seedOnboardingCalendarEvents(User $user): void
    {
        $calendar = Calendar::ensureDefaultFor($user);
        $tag = ' [onboarding-auto]';

        CalendarEvent::create([
            'user_id' => $user->id,
            'calendar_id' => $calendar->id,
            'type' => 'task',
            'title' => 'Finish Your Onboarding Checklist',
            'description' => 'Complete every step of your new-member checklist.'.$tag,
            'start_at' => now()->addDays(7)->startOfDay(),
            'end_at' => now()->addDays(7)->endOfDay(),
            'all_day' => true,
            'color' => 'tangerine',
            'reminder_minutes' => 1440,
        ]);

        CalendarEvent::create([
            'user_id' => $user->id,
            'calendar_id' => $calendar->id,
            'type' => 'follow_up',
            'title' => '30-Day Check-in with Your Sponsor',
            'description' => 'Reach out to your sponsor to review your first month and plan what\'s next.'.$tag,
            'start_at' => now()->addDays(30)->startOfDay(),
            'end_at' => now()->addDays(30)->endOfDay(),
            'all_day' => true,
            'color' => 'peacock',
            'reminder_minutes' => 1440,
        ]);
    }
}
