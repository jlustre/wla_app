<?php

namespace App\Livewire\Admin\SponsorRelationships;

use App\Models\SponsorRelationship;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Admin view of the sponsor_relationships table.
 *
 * Important: this table is a historical LOG of sponsor links, not the
 * live source of truth — a member's actual current sponsor is always
 * User.sponsor_id, changed only via the Downline Tree's reassignment
 * flow (App\Livewire\Admin\DownlineManager), which writes User.sponsor_id
 * directly and only logs the OLD link here when it closes one out. So
 * most live links have no matching row in this table at all. This
 * manager lets admins browse/correct the log and backfill missing
 * "current" rows for reporting — it never writes to User.sponsor_id
 * itself, to avoid a second, divergent path for changing who actually
 * sponsors whom.
 */
class SponsorRelationshipsManager extends Component
{
    use WithPagination;

    #[Url(as: 'q', history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $statusFilter = '';

    public string $sortBy = 'linked_at';

    public string $sortDir = 'desc';

    public int $perPage = 10;

    // Create / edit modal
    public bool $showFormModal = false;

    public ?int $editingId = null;

    public string $member_id = '';

    public string $sponsor_id = '';

    public string $sponsor_code = '';

    public bool $is_current = true;

    public string $linked_at = '';

    public string $ended_at = '';

    public string $reason = '';

    // Delete confirm
    public ?int $deletingId = null;

    // View detail modal
    public ?int $viewingId = null;

