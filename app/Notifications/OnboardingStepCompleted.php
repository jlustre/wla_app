<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class OnboardingStepCompleted extends Notification
{
    public function __construct(protected string $stepTitle)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'Onboarding step complete',
            'message' => 'You checked off "'.$this->stepTitle.'" on your onboarding checklist.',
            'type' => 'onboarding',
            'route' => 'member.onboarding',
        ];
    }
}
