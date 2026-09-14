<?php

namespace App\Livewire\Member;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class SettingsPanel extends Component
{
    public string $setting = 'profile';

    public string $username = '';
    public string $email = '';
    public ?string $phone_number = null;
    public ?string $city = null;
    public ?string $bio = null;

    public bool $notify_email = true;
    public bool $notify_in_app = true;
    public bool $notify_webinar_reminders = true;
    public bool $notify_security_alerts = true;
    public bool $notify_sponsor_messages = true;

    public bool $privacy_show_phone_to_downline = false;
    public bool $privacy_show_email_to_sponsor = true;
    public bool $privacy_show_city_on_profile = false;

    public string $theme_preference = 'light';

    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function mount(string $setting = 'profile'): void
    {
        $this->setting = $setting;

        $authUser = Auth::user();
        $user = $authUser instanceof User ? $authUser : null;
        $profile = $user?->profile;

        $this->username = $user?->username ?? '';
        $this->email = $user?->email ?? '';
        $this->phone_number = $profile?->phone_number;
        $this->city = $profile?->city;
        $this->bio = $profile?->bio;

        $notificationPreferences = $profile?->notification_preferences ?? [];
        $privacyPreferences = $profile?->privacy_preferences ?? [];

        $this->notify_email = (bool) ($notificationPreferences['email'] ?? true);
        $this->notify_in_app = (bool) ($notificationPreferences['in_app'] ?? true);
        $this->notify_webinar_reminders = (bool) ($notificationPreferences['webinar_reminders'] ?? true);
        $this->notify_security_alerts = (bool) ($notificationPreferences['security_alerts'] ?? true);
        $this->notify_sponsor_messages = (bool) ($notificationPreferences['sponsor_messages'] ?? true);

        $this->privacy_show_phone_to_downline = (bool) ($privacyPreferences['show_phone_to_downline'] ?? false);
        $this->privacy_show_email_to_sponsor = (bool) ($privacyPreferences['show_email_to_sponsor'] ?? true);
        $this->privacy_show_city_on_profile = (bool) ($privacyPreferences['show_city_on_profile'] ?? false);

        $this->theme_preference = $profile?->theme_preference ?? 'light';
    }

    public function saveProfile(): void
    {
        $user = $this->resolveUser();

        $validated = $this->validate([
            'username' => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone_number' => ['nullable', 'string', 'max:30'],
            'city' => ['nullable', 'string', 'max:120'],
            'bio' => ['nullable', 'string', 'max:1000'],
        ]);

        $user->update([
            'username' => $validated['username'],
            'email' => $validated['email'],
        ]);

        $this->upsertProfile($user, [
            'phone_number' => $validated['phone_number'],
            'city' => $validated['city'],
            'bio' => $validated['bio'],
        ]);

        session()->flash('settings-status', 'Profile details updated.');
    }

    public function saveNotifications(): void
    {
        $user = $this->resolveUser();

        $this->upsertProfile($user, [
            'notification_preferences' => [
                'email' => $this->notify_email,
                'in_app' => $this->notify_in_app,
                'webinar_reminders' => $this->notify_webinar_reminders,
                'security_alerts' => $this->notify_security_alerts,
                'sponsor_messages' => $this->notify_sponsor_messages,
            ],
        ]);

        session()->flash('settings-status', 'Notification preferences updated.');
    }

    public function savePrivacy(): void
    {
        $user = $this->resolveUser();

        $this->upsertProfile($user, [
            'privacy_preferences' => [
                'show_phone_to_downline' => $this->privacy_show_phone_to_downline,
                'show_email_to_sponsor' => $this->privacy_show_email_to_sponsor,
                'show_city_on_profile' => $this->privacy_show_city_on_profile,
            ],
        ]);

        session()->flash('settings-status', 'Privacy settings updated.');
    }

    public function saveTheme(): void
    {
        $user = $this->resolveUser();

        $validated = $this->validate([
            'theme_preference' => ['required', Rule::in(['light', 'dark'])],
        ]);

        $this->upsertProfile($user, [
            'theme_preference' => $validated['theme_preference'],
        ]);

        $this->dispatch('set-theme', theme: $validated['theme_preference']);
        session()->flash('settings-status', 'Theme preference updated.');
    }

    public function updatePassword(): void
    {
        $user = $this->resolveUser();

        $validated = $this->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (! Hash::check($validated['current_password'], $user->password)) {
            $this->addError('current_password', 'The current password is incorrect.');

            return;
        }

        $user->update([
            'password' => $validated['password'],
        ]);

        $this->reset(['current_password', 'password', 'password_confirmation']);
        session()->flash('settings-status', 'Password updated successfully.');
    }

    public function render()
    {
        $user = $this->resolveUser();
        $profile = $user->profile;

        return view('livewire.member.settings-panel', [
            'user' => $user,
            'profile' => $profile,
            'isOverview' => ! in_array($this->setting, ['profile', 'security', 'password', 'notifications', 'privacy', 'theme'], true),
            'activeSessions' => $this->countActiveSessions($user),
        ]);
    }

    protected function resolveUser(): User
    {
        $authUser = Auth::user();

        abort_unless($authUser instanceof User, 403);

        return $authUser;
    }

    protected function upsertProfile(User $user, array $attributes): Profile
    {
        return $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $attributes,
        );
    }

    protected function countActiveSessions(User $user): int
    {
        if (! \Illuminate\Support\Facades\Schema::hasTable('sessions')) {
            return 1;
        }

        return (int) \Illuminate\Support\Facades\DB::table('sessions')
            ->where('user_id', $user->id)
            ->count();
    }
}