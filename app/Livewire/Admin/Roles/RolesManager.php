<?php

namespace App\Livewire\Admin\Roles;

use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesManager extends Component
{
    use WithPagination;

    /**
     * Roles whose exact name string is depended on elsewhere in the app
     * (the admin route gate, login redirect, sidebar visibility, etc.) —
     * see role:admin|super-admin middleware and the several hasRole()
     * checks across the app. Renaming or deleting these would silently
     * break access control, so the UI blocks both.
     */
    public const PROTECTED_ROLES = ['super-admin', 'admin', 'member'];

    #[Url(as: 'q', history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $statusFilter = '';

    public string $sortBy = 'name';

    public string $sortDir = 'asc';

    public int $perPage = 10;

    // Create / edit modal
    public bool $showFormModal = false;

    public ?int $editingId = null;

    public string $name = '';

    public string $guard_name = 'web';

    public string $permissionsInput = '';

    // Delete confirm
    public ?int $deletingId = null;

    // View detail modal
    public ?int $viewingId = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'statusFilter']);
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
        $role = Role::with('permissions')->findOrFail($id);

        $this->editingId = $role->id;
        $this->name = $role->name;
        $this->guard_name = $role->guard_name;
        $this->permissionsInput = $role->permissions->pluck('name')->implode(', ');
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
        $this->reset(['name', 'permissionsInput', 'editingId']);
        $this->guard_name = 'web';
        $this->resetErrorBag();
    }

    protected function isProtected(string $roleName): bool
    {
        return in_array($roleName, self::PROTECTED_ROLES, true);
    }

    protected function rules(): array
    {
        $roleId = $this->editingId;

        return [
            'name' => [
                'required', 'string', 'max:255', 'alpha_dash',
                'unique:roles,name'.($roleId ? ",{$roleId}" : ''),
            ],
            'guard_name' => ['required', 'string', 'max:255'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        if ($this->editingId) {
            $role = Role::findOrFail($this->editingId);

            if ($this->isProtected($role->name) && $role->name !== $this->name) {
                $this->addError('name', 'This role is used by the app\'s access rules and cannot be renamed.');

                return;
            }

            $role->update(['name' => $this->name]);
        } else {
            $role = Role::create([
                'name' => $this->name,
                'guard_name' => $this->guard_name,
            ]);
        }

        $permissionNames = collect(explode(',', $this->permissionsInput))
            ->map(fn ($p) => trim($p))
            ->filter(fn ($p) => $p !== '')
            ->unique()
            ->values();

        $permissions = $permissionNames->map(
            fn ($p) => Permission::firstOrCreate(['name' => $p, 'guard_name' => $role->guard_name])
        );

        $role->syncPermissions($permissions);

        session()->flash('success', $this->editingId ? 'Role updated successfully.' : 'Role created successfully.');

        $this->showFormModal = false;
        $this->resetForm();
    }

    public function confirmDelete(int $id): void
    {
        $role = Role::withCount('users')->findOrFail($id);

        if ($this->isProtected($role->name)) {
            session()->flash('error', 'The "'.$role->name.'" role is used by the app\'s access rules and cannot be deleted.');

            return;
        }

        if ($role->users_count > 0) {
            session()->flash('error', 'Cannot delete "'.$role->name.'" — it is still assigned to '.$role->users_count.' member(s).');

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

        Role::where('id', $this->deletingId)->delete();

        session()->flash('success', 'Role deleted.');
        $this->deletingId = null;
        $this->resetPage();
    }

    public function viewDetails(int $id): void
    {
        $this->viewingId = Role::findOrFail($id)->id;
    }

    public function closeDetails(): void
    {
        $this->viewingId = null;
    }

    protected function rows()
    {
        $query = Role::query()->withCount('users');

        if (trim($this->search) !== '') {
            $query->where('name', 'like', '%'.trim($this->search).'%');
        }

        if ($this->statusFilter === 'unused') {
            $query->having('users_count', 0);
        }

        $sortable = ['name', 'guard_name', 'users_count', 'created_at'];
        $sortBy = in_array($this->sortBy, $sortable, true) ? $this->sortBy : 'name';

        return $query->orderBy($sortBy, $this->sortDir)->paginate($this->perPage);
    }

    protected function stats(): array
    {
        return [
            'total_roles' => Role::count(),
            'total_assignments' => Role::withCount('users')->get()->sum('users_count'),
            'unused_roles' => Role::doesntHave('users')->count(),
            'total_permissions' => Permission::count(),
        ];
    }

    public function render()
    {
        $viewingRole = $this->viewingId
            ? Role::with(['permissions', 'users' => fn ($q) => $q->orderBy('username')->limit(25)])->withCount('users')->find($this->viewingId)
            : null;

        return view('livewire.admin.roles.roles-manager', [
            'rows' => $this->rows(),
            'stats' => $this->stats(),
            'viewingRole' => $viewingRole,
            'protectedRoles' => self::PROTECTED_ROLES,
        ]);
    }
}
