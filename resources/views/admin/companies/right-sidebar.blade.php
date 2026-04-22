<aside class="space-y-6 xl:col-span-3">
    <!-- Publishing -->
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Publishing</h3>
        <div class="mt-4 space-y-4">
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Status</p>
                <div class="flex items-center gap-2 mt-1">
                    <div x-data="{
                        is_publish: {{ isset($company) && $company->is_publish ? 'true' : 'false' }},
                        loading: false,
                        async togglePublish() {
                            if (!this.canToggle) return;
                            this.loading = true;
                            try {
                                const res = await fetch('{{ isset($company) ? route('admin.companies.toggle-publish', $company->id) : '#' }}', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                        'Accept': 'application/json',
                                    },
                                });
                                if (res.ok) {
                                    this.is_publish = !this.is_publish;
                                }
                            } finally {
                                this.loading = false;
                            }
                        },
                        canToggle: {{ isset($company) ? 'true' : 'false' }}
                    }" class="flex items-center justify-between gap-2 w-full">
                        <span class="text-sm font-semibold" :class="is_publish ? 'text-emerald-700' : 'text-slate-700'" x-text="is_publish ? 'Published' : 'Draft'"></span>
                        <button type="button"
                            @click="togglePublish"
                            :disabled="loading || !canToggle"
                            class="inline-flex items-center px-3 py-1 rounded-full font-semibold text-xs border transition"
                            :class="!is_publish && canToggle ? 'bg-emerald-100 text-emerald-700 border-emerald-200 hover:bg-emerald-200' : (is_publish && canToggle ? 'bg-slate-200 text-slate-700 border-slate-300 hover:bg-slate-300' : 'bg-slate-100 text-slate-400 border-slate-200 cursor-not-allowed')"
                        >
                            <span x-show="!loading" x-text="canToggle ? (is_publish ? 'Unpublish' : 'Publish') : 'Publish'"></span>
                            <span x-show="loading">...</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Last Updated</p>
                <p class="mt-1 text-sm font-semibold text-slate-900">
                    @if(isset($company))
                        {{ $company->updated_at ? $company->updated_at->format('F j, Y') : ($company->created_at ? $company->created_at->format('F j, Y') : 'N/A') }}
                    @else
                        N/A
                    @endif
                </p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Created By</p>
                <p class="mt-1 text-sm font-semibold text-slate-900">
                    @if(isset($company) && $company->creator && $company->creator->name)
                        {{ $company->creator->name }}
                    @elseif(isset($company) && $company->created_by)
                        {{ $company->created_by }}
                    @else
                        N/A
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Completion -->
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex items-center justify-between">
        <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Completion</h3>
        <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">72%</span>
        </div>

        <div class="mt-4 h-2.5 w-full overflow-hidden rounded-full bg-slate-100">
        <div class="h-full w-[72%] rounded-full bg-sky-600"></div>
        </div>

        <ul class="mt-4 space-y-3 text-sm text-slate-600">
        <li class="flex items-center gap-2">
            <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
            Basic information completed
        </li>
        <li class="flex items-center gap-2">
            <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
            Links added
        </li>
        <li class="flex items-center gap-2">
            <span class="h-2.5 w-2.5 rounded-full bg-amber-400"></span>
            Compensation partially completed
        </li>
        <li class="flex items-center gap-2">
            <span class="h-2.5 w-2.5 rounded-full bg-slate-300"></span>
            Gallery pending
        </li>
        </ul>
    </div>

    <!-- Tips -->
    <div class="rounded-2xl border border-sky-200 bg-gradient-to-br from-sky-50 to-indigo-50 p-5 shadow-sm">
        <h3 class="text-sm font-semibold uppercase tracking-wide text-sky-700">Admin Tips</h3>
        <div class="mt-4 space-y-3 text-sm leading-6 text-slate-700">
        <p>Use concise descriptions for dashboard cards and a fuller description for the dedicated company profile page.</p>
        <p>Enable spillover only for companies that support WLA’s placement and eligibility logic.</p>
        <p>Use consistent logo sizes and banner dimensions for a cleaner company directory layout.</p>
        </div>
    </div>
</aside>