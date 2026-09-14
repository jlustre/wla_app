<?php

namespace App\Livewire\Dashboard;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AvatarDropdown extends Component
{
    public function render()
    {
        return view('livewire.dashboard.avatar-dropdown', [
            'user' => Auth::user(),
        ]);
    }
}