<div id="prospects-timeline" class="w-full max-w-7xl mx-auto mt-10">
    <h3 class="text-xl font-bold text-teal-700 mb-4">All Prospect Activities</h3>
    <form method="GET" class="flex flex-wrap gap-2 mb-4 items-end" onsubmit="window.location.hash='prospects-timeline'">
        <input type="text" name="timeline_search" value="{{ request('timeline_search') }}" placeholder="Search prospect, notes..." class="rounded border border-teal-700 bg-teal-50 px-3 py-2 text-sm focus:border-teal-900 focus:bg-teal-100" />
        <select name="timeline_stage" class="rounded border border-teal-700 bg-teal-50 px-3 py-2 text-sm focus:border-teal-900 focus:bg-teal-100">
            <option value="">All Stages</option>
            @foreach(['Added','Contacted','Invited','Presented','Followed Up','Joined','Closed'] as $stage)
                <option value="{{ $stage }}" @if(request('timeline_stage')===$stage) selected @endif>{{ $stage }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded bg-teal-600 px-4 py-2 text-white text-sm font-semibold hover:bg-teal-700" onclick="window.location.hash='prospects-timeline'">Filter</button>
        @if(request('timeline_search') || request('timeline_stage'))
            <a href="{{ route('lead-pipeline') }}#prospects-timeline" class="rounded bg-slate-300 px-4 py-2 text-slate-700 text-sm font-semibold hover:bg-slate-400">Clear</a>
        @endif
    </form>
    <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-100">
                <tr>
                    <th class="px-3 py-2 border">Prospect</th>
                    <th class="px-3 py-2 border">Stage</th>
                    <th class="px-3 py-2 border">Notes</th>
                    <th class="px-3 py-2 border">Next Follow-Up Date</th>
                        <th class="px-3 py-2 border">Action</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $userProspectIds = $prospects->pluck('id') ?? collect();
                    $timelineQuery = \App\Models\Timeline::whereIn('prospect_id', $userProspectIds)
                        ->whereHas('prospect', function($q) { $q->where('user_id', auth()->id()); });
                    if (request('timeline_search')) {
                        $search = request('timeline_search');
                        $timelineQuery->where(function($q) use ($search) {
                            $q->where('notes', 'like', "%$search%")
                                ->orWhereHas('prospect', function($q2) use ($search) {
                                    $q2->where('first_name', 'like', "%$search%")
                                        ->orWhere('last_name', 'like', "%$search%")
                                        ->orWhere('email', 'like', "%$search%")
                                        ->orWhere('phone', 'like', "%$search%")
                                        ->orWhere('source', 'like', "%$search%")
                                        ;
                                });
                        });
                    }
                    if (request('timeline_stage')) {
                        $timelineQuery->where('action', request('timeline_stage'));
                    }
                    $allTimelines = $timelineQuery->with('prospect')->orderByDesc('action_at')->paginate(10, ['*'], 'timeline_page');
                @endphp
                @forelse($allTimelines as $timeline)
                    @php
                        $prospectData = [
                            'id' => $timeline->prospect?->id,
                            'name' => $timeline->prospect?->first_name . ' ' . $timeline->prospect?->last_name,
                            'email' => $timeline->prospect?->email,
                            'phone' => $timeline->prospect?->phone,
                            'source' => $timeline->prospect?->source,
                            'hotness' => $timeline->prospect?->hotness,
                            'notes' => $timeline->prospect?->notes,
                            'timelines' => $timeline->prospect?->timelines()->orderByDesc('action_at')->get(["id","action","notes","action_at","next_follow_up_dt"])
                                ->map(function($t) {
                                    return [
                                        'id' => $t->id,
                                        'action' => $t->action,
                                        'notes' => $t->notes,
                                        'action_at' => $t->action_at ? $t->action_at->format('Y-m-d H:i') : null,
                                        'next_follow_up_dt' => $t->next_follow_up_dt ? $t->next_follow_up_dt->format('Y-m-d H:i') : null,
                                    ];
                                }),
                        ];
                        $prospectJson = json_encode($prospectData);
                    @endphp
                    <tr>
                        <td class="px-3 py-2 border font-semibold text-teal-800">
                            @if($timeline->prospect)
                                <a href="#" @click.prevent='selected = {!! $prospectJson !!}; showModal = true;' class="hover:underline cursor-pointer">
                                    {{ $timeline->prospect->first_name }} {{ $timeline->prospect->last_name }}
                                </a>
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-3 py-2 border text-sm">{{ $timeline->action }}</td>
                        <td class="px-3 py-2 border text-sm">{{ $timeline->notes }}</td>
                        <td class="px-3 py-2 border text-xs">{{ $timeline->next_follow_up_dt ? $timeline->next_follow_up_dt->format('Y-m-d H:i') : '' }}</td>
                    </tr>
                @empty
                    <tr>
                                <td class="px-3 py-2 border">
                                    @if($timeline->prospect)
                                        <a href="{{ route('prospects.index', ['search' => $timeline->prospect->id]) }}" class="mr-2 group" title="View Prospect">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="inline h-5 w-5 text-blue-600 group-hover:text-blue-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <span class="absolute z-20 hidden group-hover:block bg-slate-800 text-white text-xs rounded px-2 py-1 ml-6 mt-[-2.5rem]">View</span>
                                        </a>
                                        <a href="{{ route('prospects.edit', $timeline->prospect->id) }}" class="mr-2 group" title="Edit Prospect">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="inline h-5 w-5 text-yellow-600 group-hover:text-yellow-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h2m-1 0v2m0 4v2m0 4v2m-7-2a9 9 0 1118 0 9 9 0 01-18 0z" />
                                            </svg>
                                            <span class="absolute z-20 hidden group-hover:block bg-slate-800 text-white text-xs rounded px-2 py-1 ml-6 mt-[-2.5rem]">Edit</span>
                                        </a>
                                        <form action="{{ route('prospects.destroy', $timeline->prospect->id) }}" method="POST" class="inline group">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-600 hover:text-rose-800" title="Delete Prospect" onclick="return confirm('Are you sure you want to delete this prospect?')">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="inline h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                <span class="absolute z-20 hidden group-hover:block bg-slate-800 text-white text-xs rounded px-2 py-1 ml-6 mt-[-2.5rem]">Delete</span>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-slate-400">N/A</span>
                                    @endif
                                </td>
                            </tr>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">
            {{ $allTimelines->appends(request()->except('timeline_page'))->links() }}
        </div>
    </div>
</div>