<?php

namespace App\Livewire\Dashboard;

use App\Support\AdminSidebarMenu;
use App\Support\MemberSidebarMenu;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Sidebar extends Component
{
    public bool $mobile = false;

    public string $workspace = 'member';

    public function render()
    {
        $user = Auth::user();
        $isAdminWorkspace = $this->workspace === 'admin';
        $isAdmin = $user && method_exists($user, 'hasAnyRole')
            ? $user->hasAnyRole(['admin', 'super-admin'])
            : false;

        return view('livewire.dashboard.sidebar', [
            'menuGroups' => $isAdminWorkspace ? AdminSidebarMenu::groups() : MemberSidebarMenu::groups(),
            'switchLabel' => $isAdminWorkspace ? 'Member' : 'Admin',
            'switchItems' => $isAdminWorkspace
                ? [
                    ['label' => 'Member Dashboard', 'route' => 'member.dashboard', 'icon' => 'home'],
                ]
                : ($isAdmin ? MemberSidebarMenu::adminItems() : []),
            'brandEyebrow' => $isAdminWorkspace ? 'Admin Platform' : 'Sponsor Platform',
            'brandSubtitle' => $isAdminWorkspace ? 'Admin workspace' : 'Premium member workspace',
            'user' => $user,
        ]);
    }
}
