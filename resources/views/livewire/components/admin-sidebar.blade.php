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
    <div class="p-6 font-bold text-xl border-b">WLA Admin</div>
    <nav class="flex-1 p-4">
        <ul class="space-y-2">
            <li><a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded hover:bg-gray-200">Dashboard</a>
            </li>
            <li><a href="#" class="block px-3 py-2 rounded hover:bg-gray-200">Users</a></li>
            <li><a href="#" class="block px-3 py-2 rounded hover:bg-gray-200">Companies</a></li>
            <li><a href="#" class="block px-3 py-2 rounded hover:bg-gray-200">Company Rules</a></li>
            <li><a href="#" class="block px-3 py-2 rounded hover:bg-gray-200">Memberships</a></li>
            <li><a href="#" class="block px-3 py-2 rounded hover:bg-gray-200">Placements</a></li>
            <li><a href="#" class="block px-3 py-2 rounded hover:bg-gray-200">Audit Logs</a></li>
        </ul>
    </nav>
</aside>