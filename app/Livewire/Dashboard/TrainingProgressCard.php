<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

class TrainingProgressCard extends Component
{
    public array $training = [];

    public function mount(array $training): void
    {
        $this->training = $training;
    }

    public function render()
    {
        return view('livewire.dashboard.training-progress-card');
    }
}