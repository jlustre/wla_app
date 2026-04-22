<div class="overflow-hidden rounded-3xl border shadow-2xl" style="border-color: {{ $company->highlight_color ?? 'rgba(56,189,248,0.2)' }}; background: {{ $company->style_meta['hero_bg'] ?? 'linear-gradient(to bottom right, #0f172a, #020617, #0f172a)' }};">
    <div class="grid gap-8 px-6 py-8 sm:px-8 lg:grid-cols-[1.3fr_0.7fr] lg:px-10 lg:py-10">
    <div>
        <div class="flex flex-wrap items-center gap-4">
        <div class="flex h-16 w-16 items-center justify-center rounded-2xl border text-lg font-bold" style="border-color: {{ $company->secondary_color ?? 'rgba(255,255,255,0.1)' }}; background: {{ $company->style_meta['logo_bg'] ?? 'rgba(255,255,255,0.05)' }}; color: {{ $company->primary_color ?? '#38bdf8' }};">
            {!! $company->logo ? '<img src="' . asset('storage/' . $company->logo) . '" alt="Logo" class="h-12 w-12 object-contain" />' : 'LOGO' !!}
        </div>
        <div>
            <div class="flex flex-wrap items-center gap-3">
            <div>
                <h2 class="text-2xl font-extrabold sm:text-3xl" style="color: {{ $company->style_meta['hero_title'] ?? '#fff' }}">{{ $company->name }}</h2>
                @if($company->tagline)
                <div class="mt-1 text-xs sm:text-sm font-medium text-sky-200">{{ $company->tagline }}</div>
                @endif
            </div>
            <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em]" style="border-color: {{ $company->highlight_color ?? 'rgba(251,191,36,0.2)' }}; background: {{ $company->style_meta['status_bg'] ?? 'rgba(251,191,36,0.1)' }}; color: {{ $company->highlight_color ?? '#fbbf24' }};">
                Not Joined
            </span>
            </div>
            <p class="mt-2 text-sm leading-6 sm:text-base" style="color: {{ $company->style_meta['hero_subtitle'] ?? '#cbd5e1' }};">
            {{ $company->description }}
            </p>
        </div>
        <div class="mt-4 w-full">
            @if($company->banner)
            <img src="{{ asset('storage/' . $company->banner) }}" alt="Company Banner" class="w-full max-h-56 object-cover rounded-2xl shadow" />
            @else
            <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80" alt="Splash Placeholder" class="w-full max-h-56 object-cover rounded-2xl shadow" />
            @endif
        </div>
        @if($company->short_description)
            <p class="mt-4 text-base font-semibold text-sky-200 w-full">{{ $company->short_description }}</p>
        @endif
        @if($company->full_description)
            <p class="mt-2 text-sm text-slate-200 w-full">{{ $company->full_description }}</p>
        @endif
        </div>

        <div class="mt-6 grid gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border p-4" style="border-color: {{ $company->secondary_color ?? 'rgba(255,255,255,0.1)' }}; background: {{ $company->style_meta['card_bg'] ?? 'rgba(255,255,255,0.05)' }};">
            <p class="text-xs font-semibold uppercase tracking-[0.18em]" style="color: {{ $company->primary_color ?? '#38bdf8' }};">Category</p>
            <p class="mt-2 text-sm font-medium" style="color: {{ $company->style_meta['card_text'] ?? '#fff' }};">{{ $company->category }}</p>
        </div>
        <div class="rounded-2xl border p-4" style="border-color: {{ $company->secondary_color ?? 'rgba(255,255,255,0.1)' }}; background: {{ $company->style_meta['card_bg'] ?? 'rgba(255,255,255,0.05)' }};">
            <p class="text-xs font-semibold uppercase tracking-[0.18em]" style="color: {{ $company->primary_color ?? '#38bdf8' }};">Main Products</p>
            <p class="mt-2 text-sm font-medium" style="color: {{ $company->style_meta['card_text'] ?? '#fff' }};">{{ $company->style_meta['main_products'] ?? 'N/A' }}</p>
        </div>
        <div class="rounded-2xl border p-4" style="border-color: {{ $company->secondary_color ?? 'rgba(255,255,255,0.1)' }}; background: {{ $company->style_meta['card_bg'] ?? 'rgba(255,255,255,0.05)' }};">
            <p class="text-xs font-semibold uppercase tracking-[0.18em]" style="color: {{ $company->primary_color ?? '#38bdf8' }};">Compensation</p>
            <p class="mt-2 text-sm font-medium" style="color: {{ $company->style_meta['card_text'] ?? '#fff' }};">{{ $company->style_meta['compensation'] ?? 'N/A' }}</p>
        </div>
        </div>

        @include('admin.companies.overview-video')
    </div>

    @include('admin.companies.right-card')
    </div>
</div>