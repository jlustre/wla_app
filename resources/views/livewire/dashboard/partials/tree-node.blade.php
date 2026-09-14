<div class="space-y-4">
    <div class="rounded-3xl border border-slate-200 bg-white/90 p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900/80">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-lg font-bold text-slate-950 dark:text-white">{{ $node['name'] }}</p>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ $node['email'] }}</p>
            </div>
            <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700 dark:bg-blue-500/10 dark:text-blue-300">Level {{ $level }}</span>
        </div>
    </div>

    @if (! empty($node['children']))
        <div class="grid gap-4 border-l-2 border-dashed border-slate-300 pl-4 sm:pl-6 dark:border-slate-700 lg:grid-cols-2">
            @foreach ($node['children'] as $child)
                @include('livewire.dashboard.partials.tree-node', ['node' => $child, 'level' => $level + 1])
            @endforeach
        </div>
    @endif
</div>