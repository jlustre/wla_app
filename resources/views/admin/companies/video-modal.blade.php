<div x-data="{ showVideoModal: false }" @keydown.escape.window="showVideoModal = false">
    <template x-if="showVideoModal">
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70">
        <div class="relative w-full max-w-2xl bg-slate-900 rounded-2xl shadow-2xl overflow-hidden">
        <button @click="showVideoModal = false" class="absolute top-2 right-2 z-10 text-white bg-black/40 hover:bg-black/70 rounded-full p-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <div class="aspect-w-16 aspect-h-9 w-full">
            <iframe
            src="https://www.youtube.com/embed/6yKAyv5mav0"
            title="Overview Video"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen
            class="w-full h-96"
            ></iframe>
        </div>
        </div>
    </div>
    </template>
</div>