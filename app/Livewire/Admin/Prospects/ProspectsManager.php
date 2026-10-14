<?php

namespace App\Livewire\Admin\Prospects;

use App\Models\Prospect;
use App\Models\Timeline;
use App\Models\User;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ProspectsManager extends Component
{
    use WithPagination;

    #[Url(as: 'q', history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $sourceFilter = '';

    #[Url(history: true)]
    public string $hotnessFilter = '';

    #[Url(history: true)]
    public string $ownerFilter = '';

    #[Url(history: true)]
    public string $followUpFrom = '';

    #[Url(history: true)]
    public string $followUpTo = '';

    public string $sortBy = 'created_at';

    public string $sortDir = 'desc';

    public int $perPage = 10;

    // Create / edit modal
    public bool $showFormModal = false;

    public ?int $editingId = null;

    public string $user_id = '';

    public string $first_name = '';

    public string $last_name = '';

    public string $email = '';

    public string $phone = '';

    public string $source = '';

    public string $hotness = '';

    public string $next_follow_up = '';

    public string $notes = '';

    // Delete confirm
    public ?int $deletingId = null;

    public array $sourceOptions = [
        'Facebook', 'Instagram', 'TikTok', 'YouTube', 'Twitter / X',
        'LinkedIn', 'Referral', 'Friend', 'Family', 'Website', 'Event', 'Other',
    ];

    public array $hotnessOptions = ['Hot', 'Warm', 'Cold'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingSourceFilter(): void
    {
        $this->resetPage();
    }

    public function updatingHotnessFilter(): void
    {
        $this->resetPage();
    }

    public function updatingOwnerFilter(): void
    {
        $this->resetPage();
    }

    public function updatingFollowUpFrom(): void
    {
        $this->resetPage();
    }

    public function updatingFollowUpTo(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'sourceFilter', 'hotnessFilter', 'ownerFilter', 'followUpFrom', 'followUpTo']);
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
        $prospect = Prospect::findOrFail($id);

        $this->editingId = $prospect->id;
        $this->user_id = (string) $prospect->user_id;
        $this->first_name = $prospect->first_name ?? '';
        $this->last_name = $prospect->last_name ?? '';
        $this->email = $prospect->email ?? '';
        $this->phone = $prospect->phone ?? '';
        $this->source = $prospect->source ?? '';
        $this->hotness = $prospect->hotness ?? '';
        $this->next_follow_up = optional($prospect->next_follow_up)->format('Y-m-d\TH:i') ?? '';
        $this->notes = $prospect->notes ?? '';
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
        $this->reset([
            'user_id', 'first_name', 'last_name', 'email', 'phone',
            'source', 'hotness', 'next_follow_up', 'notes', 'editingId',
        ]);
        $this->resetErrorBag();
    }

    protected function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'source' => 'nullable|string|max:255',
            'hotness' => 'nullable|string|max:50',
            'next_follow_up' => 'nullable|date',
            'notes' => 'nullable|string|max:5000',
        ];
    }

    public function save(): void
    {
        $this->validate();

        if (trim($this->first_name) === '' && trim($this->last_name) === '') {
            $this->addError('first_name', 'Please provide at least a first name or a last name.');

            return;
        }

        if (trim($this->email) === '' && trim($this->phone) === '') {
            $this->addError('email', 'Please provide at least an email address or a phone number.');

            return;
        }

        $userId = (int) $this->user_id;

        $duplicate = Prospect::where('user_id', $userId)
            ->when($this->editingId, fn ($q) => $q->where('id', '!=', $this->editingId))
            ->where(function ($q) {
                $q->when(trim($this->email) !== '', fn ($qq) => $qq->orWhere('email', $this->email));
                $q->when(trim($this->phone) !== '', fn ($qq) => $qq->orWhere('phone', $this->phone));
            })
            ->exists();

        if ($duplicate) {
            $this->addError('email', 'This owner already has a prospect with this email or phone number.');

            return;
        }

        $data = [
            'user_id' => $userId,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email ?: null,
            'phone' => $this->phone ?: null,
            'source' => $this->source ?: null,
            'hotness' => $this->hotness ?: null,
            'next_follow_up' => $this->next_follow_up ?: null,
            'notes' => $this->notes ?: null,
        ];

        if ($this->editingId) {
            Prospect::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Prospect updated successfully.');
        } else {
            $prospect = Prospect::create($data);
            Timeline::create(['prospect_id' => $prospect->id, 'action' => 'Added', 'action_at' => now()]);
            session()->flash('success', 'Prospect added successfully.');
        }

        $this->showFormModal = false;
        $this->resetForm();
    }

    public function confirmDelete(int $id): void
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

        Prospect::where('id', $this->deletingId)->delete();

        session()->flash('success', 'Prospect deleted.');
        $this->deletingId = null;
        $this->resetPage();
    }

    protected function filterOptions(): array
    {
        return [
            'sources' => Prospect::whereNotNull('source')->where('source', '!=', '')->distinct()->orderBy('source')->pluck('source')->all(),
            'hotness' => Prospect::whereNotNull('hotness')->where('hotness', '!=', '')->distinct()->orderBy('hotness')->pluck('hotness')->all(),
            'owners' => User::whereIn('id', Prospect::select('user_id')->distinct())->orderBy('username')->get(['id', 'username']),
        ];
    }

    protected function stats(): array
    {
        return [
            'total' => Prospect::count(),
            'hot' => Prospect::where('hotness', 'Hot')->count(),
            'due_this_week' => Prospect::whereBetween('next_follow_up', [now(), now()->addDays(7)])->count(),
            'added_this_week' => Prospect::where('created_at', '>=', now()->subDays(7))->count(),
        ];
    }

    protected function rows()
    {
        $query = Prospect::query()->with('user');

        if (trim($this->search) !== '') {
            $term = '%'.trim($this->search).'%';
            $query->where(function ($q) use ($term) {
                $q->where('first_name', 'like', $term)
                    ->orWhere('last_name', 'like', $term)
                    ->orWhere('email', 'like', $term)
                    ->orWhere('phone', 'like', $term)
                    ->orWhere('source', 'like', $term);
            });
        }

        if ($this->sourceFilter !== '') {
            $query->where('source', $this->sourceFilter);
        }

        if ($this->hotnessFilter !== '') {
            $query->where('hotness', $this->hotnessFilter);
        }

        if ($this->ownerFilter !== '') {
            $query->where('user_id', $this->ownerFilter);
        }

        if ($this->followUpFrom !== '') {
            $query->whereDate('next_follow_up', '>=', $this->followUpFrom);
        }

        if ($this->followUpTo !== '') {
            $query->whereDate('next_follow_up', '<=', $this->followUpTo);
        }

        $sortable = ['first_name', 'created_at', 'next_follow_up', 'hotness'];
        $sortBy = in_array($this->sortBy, $sortable, true) ? $this->sortBy : 'created_at';

        return $query->orderBy($sortBy, $this->sortDir)->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.admin.prospects.prospects-manager', [
            'rows' => $this->rows(),
            'stats' => $this->stats(),
            'filterOptions' => $this->filterOptions(),
            'allOwners' => User::orderBy('username')->get(['id', 'username']),
        ]);
    }
}
