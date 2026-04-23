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
                        <td class="px-3 py-2 border text-center text-slate-500" colspan="5">
                            No timelines found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">
            {{ $allTimelines->appends(request()->except('timeline_page'))->links() }}
        </div>
    </div>
</div>