<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

class LogoutForm extends Component
{
    public string $surface = 'sidebar';

    public function render()
    {
        return view('livewire.dashboard.logout-form');
    }
}