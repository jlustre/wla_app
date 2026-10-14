<?php

namespace App\Livewire\Admin;

use App\Models\AuditLog;
use App\Models\SponsorRelationship;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class DownlineManager extends Component
{
    use WithPagination;

    #[Url(as: 'q', history: true)]
    public string $search = '';

    public string $statusFilter = '';

    public string $sortBy = 'created_at';

    public string $sortDir = 'desc';

    public int $perPage = 10;

    public string $tab = 'directory';

    public ?int $viewingTreeRootId = null;

    public ?int $reassigningUserId = null;

    public string $newSponsorSearch = '';

    public ?int $newSponsorId = null;

    public ?string $newSponsorLabel = null;

    public string $reassignReason = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
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

        $this->resetPage();
    }

    public function viewInTree(int $userId): void
    {
        $this->viewingTreeRootId = $userId;
        $this->tab = 'tree';
    }

    public function startReassign(int $userId): void
    {
        $this->reassigningUserId = $userId;
        $this->newSponsorSearch = '';
        $this->newSponsorId = null;
        $this->newSponsorLabel = null;
        $this->reassignReason = '';
        $this->resetErrorBag();
    }

    public function cancelReassign(): void
    {
        $this->reassigningUserId = null;
    }

    public function pickSponsor(int $userId, string $label): void
    {
        $this->newSponsorId = $userId;
        $this->newSponsorLabel = $label;
        $this->newSponsorSearch = '';
    }

    public function confirmReassign(): void
    {
        $this->validate([
            'newSponsorId' => 'required|integer|exists:users,id',
            'reassignReason' => 'nullable|string|max:255',
        ]);

        $member = User::findOrFail($this->reassigningUserId);

        if ((int) $this->newSponsorId === $member->id) {
            $this->addError('newSponsorId', 'A member cannot sponsor themselves.');

            return;
        }

        $oldSponsorId = $member->sponsor_id;

        if ($oldSponsorId === (int) $this->newSponsorId) {
            $this->reassigningUserId = null;

            return;
        }

        $reason = $this->reassignReason !== '' ? $this->reassignReason : 'Reassigned via admin downline manager';

        DB::transaction(function () use ($member, $oldSponsorId, $reason) {
            if ($oldSponsorId) {
                SponsorRelationship::create([
                    'member_id' => $member->id,
                    'sponsor_id' => $oldSponsorId,
                    'sponsor_code' => 'SP-'.str_pad((string) $oldSponsorId, 4, '0', STR_PAD_LEFT),
                    'is_current' => false,
                    'linked_at' => $member->updated_at ?? $member->created_at,
                    'ended_at' => now(),
                    'metadata' => ['reason' => $reason, 'source' => 'admin-downline-manager'],
                ]);
            }

            $newSponsorId = (int) $this->newSponsorId;
            $member->sponsor_id = $newSponsorId;
            $member->save();

            AuditLog::create([
                'actor_id' => Auth::id(),
                'event' => 'sponsor.reassigned',
                'auditable_type' => User::class,
                'auditable_id' => $member->id,
                'old_values' => ['sponsor_id' => $oldSponsorId],
                'new_values' => ['sponsor_id' => $newSponsorId],
                'metadata' => ['reason' => $reason, 'source' => 'admin-downline-manager'],
                'ip_address' => request()->ip(),
                'user_agent' => (string) request()->userAgent(),
            ]);
        });

        session()->flash('success', 'Sponsor updated for '.$member->username.'.');
        $this->reassigningUserId = null;
        $this->resetPage();
    }

    public function sponsorOptions()
    {
        if (trim($this->newSponsorSearch) === '') {
            return collect();
        }

        $term = '%'.$this->newSponsorSearch.'%';

        return User::query()
            ->when($this->reassigningUserId, fn ($q) => $q->where('id', '!=', $this->reassigningUserId))
            ->where(function ($q) use ($term) {
                $q->where('username', 'like', $term)->orWhere('email', 'like', $term);
            })
            ->orderBy('username')
            ->limit(8)
            ->get(['id', 'username', 'email']);
    }

    protected function rows()
    {
        $query = User::query()
            ->with('sponsor')
            ->withCount([
                'directMembers',
                'companyAffiliations as active_companies_count' => function ($q) {
                    $q->where('status', 'active');
                },
            ]);

        if (trim($this->search) !== '') {
            $term = '%'.trim($this->search).'%';
            $query->where(function ($q) use ($term) {
                $q->where('username', 'like', $term)->orWhere('email', 'like', $term);
            });
        }

        if ($this->statusFilter !== '') {
            $query->where('status', $this->statusFilter);
        }

        $sortable = ['username', 'created_at', 'direct_members_count', 'status'];
        $sortBy = in_array($this->sortBy, $sortable, true) ? $this->sortBy : 'created_at';

        return $query->orderBy($sortBy, $this->sortDir)->paginate($this->perPage);
    }

    protected function stats(): array
    {
        $total = User::count();
        $active = User::where('status', 'active')->count();
        $roots = User::whereNull('sponsor_id')->count();

        $sponsorsWithDirect = User::whereNotNull('sponsor_id')->distinct()->count('sponsor_id');
        $directTotal = User::whereNotNull('sponsor_id')->count();
        $avgDirect = $sponsorsWithDirect > 0 ? round($directTotal / $sponsorsWithDirect, 1) : 0.0;

        $all = User::query()->select('id', 'sponsor_id')->get();
        $childrenMap = [];

        foreach ($all as $row) {
            if ($row->sponsor_id && $row->sponsor_id !== $row->id) {
                $childrenMap[$row->sponsor_id][] = $row->id;
            }
        }

        $rootIds = $all->whereNull('sponsor_id')->pluck('id');
        $maxDepth = 0;

        foreach ($rootIds as $rootId) {
            $maxDepth = max($maxDepth, $this->depthFrom($rootId, $childrenMap));
        }

        return [
            'total' => $total,
            'active' => $active,
            'roots' => $roots,
            'avgDirect' => $avgDirect,
            'maxDepth' => $maxDepth,
        ];
    }

    protected function depthFrom(int $id, array $childrenMap, array $visited = []): int
    {
        if (isset($visited[$id])) {
            return 0;
        }

        $visited[$id] = true;
        $children = $childrenMap[$id] ?? [];

        if ($children === []) {
            return 1;
        }

        $max = 0;

        foreach ($children as $childId) {
            $max = max($max, $this->depthFrom($childId, $childrenMap, $visited));
        }

        return 1 + $max;
    }

    public function render()
    {
        return view('livewire.admin.downline-manager', [
            'rows' => $this->rows(),
            'stats' => $this->stats(),
            'sponsorOptions' => $this->sponsorOptions(),
        ]);
    }
}
