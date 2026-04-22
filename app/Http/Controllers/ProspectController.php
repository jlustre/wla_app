<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Prospect;
use App\Models\Timeline;
use Illuminate\Support\Facades\Auth;
use App\Helpers\PipelineHelper;

class ProspectController extends Controller
{
    public function create()
    {
        return view('prospects.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'source' => 'nullable|string|max:255',
            'hotness' => 'nullable|integer|min:0|max:100',
            'next_follow_up' => 'nullable|date',
            'last_action' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // Require at least phone or email
        if (empty($data['phone']) && empty($data['email'])) {
            return redirect()->back()->withInput()->withErrors(['email' => 'Please provide at least a phone number or an email address.']);
        }

        // Require at least first name or last name
        if (empty($data['first_name']) && empty($data['last_name'])) {
            return redirect()->back()->withInput()->withErrors(['first_name' => 'Please provide at least a first name or a last name.']);
        }

        // Uniqueness check for phone or email
        $exists = Prospect::where(function($q) use ($data) {
            $q->where('email', $data['email']);
            if (!empty($data['phone'])) {
                $q->orWhere('phone', $data['phone']);
            }
        })->exists();
        if ($exists) {
            return redirect()->back()->withInput()->withErrors(['email' => 'A prospect with this email or phone number already exists. Please check your entry or search for the existing prospect.']);
        }
        $data['user_id'] = $user->id;
        $prospect = Prospect::create($data);
        Timeline::create([
            'user_id' => $user->id,
            'prospect_id' => $prospect->id,
            'action' => 'Added',
            'notes' => null,
            'action_at' => $prospect->created_at,
        ]);
        return redirect()->route('prospects.index')->with('success', 'Prospect added successfully!');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Prospect::query();

        // If admin or super-admin, allow filtering by user_id (from query param or default to first user)
        if ($user->hasRole(['admin', 'super-admin'])) {
            $userId = $request->query('user_id');
            if ($userId) {
                $query->where('user_id', $userId);
            }
        } else {
            // Members see only their own prospects
            $query->where('user_id', $user->id);
        }



        // Search and filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                  ->orWhere('last_name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%")
                  ->orWhere('source', 'like', "%$search%")
                  ;
            });
        }
        if ($request->filled('source')) {
            $query->where('source', $request->input('source'));
        }
        if ($request->filled('hotness')) {
            $query->where('hotness', $request->input('hotness'));
        }
        // Stage filter (timeline-driven)
        if ($request->filled('stage')) {
            $stage = $request->input('stage');
            $query->whereHas('timelines', function($q) use ($stage) {
                $q->whereRaw('action = (
                    SELECT t2.action FROM timelines t2 WHERE t2.prospect_id = timelines.prospect_id ORDER BY t2.action_at DESC LIMIT 1
                )')->where('action', $stage);
            });
        }
        // Next follow up date range
        if ($request->filled('next_follow_up_start')) {
            $query->whereDate('next_follow_up', '>=', $request->input('next_follow_up_start'));
        }
        if ($request->filled('next_follow_up_end')) {
            $query->whereDate('next_follow_up', '<=', $request->input('next_follow_up_end'));
        }

        $prospects = $query->orderByDesc('created_at')->paginate(10)->withQueryString();


        // Use PipelineHelper for each stage (matches Kanban logic)
        $userId = $user->hasRole(['admin', 'super-admin']) && $request->query('user_id')
            ? $request->query('user_id')
            : $user->id;

        $addedProspects = PipelineHelper::getStageProspects($userId, 'Added');
        $contactedProspects = PipelineHelper::getStageProspects($userId, 'Contacted');
        $invitedProspects = PipelineHelper::getStageProspects($userId, 'Invited');
        $presentedProspects = PipelineHelper::getStageProspects($userId, 'Presented');
        $followedUpProspects = PipelineHelper::getStageProspects($userId, 'Followed Up');
        $joinedProspects = PipelineHelper::getStageProspects($userId, 'Joined');
        $closedProspects = PipelineHelper::getStageProspects($userId, 'Closed');
        $totalProspects = PipelineHelper::getTotalProspects($userId);

        return view('prospects.index', [
            'prospects' => $prospects,
            'addedProspects' => $addedProspects,
            'contactedProspects' => $contactedProspects,
            'invitedProspects' => $invitedProspects,
            'presentedProspects' => $presentedProspects,
            'followedUpProspects' => $followedUpProspects,
            'joinedProspects' => $joinedProspects,
            'closedProspects' => $closedProspects,
            'totalProspects' => $totalProspects,
        ]);
    }
}
