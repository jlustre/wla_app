<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

class RecentActivityTimeline extends Component
{
    public array $activities = [];

    public function mount(array $activities): void
    {
        $this->activities = $activities;
    }

    public function render()
    {
        return view('livewire.dashboard.recent-activity-timeline');
    }
}