<div class="w-full">
    <div class="w-full rounded-[32px] border border-white/60 bg-[linear-gradient(180deg,rgba(255,255,255,0.9),rgba(248,250,252,0.86))] shadow-[0_30px_100px_-55px_rgba(15,23,42,0.65)] ring-1 ring-slate-200/70 dark:border-slate-800 dark:bg-[linear-gradient(180deg,rgba(15,23,42,0.96),rgba(15,23,42,0.88))] dark:ring-slate-800">
        <div class="flex flex-col gap-3 border-b border-slate-200/80 p-5 sm:flex-row sm:items-center sm:justify-between dark:border-slate-800">
            <div>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white">
                    Sponsorship Explorer
                </h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    Focus a branch, search for members, and move through the network without flooding the page.
                </p>
            </div>
        </div>

        <div class="min-h-[600px] overflow-hidden bg-gradient-to-br from-slate-50 via-white to-amber-50/40 p-6 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950">
            <livewire:dashboard.sponsorship-tree />
        </div>
    </div>
</div>