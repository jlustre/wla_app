<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4 2xl:grid-cols-8 mx-2 lg:mx-4 my-4">
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <p class="text-sm font-medium text-slate-500">Total Prospects</p>
        <div class="mt-3 flex items-end justify-between">
            <h3 class="text-3xl font-bold text-slate-900">{{ $totalProspects ?? 0 }}</h3>
            <span class="rounded-full bg-sky-50 px-2.5 py-1 text-xs font-semibold text-sky-700">Scoped</span>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-red-100/70 p-5 shadow-sm">
        <p class="text-sm font-medium text-slate-500">Added</p>
        <div class="mt-3 flex items-end justify-between">
            <h3 class="text-3xl font-bold text-slate-900">{{ isset($addedProspects) ? count($addedProspects) : 0 }}</h3>
                <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                    {{ \App\Helpers\PipelineHelper::getStagePercentage(auth()->user()->id, 'Added') }}%
                </span>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-yellow-100/70 p-5 shadow-sm">
        <p class="text-sm font-medium text-slate-500">Contacted</p>
        <div class="mt-3 flex items-end justify-between">
            <h3 class="text-3xl font-bold text-slate-900">{{ isset($contactedProspects) ? count($contactedProspects) : 0 }}</h3>
            <span class="rounded-full bg-violet-50 px-2.5 py-1 text-xs font-semibold text-violet-700">
                {{ \App\Helpers\PipelineHelper::getStagePercentage(auth()->user()->id, 'Contacted') }}%
            </span>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-green-50/70 p-5 shadow-sm">
        <p class="text-sm font-medium text-slate-500">Invited</p>
        <div class="mt-3 flex items-end justify-between">
            <h3 class="text-3xl font-bold text-slate-900">{{ isset($invitedProspects) ? count($invitedProspects) : 0 }}</h3>
            <span class="rounded-full bg-cyan-50 px-2.5 py-1 text-xs font-semibold text-cyan-700">
                {{ \App\Helpers\PipelineHelper::getStagePercentage(auth()->user()->id, 'Invited') }}%
            </span>
        </div>
    </div>

    <div class="rounded-2xl border border-amber-200 bg-purple-100/70 p-5 shadow-sm">
        <p class="text-sm font-medium text-amber-700">Presented</p>
        <div class="mt-3 flex items-end justify-between">
            <h3 class="text-3xl font-bold text-amber-900">{{ isset($presentedProspects) ? count($presentedProspects) : 0 }}</h3>
            <span class="rounded-full bg-white px-2.5 py-1 text-xs font-semibold text-amber-700">
                {{ \App\Helpers\PipelineHelper::getStagePercentage(auth()->user()->id, 'Presented') }}%
            </span>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-indigo-100/70 p-5 shadow-sm">
        <p class="text-sm font-medium text-slate-500">Followed-up</p>
        <div class="mt-3 flex items-end justify-between">
            <h3 class="text-3xl font-bold text-slate-900">{{ isset($followedUpProspects) ? count($followedUpProspects) : 0 }}</h3>
            <span class="rounded-full bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700">
                {{ \App\Helpers\PipelineHelper::getStagePercentage(auth()->user()->id, 'Followed Up') }}%
            </span>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-indigo-200/70 p-5 shadow-sm">
        <p class="text-sm font-medium text-slate-500">Joined</p>
        <div class="mt-3 flex items-end justify-between">
            <h3 class="text-3xl font-bold text-slate-900">{{ isset($joinedProspects) ? count($joinedProspects) : 0 }}</h3>
            <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                {{ \App\Helpers\PipelineHelper::getStagePercentage(auth()->user()->id, 'Joined') }}%
            </span>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-indigo-300/70 p-5 shadow-sm">
        <p class="text-sm font-medium text-slate-500">Closed</p>
        <div class="mt-3 flex items-end justify-between">
            <h3 class="text-3xl font-bold text-slate-900">{{ isset($closedProspects) ? count($closedProspects) : 0 }}</h3>
            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                {{ \App\Helpers\PipelineHelper::getStagePercentage(auth()->user()->id, 'Closed') }}%
            </span>
        </div>
    </div>
</div>