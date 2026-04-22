<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-200 px-5 py-4 md:px-6">
        <div class="flex items-start gap-3">
        <div class="mt-0.5 flex h-10 w-10 items-center justify-center rounded-xl bg-fuchsia-100 text-fuchsia-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L14 14l2.586-2.586a2 2 0 012.828 0L20 12m1 7H3a1 1 0 01-1-1V6a1 1 0 011-1h18a1 1 0 011 1v12a1 1 0 01-1 1z" />
            </svg>
        </div>
        <div>
            <h2 class="text-lg font-semibold text-slate-900">Media Gallery</h2>
            <p class="mt-1 text-sm text-slate-500">Upload and preview marketing or branding images.</p>
        </div>
        </div>
    </div>

    <div class="space-y-5 px-5 py-5 md:px-6">
        <label class="flex min-h-[180px] cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 px-6 py-10 text-center transition hover:border-sky-400 hover:bg-sky-50">
        <div class="mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-white text-slate-500 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
        </div>
        <p class="text-sm font-semibold text-slate-700">Drag & drop multiple images here</p>
        <p class="mt-1 text-xs text-slate-500">or click to browse files</p>
        <input type="file" multiple class="hidden" />
        </label>

        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-100">
            <div class="aspect-[4/3] w-full bg-slate-200"></div>
            <div class="flex items-center justify-between px-3 py-2">
            <p class="truncate text-xs font-medium text-slate-600">preview-1.jpg</p>
            <button type="button" class="text-xs font-semibold text-rose-600 hover:text-rose-700">Remove</button>
            </div>
        </div>
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-100">
            <div class="aspect-[4/3] w-full bg-slate-200"></div>
            <div class="flex items-center justify-between px-3 py-2">
            <p class="truncate text-xs font-medium text-slate-600">preview-2.jpg</p>
            <button type="button" class="text-xs font-semibold text-rose-600 hover:text-rose-700">Remove</button>
            </div>
        </div>
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-100">
            <div class="aspect-[4/3] w-full bg-slate-200"></div>
            <div class="flex items-center justify-between px-3 py-2">
            <p class="truncate text-xs font-medium text-slate-600">preview-3.jpg</p>
            <button type="button" class="text-xs font-semibold text-rose-600 hover:text-rose-700">Remove</button>
            </div>
        </div>
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-100">
            <div class="aspect-[4/3] w-full bg-slate-200"></div>
            <div class="flex items-center justify-between px-3 py-2">
            <p class="truncate text-xs font-medium text-slate-600">preview-4.jpg</p>
            <button type="button" class="text-xs font-semibold text-rose-600 hover:text-rose-700">Remove</button>
            </div>
        </div>
        </div>
    </div>
</div>