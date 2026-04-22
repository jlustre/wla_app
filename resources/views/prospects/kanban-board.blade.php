<div class="relative">
    <!-- Kanban Scroll Arrows -->
    <button type="button" @click="document.getElementById('kanban-scroll').scrollBy({left: -400, behavior: 'smooth'})" class="absolute left-0 top-1/8 z-10 -translate-y-1/2 bg-white border border-teal-600 rounded-full shadow p-2 hover:bg-teal-50 focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
    </button>
    <button type="button" @click="document.getElementById('kanban-scroll').scrollBy({left: 400, behavior: 'smooth'})" class="absolute right-0 top-1/8 z-10 -translate-y-1/2 bg-white border border-teal-600 rounded-full shadow p-2 hover:bg-teal-50 focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
    </button>
    <div
        id="kanban-scroll"
        class="overflow-x-auto scrollbar-thin scrollbar-thumb-teal-200 scrollbar-track-slate-100"
        x-data="{ isDown: false, startX: 0, scrollLeft: 0 }"
        @mousedown="isDown = true; startX = $event.pageX - $el.offsetLeft; scrollLeft = $el.scrollLeft"
        @mouseleave="isDown = false"
        @mouseup="isDown = false"
        @mousemove="if(isDown){ $el.scrollLeft = scrollLeft - ($event.pageX - $el.offsetLeft - startX) }"
        style="cursor: grab;"
    >
    <div class="grid min-w-[900px] grid-cols-7 gap-1" style="min-height: 300px">
    {{-- Added --}}
    <div class="border-r border-slate-200 bg-red-100/70 p-4">
        <div class="mb-4 flex items-center justify-between">
            <div>
                <h4 class="text-md font-bold text-teal-800">ADDED</h4>
                <p class="text-xs text-slate-500">{{ count($addedProspects) }} prospects</p>
            </div>
        </div>

        <div class="space-y-3">
            @forelse($addedProspects as $prospect)
                @php
                    $timelinesJson = $prospect->timelines()->orderByDesc('action_at')->get(['id','action','notes','action_at'])->toJson();
                    $prospectName = addslashes($prospect->first_name . ' ' . $prospect->last_name);
                @endphp
                <button type="button"
                    class="w-full text-left rounded-lg border border-slate-200 bg-white px-2 py-1 shadow-sm hover:bg-teal-100 hover:cursor-pointer focus:outline-none"
                    @click='
                        selected = {
                            "id": {{ $prospect->id }},
                            "name": "{{ $prospectName }}",
                            "timelines": {!! $timelinesJson !!}
                        };
                        showModal = true;
                    '
                >
                    <div class="flex items-center gap-3">
                        <div>
                            <div class="text-sm font-semibold text-slate-900">{{ $prospect->first_name }} {{ $prospect->last_name }}</div>
                            <div class="text-xs text-slate-400">{{ $prospect->phone }}</div>
                        </div>
                    </div>
                </button>
            @empty
                <div class="text-xs text-slate-400">No prospects in this stage.</div>
            @endforelse
        </div>
    </div>

    {{-- Contacted --}}
    <div class="border-r border-slate-200 bg-yellow-100/70 p-4">
        <div class="mb-4 flex items-center justify-between">
            <div>
                <h4 class="text-md font-bold text-teal-800">CONTACTED</h4>
                <p class="text-xs text-slate-500">{{ count($contactedProspects) }} prospects</p>
            </div>
        </div>

        <div class="space-y-3">
            @forelse($contactedProspects as $prospect)
                @php
                    $timelinesJson = $prospect->timelines()->orderByDesc('action_at')->get(['id','action','notes','action_at'])->toJson();
                    $prospectName = addslashes($prospect->first_name . ' ' . $prospect->last_name);
                @endphp
                <button type="button"
                    class="w-full text-left rounded-lg border border-slate-200 bg-white px-2 py-1 shadow-sm hover:bg-teal-100 hover:cursor-pointer focus:outline-none"
                    @click='
                        selected = {
                            "id": {{ $prospect->id }},
                            "name": "{{ $prospectName }}",
                            "timelines": {!! $timelinesJson !!}
                        };
                        showModal = true;
                    '
                >
                    <div class="flex items-center gap-3">
                        <div>
                            <div class="text-sm font-semibold text-slate-900">{{ $prospect->first_name }} {{ $prospect->last_name }}</div>
                            <div class="text-xs text-slate-400">{{ $prospect->phone }}</div>
                        </div>
                    </div>
                </button>
            @empty
                <div class="text-xs text-slate-400">No prospects in this stage.</div>
            @endforelse
        </div>
    </div>

    {{-- Invited --}}
    <div class="border-r border-slate-200 bg-green-50/70 p-4">
        <div class="mb-4 flex items-center justify-between">
            <div>
                <h4 class="text-md font-bold text-teal-800">INVITED</h4>
                <p class="text-xs text-slate-500">{{ count($invitedProspects) }} prospects</p>
            </div>
        </div>

        <div class="space-y-3">
            @forelse($invitedProspects as $prospect)
                @php
                    $timelinesJson = $prospect->timelines()->orderByDesc('action_at')->get(['id','action','notes','action_at'])->toJson();
                    $prospectName = addslashes($prospect->first_name . ' ' . $prospect->last_name);
                @endphp
                <button type="button"
                    class="w-full text-left rounded-lg border border-slate-200 bg-white px-2 py-1 shadow-sm hover:bg-teal-100 hover:cursor-pointer focus:outline-none"
                    @click='
                        selected = {
                            "id": {{ $prospect->id }},
                            "name": "{{ $prospectName }}",
                            "timelines": {!! $timelinesJson !!}
                        };
                        showModal = true;
                    '
                >
                    <div class="flex items-center gap-3">
                        <div>
                            <div class="text-sm font-semibold text-slate-900">{{ $prospect->first_name }} {{ $prospect->last_name }}</div>
                            <div class="text-xs text-slate-400">{{ $prospect->phone }}</div>
                        </div>
                    </div>
                </button>
            @empty
                <div class="text-xs text-slate-400">No prospects in this stage.</div>
            @endforelse
        </div>
    </div>

    {{-- Presented --}}
    <div class="border-r border-slate-200 bg-purple-100/70 p-4">
        <div class="mb-4 flex items-center justify-between">
            <div>
                <h4 class="text-md font-bold text-teal-800">PRESENTED</h4>
                <p class="text-xs text-slate-500">{{ count($presentedProspects) }} prospects</p>
            </div>
        </div>

        <div class="space-y-3">
            @forelse($presentedProspects as $prospect)
                @php
                    $timelinesJson = $prospect->timelines()->orderByDesc('action_at')->get(['id','action','notes','action_at'])->toJson();
                    $prospectName = addslashes($prospect->first_name . ' ' . $prospect->last_name);
                @endphp
                <button type="button"
                    class="w-full text-left rounded-lg border border-slate-200 bg-white px-2 py-1 shadow-sm hover:bg-teal-100 hover:cursor-pointer focus:outline-none"
                    @click='
                        selected = {
                            "id": {{ $prospect->id }},
                            "name": "{{ $prospectName }}",
                            "timelines": {!! $timelinesJson !!}
                        };
                        showModal = true;
                    '
                >
                    <div class="flex items-center gap-3">
                        <div>
                            <div class="text-sm font-semibold text-slate-900">{{ $prospect->first_name }} {{ $prospect->last_name }}</div>
                            <div class="text-xs text-slate-400">{{ $prospect->phone }}</div>
                        </div>
                    </div>
                </button>
            @empty
                <div class="text-xs text-slate-400">No prospects in this stage.</div>
            @endforelse
        </div>
    </div>

    {{-- Follow Up --}}
    <div class="border-r border-slate-200 bg-indigo-100/70 p-4">
        <div class="mb-4 flex items-center justify-between">
            <div>
                <h4 class="text-md font-bold text-teal-800">FOLLOW UP</h4>
                <p class="text-xs text-slate-500">{{ count($followedUpProspects) }} prospects</p>
            </div>
        </div>

        <div class="space-y-3">
            @forelse($followedUpProspects as $prospect)
                @php
                    $timelinesJson = $prospect->timelines()->orderByDesc('action_at')->get(['id','action','notes','action_at'])->toJson();
                    $prospectName = addslashes($prospect->first_name . ' ' . $prospect->last_name);
                @endphp
                <button type="button"
                    class="w-full text-left rounded-lg border border-slate-200 bg-white px-2 py-1 shadow-sm hover:bg-teal-100 hover:cursor-pointer focus:outline-none"
                    @click='
                        selected = {
                            "id": {{ $prospect->id }},
                            "name": "{{ $prospectName }}",
                            "timelines": {!! $timelinesJson !!}
                        };
                        showModal = true;
                    '
                >
                    <div class="flex items-center gap-3">
                        <div>
                            <div class="text-sm font-semibold text-slate-900">{{ $prospect->first_name }} {{ $prospect->last_name }}</div>
                            <div class="text-xs text-slate-400">{{ $prospect->phone }}</div>
                        </div>
                    </div>
                </button>
            @empty
                <div class="text-xs text-slate-400">No prospects in this stage.</div>
            @endforelse
        </div>
    </div>
    
    {{-- Joined --}}
    <div class="border-r border-slate-200 bg-indigo-200/70 p-4">
        <div class="mb-4 flex items-center justify-between">
            <div>
                <h4 class="text-md font-bold text-teal-800">JOINED</h4>
                <p class="text-xs text-slate-500">{{ count($joinedProspects) }} prospects</p>
            </div>
        </div>

        <div class="space-y-3">
            @forelse($joinedProspects as $prospect)
                @php
                    $timelinesJson = $prospect->timelines()->orderByDesc('action_at')->get(['id','action','notes','action_at'])->toJson();
                    $prospectName = addslashes($prospect->first_name . ' ' . $prospect->last_name);
                @endphp
                <button type="button"
                    class="w-full text-left rounded-lg border border-slate-200 bg-white px-2 py-1 shadow-sm hover:bg-teal-100 hover:cursor-pointer focus:outline-none"
                    @click='
                        selected = {
                            "id": {{ $prospect->id }},
                            "name": "{{ $prospectName }}",
                            "timelines": {!! $timelinesJson !!}
                        };
                        showModal = true;
                    '
                >
                    <div class="flex items-center gap-3">
                        <div>
                            <div class="text-sm font-semibold text-slate-900">{{ $prospect->first_name }} {{ $prospect->last_name }}</div>
                            <div class="text-xs text-slate-400">{{ $prospect->phone }}</div>
                        </div>
                    </div>
                </button>
            @empty
                <div class="text-xs text-slate-400">No prospects in this stage.</div>
            @endforelse
        </div>
    </div>

    {{-- Closed --}}
    <div class="border-r border-slate-200 bg-indigo-300/70 p-4">
        <div class="mb-4 flex items-center justify-between">
            <div>
                <h4 class="text-md font-bold text-teal-800">CLOSED</h4>
                <p class="text-xs text-slate-500">{{ count($closedProspects) }} prospects</p>
            </div>
        </div>

        <div class="space-y-3">
            @forelse($closedProspects as $prospect)
                @php
                    $timelinesJson = $prospect->timelines()->orderByDesc('action_at')->get(['id','action','notes','action_at'])->toJson();
                    $prospectName = addslashes($prospect->first_name . ' ' . $prospect->last_name);
                @endphp
                <button type="button"
                    class="w-full text-left rounded-lg border border-slate-200 bg-white px-2 py-1 shadow-sm hover:bg-teal-100 hover:cursor-pointer focus:outline-none"
                    @click='
                        selected = {
                            "id": {{ $prospect->id }},
                            "name": "{{ $prospectName }}",
                            "timelines": {!! $timelinesJson !!}
                        };
                        showModal = true;
                    '
                >
                    <div class="flex items-center gap-3">
                        <div>
                            <div class="text-sm font-semibold text-slate-900">{{ $prospect->first_name }} {{ $prospect->last_name }}</div>
                            <div class="text-xs text-slate-400">{{ $prospect->phone }}</div>
                        </div>
                    </div>
                </button>
            @empty
                <div class="text-xs text-slate-400">No prospects in this stage.</div>
            @endforelse
        </div>
    </div>
</div>
