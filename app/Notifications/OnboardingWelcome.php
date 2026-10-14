<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class OnboardingWelcome extends Notification
{
    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'Welcome to Wealth Legacy Alliance!',
            'message' => "We've set up your onboarding checklist — complete it to get your workspace, calendar, and network ready.",
            'type' => 'onboarding',
            'route' => 'member.onboarding',
        ];
    }
}
