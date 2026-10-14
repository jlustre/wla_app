@php $resourceItems = $contentSections->get('resources', collect()); @endphp

<div class="rounded-3xl border border-white/10 bg-white/5 p-6 sm:p-8">
    <div class="mb-6">
        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-sky-300">Resources & Training</p>
        <h2 class="mt-2 text-2xl font-extrabold text-white">Learning Materials</h2>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        @if($company->intro_video_link)
            <button type="button" @click="showVideoModal = true" class="rounded-2xl border border-white/10 bg-slate-900/60 p-5 text-left transition hover:border-sky-400/30 hover:bg-slate-900">
                <h3 class="text-base font-bold text-white">Intro Video</h3>
                <p class="mt-2 text-sm leading-6 text-slate-400">Watch the official company introduction and overview video.</p>
            </button>
        @endif

        @if($company->webinar_link)
            <a href="{{ $company->webinar_link }}" target="_blank" rel="noopener" class="rounded-2xl border border-white/10 bg-slate-900/60 p-5 transition hover:border-sky-400/30 hover:bg-slate-900">
                <h3 class="text-base font-bold text-white">Webinars & Events</h3>
                <p class="mt-2 text-sm leading-6 text-slate-400">Join live or replay training calls, webinars, and company updates.</p>
            </a>
        @endif

        @forelse($resourceItems as $item)
            <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-5 {{ $item->element_class }}">
                @if($item->title)
                    <h3 class="text-base font-bold text-white">{{ $item->title }}</h3>
                @endif
                @if($item->content)
                    <div class="mt-2 text-sm leading-6 text-slate-400">{!! $item->content !!}</div>
                @endif
            </div>
        @empty
            @unless($company->intro_video_link || $company->webinar_link)
                <div class="rounded-2xl border border-dashed border-white/15 bg-slate-900/40 p-5 text-sm text-slate-400 md:col-span-2">
                    No resources have been published for {{ $company->name }} yet. Check back soon.
                </div>
            @endunless
        @endforelse
    </div>
</div>
