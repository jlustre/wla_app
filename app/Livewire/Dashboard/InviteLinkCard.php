<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

class InviteLinkCard extends Component
{
    public array $invite = [];
    public array $recentMembers = [];

    public function mount(array $invite, array $recentMembers = []): void
    {
        $this->invite = $invite;
        $this->recentMembers = $recentMembers;
    }

    public function render()
    {
        return view('livewire.dashboard.invite-link-card');
    }
}