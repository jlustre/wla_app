<?php

namespace App\Livewire\Profile;

use Livewire\Component;

class ActivityItem extends Component
{
    public array $activity = [];

    public function mount(array $activity): void
    {
        $this->activity = $activity;
    }

    public function wrapperClasses(): string
    {
        return match ($this->activity['type']) {
            'success' => 'border-emerald-100 bg-emerald-50',
            'info' => 'border-cyan-100 bg-cyan-50',
            'danger' => 'border-rose-100 bg-rose-50',
            'warning' => 'border-violet-100 bg-violet-50',
            default => 'border-slate-100 bg-slate-50',
        };
    }

    public function iconClasses(): string
    {
        return match ($this->activity['type']) {
            'success' => 'bg-emerald-500 text-white',
            'info' => 'bg-cyan-500 text-white',
            'danger' => 'bg-rose-500 text-white',
            'warning' => 'bg-violet-500 text-white',
            default => 'bg-slate-500 text-white',
        };
    }

    public function render()
    {
        return view('livewire.profile.activity-item');
    }
}