<div class="mt-6 flex flex-wrap gap-3">
    @if(isset($company->style_meta['actions']))
        {!! $company->style_meta['actions'] !!}
    @else
    <div x-data="{ showVideoModal: false }">
        <a href="#" class="inline-flex items-center justify-center rounded-xl px-5 py-3 text-sm font-semibold text-white transition" style="background: {{ $company->primary_color ?? '#0ea5e9' }};">
        Join Now
        </a>
        <a href="{{ $company->website ?? '#' }}" class="inline-flex items-center justify-center rounded-xl border px-5 py-3 text-sm font-semibold transition" style="border-color: {{ $company->secondary_color ?? 'rgba(255,255,255,0.1)' }}; background: {{ $company->style_meta['button_bg'] ?? 'rgba(255,255,255,0.05)' }}; color: {{ $company->secondary_color ?? '#f1f5f9' }};">
        Visit Website
        </a>
        <button
        @click="showVideoModal = true"
        type="button"
        class="inline-flex items-center justify-center rounded-xl border px-5 py-3 text-sm font-semibold transition"
        style="border-color: {{ $company->secondary_color ?? 'rgba(255,255,255,0.1)' }}; background: {{ $company->style_meta['button_bg'] ?? 'rgba(255,255,255,0.05)' }}; color: {{ $company->secondary_color ?? '#f1f5f9' }};"
        >
        Watch Overview Video
        </button>
        <!-- Video Modal -->
        <div x-show="showVideoModal" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/70">
        <div class="relative w-full max-w-2xl bg-slate-900 rounded-2xl shadow-2xl overflow-hidden border-4 border-slate-100/80">
            <button @click="showVideoModal = false" class="absolute top-2 right-2 z-20 text-white bg-black/40 hover:bg-black/70 rounded-full p-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
            </button>
            <div class="aspect-w-16 aspect-h-9 w-full">
            <template x-if="showVideoModal">
                <iframe
                src="https://www.youtube.com/embed/6yKAyv5mav0"
                title="Overview Video"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen
                class="w-full h-96"
                ></iframe>
            </template>
            </div>
        </div>
        </div>
    </div>
    @endif
    </div>