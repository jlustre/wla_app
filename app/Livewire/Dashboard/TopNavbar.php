<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

class TopNavbar extends Component
{
    public string $title = 'Member Dashboard';

    public string $eyebrow = 'Member dashboard';

    public function mount(string $title = 'Member Dashboard', string $eyebrow = 'Member dashboard'): void
    {
        $this->title = $title;
        $this->eyebrow = $eyebrow;
    }

    public function render()
    {
        return view('livewire.dashboard.top-navbar');
    }
}