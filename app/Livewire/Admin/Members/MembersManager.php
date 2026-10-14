<?php

namespace App\Livewire\Admin\Members;

use App\Enums\UserStatus;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class MembersManager extends Component
{
    use WithPagination;

    #[Url(as: 'q', history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $statusFilter = '';

    #[Url(history: true)]
    public string $roleFilter = '';

    public string $sortBy = 'created_at';

    public string $sortDir = 'desc';

    public int $perPage = 10;

    // Create / edit modal
    public bool $showFormModal = false;

    public ?int $editingId = null;

    public string $username = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public string $status = 'active';

    public string $role = 'member';

    public string $sponsor_id = '';

    // Delete confirm
    public ?int $deletingId = null;

    // View detail modal
    public ?int $viewingId = null;

    // Reset password modal
    public ?int $resettingId = null;

    public string $newPassword = '';

    public string $newPassword_confirmation = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingRoleFilter(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'statusFilter', 'roleFilter']);
        $this->resetPage();
    }

    public function toggleSort(string $field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDir = 'asc';
        }
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->editingId = null;
        $this->showFormModal = true;
    }

    public function openEdit(int $id): void
    {
        $user = User::findOrFail($id);

        $this->editingId = $user->id;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->password = '';
        $this->password_confirmation = '';
        $this->status = $user->status instanceof UserStatus ? $user->status->value : (string) $user->status;
        $this->role = $user->getRoleNames()->first() ?? 'member';
        $this->sponsor_id = $user->sponsor_id ? (string) $user->sponsor_id : '';
        $this->resetErrorBag();
        $this->showFormModal = true;
    }

    public function closeModal(): void
    {
        $this->showFormModal = false;
        $this->resetForm();
    }

    protected function resetForm(): void
    {
        $this->reset(['username', 'email', 'password', 'password_confirmation', 'sponsor_id', 'editingId']);
        $this->status = 'active';
        $this->role = 'member';
        $this->resetErrorBag();
    }

    protected function rules(): array
    {
        $userId = $this->editingId;

        $rules = [
            'username' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:users,username'.($userId ? ",{$userId}" : '')],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'.($userId ? ",{$userId}" : '')],
            'status' => ['required', 'in:active,inactive,pending,suspended'],
            'role' => ['required', 'exists:roles,name'],
        ];

        if (! $userId) {
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
            $rules['sponsor_id'] = ['nullable', 'exists:users,id'];
        }

        return $rules;
    }

    public function save(): void
    {
        $this->validate();

        if (! in_array($this->role, $this->assignableRoleNames(), true)) {
            $this->addError('role', 'You are not allowed to assign that role.');

            return;
        }

        if ($this->editingId) {
            $user = User::findOrFail($this->editingId);

            if ($user->id === Auth::id() && $this->status !== 'active') {
                $this->addError('status', 'You cannot change your own status.');

                return;
            }

            $oldStatus = $user->status instanceof UserStatus ? $user->status->value : (string) $user->status;
            $oldRole = $user->getRoleNames()->first();

            $user->update([
                'username' => $this->username,
                'email' => $this->email,
                'status' => $this->status,
            ]);

            if ($oldStatus !== $this->status) {
                $this->logAudit($user, 'member.status_changed', ['status' => $oldStatus], ['status' => $this->status]);
            }

            if ($oldRole !== $this->role) {
                $user->syncRoles([$this->role]);
                $this->logAudit($user, 'member.role_changed', ['role' => $oldRole], ['role' => $this->role]);
            }

            session()->flash('success', 'Member updated successfully.');
        } else {
            $user = User::create([
                'username' => $this->username,
                'email' => $this->email,
                'password' => $this->password,
                'status' => $this->status,
                'sponsor_id' => $this->sponsor_id !== '' ? (int) $this->sponsor_id : null,
            ]);

            $user->assignRole($this->role);
            $user->forceFill(['email_verified_at' => now()])->save();

            $this->logAudit($user, 'member.created', [], [
                'username' => $user->username,
                'email' => $user->email,
                'status' => $this->status,
                'role' => $this->role,
            ]);

            session()->flash('success', 'Member created successfully.');
        }

        $this->showFormModal = false;
        $this->resetForm();
    }

    public function confirmDelete(int $id): void
    {
        if ($id === Auth::id()) {
            session()->flash('error', 'You cannot delete your own account.');

            return;
        }

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

        $user = User::findOrFail($this->deletingId);
        $user->delete();

        $this->logAudit($user, 'member.deleted', [], []);

        session()->flash('success', 'Member deleted.');
        $this->deletingId = null;
        $this->resetPage();
    }

    public function restore(int $id): void
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        $this->logAudit($user, 'member.restored', [], []);

        session()->flash('success', 'Member restored.');
        $this->resetPage();
    }

    public function viewDetails(int $id): void
    {
        $this->viewingId = User::withTrashed()->findOrFail($id)->id;
    }

    public function closeDetails(): void
    {
        $this->viewingId = null;
    }

    public function openResetPassword(int $id): void
    {
        User::findOrFail($id);

        $this->resettingId = $id;
        $this->newPassword = '';
        $this->newPassword_confirmation = '';
        $this->resetErrorBag();
    }

    public function cancelResetPassword(): void
    {
        $this->reset(['resettingId', 'newPassword', 'newPassword_confirmation']);
        $this->resetErrorBag();
    }

    public function saveResetPassword(): void
    {
        $this->validate([
            'newPassword' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::findOrFail($this->resettingId);
        $user->update(['password' => $this->newPassword]);

        $this->logAudit($user, 'member.password_reset', [], []);

        session()->flash('success', 'Password reset for '.$user->username.'.');
        $this->cancelResetPassword();
    }

    protected function logAudit(User $user, string $event, array $old, array $new): void
    {
        AuditLog::create([
            'actor_id' => Auth::id(),
            'event' => $event,
            'auditable_type' => User::class,
            'auditable_id' => $user->id,
            'old_values' => $old,
            'new_values' => $new,
            'metadata' => ['source' => 'admin-members-manager'],
            'ip_address' => request()->ip(),
            'user_agent' => (string) request()->userAgent(),
        ]);
    }

    protected function assignableRoleNames(): array
    {
        $roles = Role::orderBy('name')->pluck('name')->all();

        if (! Auth::user()?->hasRole('super-admin')) {
            $roles = array_values(array_diff($roles, ['super-admin']));
        }

        return $roles;
    }

    protected function rows()
    {
        $base = $this->statusFilter === 'trashed'
            ? User::onlyTrashed()
            : User::query();

        $query = $base->with(['profile', 'roles', 'sponsor'])->withCount('directMembers');

        if (trim($this->search) !== '') {
            $term = '%'.trim($this->search).'%';
            $query->where(function ($q) use ($term) {
                $q->where('username', 'like', $term)->orWhere('email', 'like', $term);
            });
        }

        if ($this->statusFilter !== '' && $this->statusFilter !== 'trashed') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->roleFilter !== '') {
            $query->whereHas('roles', fn ($q) => $q->where('name', $this->roleFilter));
        }

        $sortable = ['username', 'email', 'created_at', 'status', 'direct_members_count'];
        $sortBy = in_array($this->sortBy, $sortable, true) ? $this->sortBy : 'created_at';

        return $query->orderBy($sortBy, $this->sortDir)->paginate($this->perPage);
    }

    protected function stats(): array
    {
        return [
            'total' => User::count(),
            'active' => User::where('status', 'active')->count(),
            'pending' => User::where('status', 'pending')->count(),
            'suspended' => User::where('status', 'suspended')->count(),
            'new_this_week' => User::where('created_at', '>=', now()->subDays(7))->count(),
            'trashed' => User::onlyTrashed()->count(),
        ];
    }

    protected function filterOptions(): array
    {
        return [
            'roles' => Role::orderBy('name')->pluck('name')->all(),
        ];
    }

    public function render()
    {
        $viewingUser = $this->viewingId
            ? User::withTrashed()->with(['profile', 'roles', 'sponsor'])->withCount(['directMembers', 'companyAffiliations'])->find($this->viewingId)
            : null;

        return view('livewire.admin.members.members-manager', [
            'rows' => $this->rows(),
            'stats' => $this->stats(),
            'filterOptions' => $this->filterOptions(),
            'assignableRoles' => $this->assignableRoleNames(),
            'sponsorOptions' => User::orderBy('username')->get(['id', 'username']),
            'viewingUser' => $viewingUser,
            'resettingUser' => $this->resettingId ? User::find($this->resettingId) : null,
        ]);
    }
}
