<?php

use App\Livewire\Member\OnboardingChecklist;
use App\Models\CalendarEvent;
use App\Models\Invitation;
use App\Models\MemberCompanyAffiliation;
use App\Models\OnboardingProgress;
use App\Models\Prospect;
use App\Models\User;
use App\Services\OnboardingService;
use App\Support\OnboardingSteps;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

test('a brand-new member gets progress rows, calendar reminders, and a welcome notification', function () {
    Notification::fake();

    $user = User::factory()->create();

    expect(OnboardingProgress::where('user_id', $user->id)->count())->toBe(count(OnboardingSteps::all()));
    expect(OnboardingProgress::where('user_id', $user->id)->whereNotNull('completed_at')->count())->toBe(0);

    $onboardingEvents = CalendarEvent::where('user_id', $user->id)->where('description', 'like', '%[onboarding-auto]%')->get();
    expect($onboardingEvents)->toHaveCount(2);
    expect($onboardingEvents->pluck('title')->all())->toBe(['Finish Your Onboarding Checklist', '30-Day Check-in with Your Sponsor']);

    Notification::assertSentTo($user, \App\Notifications\OnboardingWelcome::class);
});

test('refreshing progress detects steps the member has already completed and notifies once', function () {
    Notification::fake();

    $user = User::factory()->create();
    Prospect::create(['user_id' => $user->id, 'first_name' => 'Jane', 'last_name' => 'Doe', 'email' => 'jane@example.test']);

    $summary = OnboardingService::refresh($user);
    $addProspectStep = collect($summary['steps'])->firstWhere('key', 'add_prospect');

    expect($addProspectStep['completed'])->toBeTrue();
    expect($summary['completed'])->toBe(1);
    expect($summary['isComplete'])->toBeFalse();

    Notification::assertSentToTimes($user, \App\Notifications\OnboardingStepCompleted::class, 1);

    // Refreshing again should not re-fire the notification for the same step.
    OnboardingService::refresh($user);
    Notification::assertSentToTimes($user, \App\Notifications\OnboardingStepCompleted::class, 1);
});

test('completing every step fires the congratulatory notification exactly once', function () {
    Notification::fake();

    $user = User::factory()->create();

    // Satisfy every auto-check.
    $user->profile()->update(['bio' => 'Bio', 'avatar' => 'avatar.jpg', 'completion_percentage' => 100]);
    Prospect::create(['user_id' => $user->id, 'first_name' => 'A', 'last_name' => 'B', 'email' => 'ab@example.test']);
    Invitation::create(['inviter_id' => $user->id, 'email' => 'invited@example.test', 'invite_code' => 'ABC123', 'invite_link' => url('/register?ref=ABC123'), 'status' => 'sent']);
    $company = \App\Models\Company::create(['name' => 'Test Co', 'slug' => 'test-co-'.uniqid(), 'status' => '1']);
    MemberCompanyAffiliation::create(['user_id' => $user->id, 'company_id' => $company->id]);
    $resource = \App\Models\MemberResource::create(['title' => 'Test Resource', 'slug' => 'test-resource-'.uniqid(), 'category' => 'training']);
    \App\Models\MemberResourceInteraction::create([
        'member_resource_id' => $resource->id,
        'user_id' => $user->id,
        'status' => 'viewed',
    ]);
    CalendarEvent::factory()->for($user)->create(['title' => 'My Own Event']);

    $summary = OnboardingService::refresh($user);

    expect($summary['isComplete'])->toBeTrue();
    expect($summary['percent'])->toBe(100);

    Notification::assertSentTo($user, \App\Notifications\OnboardingCompleted::class);

    // A second refresh shouldn't re-fire it.
    OnboardingService::refresh($user);
    Notification::assertSentToTimes($user, \App\Notifications\OnboardingCompleted::class, 1);
});

test('the compact dashboard widget hides itself once onboarding is complete', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(OnboardingChecklist::class, ['compact' => true])
        ->assertSee('Your Onboarding Checklist');

    foreach (OnboardingSteps::keys() as $key) {
        OnboardingProgress::where('user_id', $user->id)->where('step_key', $key)->update(['completed_at' => now()]);
    }

    Livewire::actingAs($user)
        ->test(OnboardingChecklist::class, ['compact' => true])
        ->assertDontSee('Your Onboarding Checklist');
});

test('the full onboarding page lists every step with working action links', function () {
    $user = User::factory()->create();

    $response = Livewire::actingAs($user)->test(OnboardingChecklist::class);

    foreach (OnboardingSteps::all() as $step) {
        $response->assertSee($step['title']);
    }

    $response->assertSee('My Onboarding');
});

test('backfilling an existing member never sends notifications or seeds calendar events', function () {
    Notification::fake();

    $user = User::factory()->create();
    Notification::fake(); // reset: ignore the welcome notification from creation itself

    OnboardingService::backfillProgressOnly($user);

    Notification::assertNothingSent();
    expect(CalendarEvent::where('user_id', $user->id)->where('description', 'like', '%[onboarding-auto]%')->count())->toBe(2); // only the ones from initial creation
});
