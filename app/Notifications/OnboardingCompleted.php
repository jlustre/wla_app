<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class OnboardingCompleted extends Notification
{
    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'Onboarding complete! 🎉',
            'message' => "You've finished every step of your onboarding checklist. Keep the momentum going!",
            'type' => 'onboarding',
            'route' => 'member.dashboard',
        ];
    }
}
