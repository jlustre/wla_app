<?php

namespace App\Livewire\Admin\Prospects;

use App\Models\Prospect;
use App\Models\Timeline;
use App\Models\User;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * Admin-wide version of the member "Prospect Funnel" board (see
 * App\Livewire\Member\ProspectFunnel): every prospect across every member,
 * bucketed by the funnel stage of their most recent logged activity, with
 * drag-and-drop (or the "Move" menu) to resort a card into another stage,
 * create/remove to manage pipeline membership, and a detail popup to view
 * a prospect's full activity history.
 */
class PipelineManager extends Component
{
    #[Url(as: 'q', history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $ownerFilter = '';

    #[Url(history: true)]
    public string $sourceFilter = '';

    #[Url(history: true)]
    public string $hotnessFilter = '';

    public int $perPage = 8;

    public array $stagePage = [];

    // Move-to-stage modal
    public ?int $movingProspectId = null;

    public ?string $movingToStage = null;

    public string $moveAction = '';

    public string $moveNotes = '';

    public string $moveDate = '';

    public string $moveNextFollowUp = '';

    // Prospect detail / activity history popup
    public ?int $viewingProspectId = null;

    // Add-to-pipeline modal
    public bool $showFormModal = false;

    public string $user_id = '';

    public string $first_name = '';

    public string $last_name = '';

    public string $email = '';

    public string $phone = '';

    public string $source = '';

    public string $hotness = '';

    public string $next_follow_up = '';

    public string $notes = '';

    public array $sourceOptions = [
        'Facebook', 'Instagram', 'TikTok', 'YouTube', 'Twitter / X',
        'LinkedIn', 'Referral', 'Friend', 'Family', 'Website', 'Event', 'Other',
    ];

    public array $hotnessOptions = ['Hot', 'Warm', 'Cold'];

    // Remove confirm
    public ?int $removingId = null;

    public function updatingSearch(): void
    {
        $this->resetStagePages();
    }

    public function updatingOwnerFilter(): void
    {
        $this->resetStagePages();
    }

    public function updatingSourceFilter(): void
    {
        $this->resetStagePages();
    }

