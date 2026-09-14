<?php

namespace App\Livewire\Dashboard;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;

class NotificationDropdown extends Component
{
    public array $notifications = [];

    public function mount(): void
    {
        $this->loadNotifications();
    }

    public function markAsRead(string $notificationId): void
    {
        $user = Auth::user();

        if ($user && Schema::hasTable('notifications')) {
            $notification = $user->notifications()->whereKey($notificationId)->first();

            if ($notification && ! $notification->read_at) {
                $notification->markAsRead();
            }
        }

        $this->loadNotifications();
    }

    public function markAllAsRead(): void
    {
        $user = Auth::user();

        if ($user && Schema::hasTable('notifications')) {
            $user->unreadNotifications->markAsRead();
        }

        $this->loadNotifications();
    }

    public function render()
    {
        return view('livewire.dashboard.notification-dropdown', [
            'unreadCount' => collect($this->notifications)->whereNull('read_at')->count(),
        ]);
    }

    protected function loadNotifications(): void
    {
        $user = Auth::user();

        if ($user && Schema::hasTable('notifications')) {
            $this->notifications = $user->notifications()->latest()->take(6)->get()->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => data_get($notification->data, 'title', 'Member notification'),
                    'message' => data_get($notification->data, 'message', 'A new update is available.'),
                    'time' => optional($notification->created_at)->diffForHumans() ?? 'Just now',
                    'type' => data_get($notification->data, 'type', 'system'),
                    'read_at' => $notification->read_at,
                ];
            })->all();

            return;
        }

        $this->notifications = [
            ['id' => '1', 'title' => 'New member joined', 'message' => 'A direct invite registered through your sponsor link.', 'time' => '10 minutes ago', 'type' => 'member', 'read_at' => null],
            ['id' => '2', 'title' => 'Sponsor message', 'message' => 'Your sponsor shared updated webinar talking points.', 'time' => '2 hours ago', 'type' => 'message', 'read_at' => null],
            ['id' => '3', 'title' => 'Training reminder', 'message' => 'Risk management training is still incomplete.', 'time' => 'Yesterday', 'type' => 'training', 'read_at' => now()->subDay()->toDateTimeString()],
            ['id' => '4', 'title' => 'Security alert', 'message' => 'A new session was detected on your account.', 'time' => '2 days ago', 'type' => 'security', 'read_at' => now()->subDays(2)->toDateTimeString()],
        ];
    }
}