<div class="group rounded-3xl border border-slate-200 bg-white p-5 transition hover:-translate-y-1 hover:shadow-lg">
    <div class="flex items-start justify-between">
        <div class="flex items-center gap-3">
            <div
                class="flex h-12 w-12 items-center justify-center rounded-2xl {{ $this->logoClasses() }} text-white shadow-md">
                <span class="text-sm font-bold">{{ $company['logo_letter'] }}</span>
            </div>
            <div>
                <h4 class="font-semibold text-slate-900">{{ $company['name'] }}</h4>
                <p class="text-xs text-slate-500">{{ $company['tagline'] }}</p>
            </div>
        </div>

        <span
            class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-semibold {{ $this->badgeClasses() }}">
            <span
                class="h-2 w-2 rounded-full {{ $company['status'] === 'Joined' ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
            {{ $company['status'] }}
        </span>
    </div>

    <p class="mt-4 text-sm leading-6 text-slate-600">
        {{ $company['description'] }}
    </p>

    <div class="mt-5 flex items-center justify-between gap-3">
        <span class="text-xs font-medium {{ $company['footer_note'] ? 'text-rose-500' : 'text-slate-500' }}">
            {{ $company['footer_note'] ?: $company['joined_at'] }}
        </span>

        <button wire:click="$dispatch('company-action', { company: '{{ addslashes($company['name']) }}' })"
            class="rounded-xl px-3 py-2 text-sm font-semibold transition {{ $this->buttonClasses() }}" type="button">
            {{ $company['action_label'] }}
        </button>
    </div>
</div>