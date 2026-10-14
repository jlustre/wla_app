<?php

namespace App\Livewire\Member;

use App\Models\Prospect;
use App\Models\Timeline;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/**
 * The "Prospect Funnel" page: a kanban board of every prospect bucketed by
 * the funnel stage of their most recent logged activity (see
 * Timeline::funnelStageOptions()), plus a conversion funnel chart showing
 * how many prospects have ever reached each stage.
 *
 * Moving a card to a new stage doesn't just relabel the prospect — it logs
 * a new Timeline entry, the same source of truth the Activity Log on the
 * My Prospects page (App\Livewire\Member\ProspectsManager) reads from. So
 * the board and the activity history can never drift apart.
 */
class ProspectFunnel extends Component
{
    public string $search = '';

    // Move-to-stage modal
    public ?int $movingProspectId = null;

    public ?string $movingToStage = null;

    public string $moveAction = '';

    public string $moveNotes = '';

    public string $moveDate = '';

    public string $moveNextFollowUp = '';

    // Prospect detail / activity history popup
    public ?int $viewingProspectId = null;

    public function viewDetails(int $prospectId): void
    {
        $prospect = Prospect::where('user_id', Auth::id())->findOrFail($prospectId);

        $this->viewingProspectId = $prospect->id;
    }

    public function closeDetails(): void
    {
        $this->viewingProspectId = null;
    }

    public function requestMove(int $prospectId, string $stage): void
    {
        $stageOptions = Timeline::funnelStageOptions();

        if (! isset($stageOptions[$stage])) {
            return;
        }

        $prospect = Prospect::where('user_id', Auth::id())->findOrFail($prospectId);

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

        $prospect = Prospect::where('user_id', Auth::id())->findOrFail($this->movingProspectId);

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

    /**
     * Every prospect bucketed into its current funnel stage, keyed by
     * stage, in display order. One query for the prospects (with their
     * latest activity eager-loaded via latestOfMany — no N+1) plus the
     * filter, done in PHP since the stage itself isn't a DB column.
     */
    protected function board(int $userId): array
    {
        $stageOptions = Timeline::funnelStageOptions();

        $board = [];
        foreach ($stageOptions as $stage => $meta) {
            $board[$stage] = [];
        }

        $prospects = Prospect::where('user_id', $userId)
            ->with('latestActivity')
            ->when(trim($this->search) !== '', function ($q) {
                $term = '%'.trim($this->search).'%';
                $q->where(function ($qq) use ($term) {
                    $qq->where('first_name', 'like', $term)
                        ->orWhere('last_name', 'like', $term)
                        ->orWhere('email', 'like', $term)
                        ->orWhere('phone', 'like', $term);
                });
            })
            ->orderBy('first_name')
            ->get();

        foreach ($prospects as $prospect) {
            $stage = Timeline::funnelStageForAction($prospect->latestActivity?->action);
            $board[$stage][] = $prospect;
        }

        return $board;
    }

    /**
     * Cumulative "ever reached this stage" counts and stage-to-stage
     * conversion percentages for the funnel chart. "Closed" is reported
     * separately since it's an exit, not a forward step.
     */
    protected function funnelStats(int $userId): array
    {
        $total = Prospect::where('user_id', $userId)->count();

        $stages = [];
        $previousCount = null;

        foreach (Timeline::funnelStageOptions() as $stage => $meta) {
            if ($stage === 'Closed') {
                continue;
            }

            $count = Prospect::where('user_id', $userId)
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

        $closed = Prospect::where('user_id', $userId)
            ->whereHas('timelines', fn ($q) => $q->where('action', 'Closed'))
            ->count();

        return ['stages' => $stages, 'closed' => $closed, 'total' => $total];
    }

    public function render()
    {
        $userId = Auth::id();

        return view('livewire.member.prospect-funnel', [
            'board' => $this->board($userId),
            'funnelStats' => $this->funnelStats($userId),
            'stageOptions' => Timeline::funnelStageOptions(),
            'movingProspect' => $this->movingProspectId
                ? Prospect::where('user_id', $userId)->find($this->movingProspectId)
                : null,
            'viewingProspect' => $this->viewingProspectId
                ? Prospect::where('user_id', $userId)->find($this->viewingProspectId)
                : null,
            'viewingActivities' => $this->viewingProspectId
                ? Timeline::where('prospect_id', $this->viewingProspectId)->orderByDesc('action_at')->orderByDesc('id')->get()
                : collect(),
            'activityOptions' => Timeline::actionOptions(),
        ]);
    }
}
