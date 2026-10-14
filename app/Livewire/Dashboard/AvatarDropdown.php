<?php

namespace App\Livewire\Dashboard;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class AvatarDropdown extends Component
{
    #[On('avatar-updated')]
    public function refreshAvatar(): void
    {
        // Re-rendering with a fresh Auth::user() lookup is enough; the
        // listener attribute is what triggers Livewire to re-render.
    }

    public function render()
    {
        $user = Auth::user();

        return view('livewire.dashboard.avatar-dropdown', [
            'user' => $user,
            'avatarUrl' => $user?->profile?->avatar ? asset('storage/'.$user->profile->avatar) : null,
        ]);
    }
}
