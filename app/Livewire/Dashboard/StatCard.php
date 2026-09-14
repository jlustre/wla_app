<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

class StatCard extends Component
{
    public string $title;
    public string $value;
    public string $change;
    public string $icon;
    public string $accent;

    public function mount(string $title, string $value, string $change = '', string $icon = 'home', string $accent = 'blue'): void
    {
        $this->title = $title;
        $this->value = $value;
        $this->change = $change;
        $this->icon = $icon;
        $this->accent = $accent;
    }

    public function render()
    {
        return view('livewire.dashboard.stat-card');
    }
}