    // Backfill confirm
    public bool $showBackfillModal = false;

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
        $this->linked_at = now()->format('Y-m-d\TH:i');
        $this->showFormModal = true;
    }

    public function openEdit(int $id): void
    {
        $row = SponsorRelationship::findOrFail($id);

        $this->editingId = $row->id;
        $this->member_id = (string) $row->member_id;
        $this->sponsor_id = $row->sponsor_id ? (string) $row->sponsor_id : '';
        $this->sponsor_code = $row->sponsor_code ?? '';
        $this->is_current = $row->is_current;
        $this->linked_at = optional($row->linked_at)->format('Y-m-d\TH:i') ?? '';
        $this->ended_at = optional($row->ended_at)->format('Y-m-d\TH:i') ?? '';
        $this->reason = $row->metadata['reason'] ?? '';
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
        $this->reset(['member_id', 'sponsor_id', 'sponsor_code', 'linked_at', 'ended_at', 'reason', 'editingId']);
        $this->is_current = true;
        $this->resetErrorBag();
    }

    protected function rules(): array
    {
        return [
            'member_id' => ['required', 'exists:users,id'],
            'sponsor_id' => ['nullable', 'exists:users,id', 'different:member_id'],
            'sponsor_code' => ['nullable', 'string', 'max:64'],
            'linked_at' => ['nullable', 'date'],
            'ended_at' => ['nullable', 'date'],
            'reason' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'member_id' => (int) $this->member_id,
            'sponsor_id' => $this->sponsor_id !== '' ? (int) $this->sponsor_id : null,
            'sponsor_code' => $this->sponsor_code !== '' ? $this->sponsor_code : null,
            'is_current' => $this->is_current,
            'linked_at' => $this->linked_at !== '' ? $this->linked_at : null,
            'ended_at' => $this->ended_at !== '' ? $this->ended_at : null,
            'metadata' => array_filter(['reason' => $this->reason !== '' ? $this->reason : null, 'source' => 'admin-sponsor-relationships-manager']),
        ];

        DB::transaction(function () use ($data) {
            // Keep "current" meaning exactly one row per member: closing out
            // any other row already marked current for this member.
            if ($data['is_current']) {
                SponsorRelationship::where('member_id', $data['member_id'])
                    ->where('is_current', true)
                    ->when($this->editingId, fn ($q) => $q->where('id', '!=', $this->editingId))
                    ->update(['is_current' => false, 'ended_at' => now()]);
            }

            if ($this->editingId) {
                SponsorRelationship::findOrFail($this->editingId)->update($data);
            } else {
                SponsorRelationship::create($data);
            }
        });

        session()->flash('success', $this->editingId ? 'Sponsor relationship updated.' : 'Sponsor relationship logged.');

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

        SponsorRelationship::where('id', $this->deletingId)->delete();

        session()->flash('success', 'Sponsor relationship record deleted.');
        $this->deletingId = null;
        $this->resetPage();
    }

    public function restore(int $id): void
    {
        SponsorRelationship::onlyTrashed()->findOrFail($id)->restore();

        session()->flash('success', 'Sponsor relationship record restored.');
        $this->resetPage();
    }

    public function viewDetails(int $id): void
    {
        $this->viewingId = SponsorRelationship::withTrashed()->findOrFail($id)->id;
    }

    public function closeDetails(): void
    {
        $this->viewingId = null;
    }

    public function openBackfillConfirm(): void
    {
        $this->showBackfillModal = true;
    }

    public function cancelBackfill(): void
    {
        $this->showBackfillModal = false;
    }

    /**
     * Create a matching is_current=true log row for every live User whose
     * sponsor_id isn't represented in the log yet — closing this table's
     * biggest gap (see class docblock) without touching User.sponsor_id.
     */
    public function runBackfill(): void
    {
        $missing = $this->missingBackfillQuery()->get(['id', 'sponsor_id', 'created_at', 'updated_at']);

        DB::transaction(function () use ($missing) {
            foreach ($missing as $user) {
                SponsorRelationship::create([
                    'member_id' => $user->id,
                    'sponsor_id' => $user->sponsor_id,
                    'sponsor_code' => 'SP-'.str_pad((string) $user->sponsor_id, 4, '0', STR_PAD_LEFT),
                    'is_current' => true,
                    'linked_at' => $user->updated_at ?? $user->created_at,
                    'metadata' => ['source' => 'admin-backfill'],
                ]);
            }
        });

        session()->flash('success', 'Backfilled '.$missing->count().' missing sponsor relationship record(s).');
        $this->showBackfillModal = false;
        $this->resetPage();
    }

    protected function missingBackfillQuery()
    {
        return User::query()
            ->whereNotNull('sponsor_id')
            ->whereNotIn('id', SponsorRelationship::where('is_current', true)->select('member_id'));
    }

    protected function baseQuery()
    {
        $query = $this->statusFilter === 'trashed'
            ? SponsorRelationship::onlyTrashed()
            : SponsorRelationship::query();

        $query->with(['member', 'sponsor']);

        if (trim($this->search) !== '') {
            $term = '%'.trim($this->search).'%';
            $query->where(function ($q) use ($term) {
                $q->whereHas('member', fn ($m) => $m->where('username', 'like', $term)->orWhere('email', 'like', $term))
                    ->orWhereHas('sponsor', fn ($s) => $s->where('username', 'like', $term)->orWhere('email', 'like', $term))
                    ->orWhere('sponsor_code', 'like', $term);
            });
        }

        if ($this->statusFilter === 'current') {
            $query->where('is_current', true);
        } elseif ($this->statusFilter === 'ended') {
            $query->where('is_current', false);
        }

        return $query;
    }

    protected function rows()
    {
        $sortable = ['linked_at', 'ended_at', 'is_current', 'created_at'];
        $sortBy = in_array($this->sortBy, $sortable, true) ? $this->sortBy : 'linked_at';

        return $this->baseQuery()->orderBy($sortBy, $this->sortDir)->paginate($this->perPage);
    }

    protected function stats(): array
    {
        return [
            'total' => SponsorRelationship::count(),
            'current' => SponsorRelationship::where('is_current', true)->count(),
            'ended' => SponsorRelationship::where('is_current', false)->count(),
            'missing' => $this->missingBackfillQuery()->count(),
        ];
    }

    public function render()
    {
        return view('livewire.admin.sponsor-relationships.sponsor-relationships-manager', [
            'rows' => $this->rows(),
            'stats' => $this->stats(),
            'userOptions' => User::orderBy('username')->get(['id', 'username']),
            'viewingRow' => $this->viewingId
                ? SponsorRelationship::withTrashed()->with(['member', 'sponsor'])->find($this->viewingId)
                : null,
        ]);
    }
}
