<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

class QuickActions extends Component
{
    public array $actions = [];

    public function mount(array $actions): void
    {
        $this->actions = $actions;
    }

    public function render()
    {
        return view('livewire.dashboard.quick-actions');
    }
}