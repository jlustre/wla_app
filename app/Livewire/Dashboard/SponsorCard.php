<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

class SponsorCard extends Component
{
    public array $sponsor = [];

    public function mount(array $sponsor): void
    {
        $this->sponsor = $sponsor;
    }

    public function render()
    {
        return view('livewire.dashboard.sponsor-card');
    }
}