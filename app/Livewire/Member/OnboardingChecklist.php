<?php

namespace App\Livewire\Member;

use App\Services\OnboardingService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/**
 * Renders in two places with the same underlying progress data:
 * - compact=true: a small progress widget on the member dashboard (hides
 *   itself once the checklist is fully complete).
 * - compact=false: the full "My Onboarding" page, with step descriptions
 *   and action buttons.
 *
 * Progress is re-checked (App\Services\OnboardingService::refresh) on every
 * mount, so visiting either view is what triggers a step to flip to
 * "complete" and fire its notification — there's no separate background
 * job watching for this.
 */
class OnboardingChecklist extends Component
{
    public bool $compact = false;

    public function mount(bool $compact = false): void
    {
        $this->compact = $compact;
    }

    public function refresh(): void
    {
        // Explicit re-check button on the full page, in case a step was
        // just completed in another tab.
    }

    public function render()
    {
        $progress = OnboardingService::refresh(Auth::user());

        return view('livewire.member.onboarding-checklist', [
            'progress' => $progress,
        ]);
    }
}
