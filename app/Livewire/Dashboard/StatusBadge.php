<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

class StatusBadge extends Component
{
    public string $label = 'Active';
    public string $tone = 'blue';

    public function mount(string $label = 'Active', string $tone = 'blue'): void
    {
        $this->label = $label;
        $this->tone = $tone;
    }

    public function render()
    {
        return view('livewire.dashboard.status-badge');
    }
}