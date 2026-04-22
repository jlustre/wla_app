<?php

namespace App\Livewire\Profile;

use Livewire\Component;

class StatsCard extends Component
{
    public string $label;
    public string $value;
    public string $subtext;

    public function mount(string $label, string $value, string $subtext = ''): void
    {
        $this->label = $label;
        $this->value = $value;
        $this->subtext = $subtext;
    }

    public function render()
    {
        return view('livewire.profile.stats-card');
    }
}