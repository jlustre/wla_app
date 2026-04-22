<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prospect;
use Illuminate\Support\Facades\Auth;

class LeadPipelineController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Prospect::query();
        if ($user->hasRole(['admin', 'super-admin'])) {
            $userId = $request->query('user_id');
            if ($userId) {
                $query->where('user_id', $userId);
            }
        } else {
            $query->where('user_id', $user->id);
        }
        $prospects = $query->orderBy('first_name')->get();
        $selectedProspect = null;
        $timelines = collect();
        if ($request->filled('prospect_id')) {
            $selectedProspect = Prospect::where('id', $request->prospect_id)
                ->where('user_id', $user->id)
                ->first();
            if ($selectedProspect) {
                $timelines = $selectedProspect->timelines()->orderByDesc('action_at')->get();
            }
        }

        // Use PipelineHelper for each stage
        $addedProspects = \App\Helpers\PipelineHelper::getStageProspects($user->id, 'Added');
        $contactedProspects = \App\Helpers\PipelineHelper::getStageProspects($user->id, 'Contacted');
        $invitedProspects = \App\Helpers\PipelineHelper::getStageProspects($user->id, 'Invited');
        $presentedProspects = \App\Helpers\PipelineHelper::getStageProspects($user->id, 'Presented');
        $followedUpProspects = \App\Helpers\PipelineHelper::getStageProspects($user->id, 'Followed Up');
        $joinedProspects = \App\Helpers\PipelineHelper::getStageProspects($user->id, 'Joined');
        $closedProspects = \App\Helpers\PipelineHelper::getStageProspects($user->id, 'Closed');

        return view('prospects.lead-pipeline', [
            'prospects' => $prospects,
            'selectedProspect' => $selectedProspect,
            'timelines' => $timelines,
            'addedProspects' => $addedProspects,
            'contactedProspects' => $contactedProspects,
            'invitedProspects' => $invitedProspects,
            'presentedProspects' => $presentedProspects,
            'followedUpProspects' => $followedUpProspects,
            'joinedProspects' => $joinedProspects,
            'closedProspects' => $closedProspects,
        ]);
    }
    public function storeActivity(Request $request)
    {
        $request->validate([
            'prospect_id' => 'required|exists:prospects,id',
            'action' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $prospect = Prospect::findOrFail($request->prospect_id);
        // Only allow if user owns the prospect or is admin
        $user = Auth::user();
        if (!$user->hasRole(['admin', 'super-admin']) && $prospect->user_id !== $user->id) {
            abort(403);
        }

        $prospect->timelines()->create([
            'action' => $request->action,
            'notes' => $request->notes,
            'action_at' => now(),
            'next_follow_up_dt' => $request->next_follow_up_dt,
        ]);

        // No direct stage column update; stage is timeline-driven

        return redirect()->route('lead-pipeline', ['prospect_id' => $prospect->id])
            ->with('success', 'Activity logged successfully.');
    }
}
