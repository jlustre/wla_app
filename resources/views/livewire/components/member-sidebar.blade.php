<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }
}; ?>

<aside class="w-64 bg-white shadow-md flex flex-col">
    <div class="p-6 font-bold text-xl border-b">WLA Member</div>
    <nav class="flex-1 p-4">
        <ul class="space-y-2">
            <li><a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded hover:bg-gray-200">Dashboard</a></li>
            <li><a href="{{ route('profile') }}" class="block px-3 py-2 rounded hover:bg-gray-200">Profile</a></li>
            <li><a href="#" class="block px-3 py-2 rounded hover:bg-gray-200">My Sponsor</a></li>
            <li><a href="#" class="block px-3 py-2 rounded hover:bg-gray-200">My Referrals</a></li>
            <li><a href="#" class="block px-3 py-2 rounded hover:bg-gray-200">My Companies</a></li>
        </ul>
    </nav>
</aside>