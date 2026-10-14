<?php

namespace App\Livewire\Admin\Notifications;

use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Admin view of Laravel's built-in `notifications` table. Unlike Activity
 * and AuditLog, a notification's read_at is designed to be mutated
 * (DatabaseNotification::markAsRead()/markAsUnread() are framework
 * methods) and deleting old notifications is a completely normal action
 * — so this page supports real management, not just read-only reporting.
 * It reads/writes through the exact same Eloquent model the member-facing
 * bell dropdown (App\Livewire\Dashboard\NotificationDropdown) and
 * notification feed (App\Livewire\Member\ContentHub) already use, so any
 * action taken here is immediately consistent with what a member sees —
 * there's no separate store to desync from.
 */
class NotificationsManager extends Component
{
    use WithPagination;

    #[Url(as: 'q', history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $typeFilter = '';

    #[Url(history: true)]
    public string $readFilter = '';

    #[Url(history: true)]
    public string $recipientFilter = '';

    public string $sortDir = 'desc';

    public int $perPage = 20;

    public ?string $viewingId = null;

    public ?string $deletingId = null;

    public bool $showBulkReadModal = false;

    public bool $showBulkDeleteModal = false;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingTypeFilter(): void
    {
        $this->resetPage();
    }

    public function updatingReadFilter(): void
    {
        $this->resetPage();
    }

    public function updatingRecipientFilter(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'typeFilter', 'readFilter', 'recipientFilter']);
        $this->resetPage();
    }

    public function toggleSortDir(): void
    {
        $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
    }

    public function markRead(string $id): void
    {
        DatabaseNotification::whereKey($id)->first()?->markAsRead();
    }

    public function markUnread(string $id): void
    {
        DatabaseNotification::whereKey($id)->first()?->markAsUnread();
    }

    public function viewDetails(string $id): void
    {
        $this->viewingId = DatabaseNotification::findOrFail($id)->id;
    }

    public function closeDetails(): void
    {
        $this->viewingId = null;
    }

    public function confirmDelete(string $id): void
    {
        $this->deletingId = $id;
    }

    public function cancelDelete(): void
    {
        $this->deletingId = null;
    }

    public function delete(): void
    {
        if (! $this->deletingId) {
            return;
        }

        DatabaseNotification::whereKey($this->deletingId)->delete();

        session()->flash('success', 'Notification deleted.');
        $this->deletingId = null;
        $this->resetPage();
    }

    public function openBulkRead(): void
    {
        $this->showBulkReadModal = true;
    }

    public function cancelBulkRead(): void
    {
        $this->showBulkReadModal = false;
    }

    public function bulkMarkFilteredRead(): void
    {
        $count = 0;

        $this->filteredQuery()->whereNull('read_at')->get()->each(function (DatabaseNotification $n) use (&$count) {
            $n->markAsRead();
            $count++;
        });

        session()->flash('success', "Marked {$count} notification(s) as read.");
        $this->showBulkReadModal = false;
    }

    public function openBulkDelete(): void
    {
        $this->showBulkDeleteModal = true;
    }

    public function cancelBulkDelete(): void
    {
        $this->showBulkDeleteModal = false;
    }

    public function bulkDeleteFilteredRead(): void
    {
        $count = $this->filteredQuery()->whereNotNull('read_at')->count();

        $this->filteredQuery()->whereNotNull('read_at')->delete();

        session()->flash('success', "Deleted {$count} read notification(s).");
        $this->showBulkDeleteModal = false;
        $this->resetPage();
    }

    public static function dataValue(DatabaseNotification $notification, string $key, $default = null)
    {
        return data_get($notification->data, $key, $default);
    }

    public static function typeLabel(DatabaseNotification $notification): string
    {
        return Str::headline((string) self::dataValue($notification, 'type', 'system'));
    }

    protected function filteredQuery()
    {
        $query = DatabaseNotification::query()->where('notifiable_type', User::class);

        if (trim($this->search) !== '') {
            $term = '%'.trim($this->search).'%';
            $matchingUserIds = User::withTrashed()
                ->where(fn ($q) => $q->where('username', 'like', $term)->orWhere('email', 'like', $term))
                ->pluck('id');

            $query->where(function ($q) use ($term, $matchingUserIds) {
                $q->where('data->title', 'like', $term)
                    ->orWhere('data->message', 'like', $term)
                    ->orWhere('data->type', 'like', $term)
                    ->orWhereIn('notifiable_id', $matchingUserIds);
            });
        }

        if ($this->typeFilter !== '') {
            $query->where('data->type', $this->typeFilter);
        }

        if ($this->readFilter === 'read') {
            $query->whereNotNull('read_at');
        } elseif ($this->readFilter === 'unread') {
            $query->whereNull('read_at');
        }

        if ($this->recipientFilter !== '') {
            $query->where('notifiable_id', $this->recipientFilter);
        }

        return $query;
    }

    protected function rows()
    {
        return $this->filteredQuery()->orderBy('created_at', $this->sortDir)->paginate($this->perPage);
    }

    protected function stats(): array
    {
        $base = DatabaseNotification::where('notifiable_type', User::class);

        return [
            'total' => (clone $base)->count(),
            'unread' => (clone $base)->whereNull('read_at')->count(),
            'read' => (clone $base)->whereNotNull('read_at')->count(),
            'recipients' => (clone $base)->distinct('notifiable_id')->count('notifiable_id'),
        ];
    }

    protected function filterOptions(): array
    {
        $types = DatabaseNotification::where('notifiable_type', User::class)
            ->selectRaw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.type')) as extracted_type")
            ->distinct()
            ->pluck('extracted_type')
            ->filter()
            ->sort()
            ->values();

        $recipientIds = DatabaseNotification::where('notifiable_type', User::class)
            ->select('notifiable_id')
            ->distinct()
            ->pluck('notifiable_id');

        $recipients = User::withTrashed()->whereIn('id', $recipientIds)->orderBy('username')->get(['id', 'username']);

        return ['types' => $types, 'recipients' => $recipients];
    }

    public function render()
    {
        $rows = $this->rows();

        $recipientCache = User::withTrashed()
            ->whereIn('id', collect($rows->items())->pluck('notifiable_id')->unique())
            ->get(['id', 'username', 'email'])
            ->keyBy('id');

        return view('livewire.admin.notifications.notifications-manager', [
            'rows' => $rows,
            'recipientCache' => $recipientCache,
            'stats' => $this->stats(),
            'filterOptions' => $this->filterOptions(),
            'viewingNotification' => $this->viewingId
                ? DatabaseNotification::find($this->viewingId)
                : null,
        ]);
    }
}