    public function updatingHotnessFilter(): void
    {
        $this->resetStagePages();
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'ownerFilter', 'sourceFilter', 'hotnessFilter']);
        $this->resetStagePages();
    }

    protected function resetStagePages(): void
    {
        $this->stagePage = [];
    }

    public function goToStagePage(string $stage, int $page): void
    {
        $this->stagePage[$stage] = max(1, $page);
    }

    public function requestMove(int $prospectId, string $stage): void
    {
        $stageOptions = Timeline::funnelStageOptions();

        if (! isset($stageOptions[$stage])) {
            return;
        }

        $prospect = Prospect::findOrFail($prospectId);

        $this->movingProspectId = $prospect->id;
        $this->movingToStage = $stage;
        $this->moveAction = $stageOptions[$stage]['actions'][0];
        $this->moveNotes = '';
        $this->moveDate = now()->format('Y-m-d\TH:i');
        $this->moveNextFollowUp = '';
        $this->resetErrorBag();
    }

    public function cancelMove(): void
    {
        $this->reset(['movingProspectId', 'movingToStage', 'moveAction', 'moveNotes', 'moveDate', 'moveNextFollowUp']);
        $this->resetErrorBag();
    }

    protected function moveRules(): array
    {
        return [
            'moveAction' => 'required|string|max:50',
            'moveDate' => 'required|date',
            'moveNotes' => 'nullable|string|max:2000',
            'moveNextFollowUp' => 'nullable|date',
        ];
    }

    public function confirmMove(): void
    {
        $this->validate($this->moveRules());

        $prospect = Prospect::findOrFail($this->movingProspectId);

        Timeline::create([
            'prospect_id' => $prospect->id,
            'action' => $this->moveAction,
            'notes' => $this->moveNotes !== '' ? $this->moveNotes : null,
            'action_at' => $this->moveDate,
            'next_follow_up_dt' => $this->moveNextFollowUp !== '' ? $this->moveNextFollowUp : null,
        ]);

        if ($this->moveNextFollowUp !== '') {
            $prospect->update(['next_follow_up' => $this->moveNextFollowUp]);
        }

        $this->cancelMove();
    }

    public function viewDetails(int $prospectId): void
    {
        $this->viewingProspectId = Prospect::findOrFail($prospectId)->id;
    }

    public function closeDetails(): void
    {
        $this->viewingProspectId = null;
    }

    public function openCreate(): void
    {
        $this->resetForm();
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
            'source', 'hotness', 'next_follow_up', 'notes',
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
            ->where(function ($q) {
                $q->when(trim($this->email) !== '', fn ($qq) => $qq->orWhere('email', $this->email));
                $q->when(trim($this->phone) !== '', fn ($qq) => $qq->orWhere('phone', $this->phone));
            })
            ->exists();

        if ($duplicate) {
            $this->addError('email', 'This owner already has a prospect with this email or phone number.');

            return;
        }

        $prospect = Prospect::create([
            'user_id' => $userId,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email ?: null,
            'phone' => $this->phone ?: null,
            'source' => $this->source ?: null,
            'hotness' => $this->hotness ?: null,
            'next_follow_up' => $this->next_follow_up ?: null,
            'notes' => $this->notes ?: null,
        ]);

        Timeline::create(['prospect_id' => $prospect->id, 'action' => 'Added', 'action_at' => now()]);

        session()->flash('success', 'Prospect added to the pipeline.');

        $this->showFormModal = false;
        $this->resetForm();
    }

    public function confirmRemove(int $id): void
    {
        $this->removingId = $id;
    }

    public function cancelRemove(): void
    {
        $this->removingId = null;
    }

    public function remove(): void
    {
        if (! $this->removingId) {
            return;
        }

        Prospect::where('id', $this->removingId)->delete();

        if ($this->viewingProspectId === $this->removingId) {
            $this->viewingProspectId = null;
        }

        session()->flash('success', 'Prospect removed from the pipeline.');
        $this->removingId = null;
    }

    protected function filteredQuery()
    {
        $query = Prospect::query()->with(['user', 'latestActivity']);

        if (trim($this->search) !== '') {
            $term = '%'.trim($this->search).'%';
            $query->where(function ($q) use ($term) {
                $q->where('first_name', 'like', $term)
                    ->orWhere('last_name', 'like', $term)
                    ->orWhere('email', 'like', $term)
                    ->orWhere('phone', 'like', $term);
            });
        }

        if ($this->ownerFilter !== '') {
            $query->where('user_id', $this->ownerFilter);
        }

        if ($this->sourceFilter !== '') {
            $query->where('source', $this->sourceFilter);
        }

        if ($this->hotnessFilter !== '') {
            $query->where('hotness', $this->hotnessFilter);
        }

        return $query;
    }

    /**
     * Every matching prospect bucketed into its current funnel stage, each
     * stage sliced to the current page of $perPage cards.
     */
    protected function board(): array
    {
        $stageOptions = Timeline::funnelStageOptions();

        $buckets = [];
        foreach ($stageOptions as $stage => $meta) {
            $buckets[$stage] = [];
        }

        foreach ($this->filteredQuery()->orderBy('first_name')->get() as $prospect) {
            $stage = Timeline::funnelStageForAction($prospect->latestActivity?->action);
            $buckets[$stage][] = $prospect;
        }

        $board = [];
        foreach ($buckets as $stage => $items) {
            $total = count($items);
            $lastPage = max(1, (int) ceil($total / $this->perPage));
            $page = min(max(1, $this->stagePage[$stage] ?? 1), $lastPage);

            $board[$stage] = [
                'items' => array_slice($items, ($page - 1) * $this->perPage, $this->perPage),
                'total' => $total,
                'page' => $page,
                'lastPage' => $lastPage,
            ];
        }

        return $board;
    }

    protected function stats(): array
    {
        $base = $this->filteredQuery();

        return [
            'total' => (clone $base)->count(),
            'hot' => (clone $base)->where('hotness', 'Hot')->count(),
            'due_this_week' => (clone $base)->whereBetween('next_follow_up', [now(), now()->addDays(7)])->count(),
            'added_this_week' => (clone $base)->where('created_at', '>=', now()->subDays(7))->count(),
        ];
    }

    /**
     * Cumulative "ever reached this stage" counts and stage-to-stage
     * conversion percentages for the funnel chart, over the filtered set.
     */
    protected function funnelStats(): array
    {
        $total = (clone $this->filteredQuery())->count();

        $stages = [];
        $previousCount = null;

        foreach (Timeline::funnelStageOptions() as $stage => $meta) {
            if ($stage === 'Closed') {
                continue;
            }

            $count = (clone $this->filteredQuery())
                ->whereHas('timelines', fn ($q) => $q->whereIn('action', $meta['actions']))
                ->count();

            $stages[$stage] = [
                'label' => $meta['label'],
                'color' => $meta['color'],
                'count' => $count,
                'pct_of_total' => $total > 0 ? round($count / $total * 100, 1) : 0,
                'pct_of_previous' => $previousCount !== null && $previousCount > 0
                    ? round($count / $previousCount * 100, 1)
                    : null,
            ];

            $previousCount = $count;
        }

        $closed = (clone $this->filteredQuery())
            ->whereHas('timelines', fn ($q) => $q->where('action', 'Closed'))
            ->count();

        return ['stages' => $stages, 'closed' => $closed, 'total' => $total];
    }

    protected function filterOptions(): array
    {
        return [
            'sources' => Prospect::whereNotNull('source')->where('source', '!=', '')->distinct()->orderBy('source')->pluck('source')->all(),
            'hotness' => Prospect::whereNotNull('hotness')->where('hotness', '!=', '')->distinct()->orderBy('hotness')->pluck('hotness')->all(),
            'owners' => User::whereIn('id', Prospect::select('user_id')->distinct())->orderBy('username')->get(['id', 'username']),
        ];
    }

    public function render()
    {
        return view('livewire.admin.prospects.pipeline-manager', [
            'board' => $this->board(),
            'stats' => $this->stats(),
            'funnelStats' => $this->funnelStats(),
            'stageOptions' => Timeline::funnelStageOptions(),
            'filterOptions' => $this->filterOptions(),
            'allOwners' => User::orderBy('username')->get(['id', 'username']),
            'movingProspect' => $this->movingProspectId
                ? Prospect::find($this->movingProspectId)
                : null,
            'viewingProspect' => $this->viewingProspectId
                ? Prospect::with('user')->find($this->viewingProspectId)
                : null,
            'viewingActivities' => $this->viewingProspectId
                ? Timeline::where('prospect_id', $this->viewingProspectId)->orderByDesc('action_at')->orderByDesc('id')->get()
                : collect(),
            'activityOptions' => Timeline::actionOptions(),
        ]);
    }
}
