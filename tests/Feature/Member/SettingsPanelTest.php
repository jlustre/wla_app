<?php

use App\Livewire\Member\SettingsPanel;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

test('member profile settings can be updated', function () {
    $user = User::factory()->create([
        'username' => 'member.settings',
        'email' => 'member-settings@example.test',
    ]);

    $this->actingAs($user);

    Livewire::test(SettingsPanel::class, ['setting' => 'profile'])
        ->set('username', 'member.updated')
        ->set('email', 'member-updated@example.test')
        ->set('phone_number', '+1 (555) 000-1001')
        ->set('city', 'Austin')
        ->set('bio', 'Updated member bio.')
        ->call('saveProfile')
        ->assertHasNoErrors();

    expect($user->refresh()->username)->toBe('member.updated')
        ->and($user->email)->toBe('member-updated@example.test')
        ->and($user->profile->phone_number)->toBe('+1 (555) 000-1001')
        ->and($user->profile->city)->toBe('Austin')
        ->and($user->profile->bio)->toBe('Updated member bio.');
});

test('member notification and privacy settings can be updated', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(SettingsPanel::class, ['setting' => 'notifications'])
        ->set('notify_email', false)
        ->set('notify_in_app', true)
        ->set('notify_webinar_reminders', false)
        ->set('notify_security_alerts', true)
        ->set('notify_sponsor_messages', false)
        ->call('saveNotifications')
        ->assertHasNoErrors();

    Livewire::test(SettingsPanel::class, ['setting' => 'privacy'])
        ->set('privacy_show_phone_to_downline', true)
        ->set('privacy_show_email_to_sponsor', false)
        ->set('privacy_show_city_on_profile', true)
        ->call('savePrivacy')
        ->assertHasNoErrors();

    expect($user->refresh()->profile->notification_preferences)->toMatchArray([
        'email' => false,
        'in_app' => true,
        'webinar_reminders' => false,
        'security_alerts' => true,
        'sponsor_messages' => false,
    ])->and($user->profile->privacy_preferences)->toMatchArray([
        'show_phone_to_downline' => true,
        'show_email_to_sponsor' => false,
        'show_city_on_profile' => true,
    ]);
});

test('member theme preference can be updated', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(SettingsPanel::class, ['setting' => 'theme'])
        ->set('theme_preference', 'dark')
        ->call('saveTheme')
        ->assertHasNoErrors();

    expect($user->refresh()->profile->theme_preference)->toBe('dark');
});

test('current password is required to update member password', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(SettingsPanel::class, ['setting' => 'password'])
        ->set('current_password', 'wrong-password')
        ->set('password', 'new-password-123')
        ->set('password_confirmation', 'new-password-123')
        ->call('updatePassword')
        ->assertHasErrors(['current_password']);

    expect(Hash::check('password', $user->fresh()->password))->toBeTrue();
});