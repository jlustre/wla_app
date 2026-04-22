<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <form wire:submit.prevent="save" enctype="multipart/form-data">
        <div class="border-b border-slate-200 px-5 py-4 md:px-6">
            <div class="flex items-start gap-3">
                <div class="mt-0.5 flex h-10 w-10 items-center justify-center rounded-xl bg-sky-100 text-sky-700">
                    <!-- SVG icon -->
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Company Basic Information</h2>
                    <p class="mt-1 text-sm text-slate-500">Core identity, descriptions, and branding assets.</p>
                </div>
            </div>
        </div>
        <div class="space-y-6 px-5 py-5 md:px-6">
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="md:col-span-1">
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Company Name<span class="text-rose-500">*</span>
                    </label>
                    <input type="text" wire:model="name" placeholder="Enter company name" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-4 focus:ring-sky-100" required />
                    @error('name') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-1">
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Slug <span class="text-rose-500">*</span>
                    </label>
                    <div class="flex overflow-hidden rounded-xl border border-slate-300 bg-white focus-within:border-sky-500 focus-within:ring-4 focus-within:ring-sky-100">
                        <span class="inline-flex items-center border-r border-slate-200 bg-slate-50 px-4 text-sm text-slate-500">/companies/</span>
                        <input type="text" wire:model="slug" placeholder="company-slug" class="w-full px-4 py-3 text-sm text-slate-800 outline-none placeholder:text-slate-400" required />
                    </div>
                    @error('slug') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Short Description</label>
                    <textarea wire:model="short_description" rows="3" placeholder="Write a short summary of the company..." class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-4 focus:ring-sky-100"></textarea>
                    @error('short_description') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Full Description</label>
                    <textarea wire:model="full_description" rows="6" placeholder="Write a more complete company full description..." class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-4 focus:ring-sky-100"></textarea>
                    @error('full_description') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Company Logo</label>
                    <input type="file" wire:model="logoFile" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100" />
                    @isset($logoFile)
                        @if ($logoFile)
                            <div class="mt-2"><img src="{{ $logoFile->temporaryUrl() }}" alt="Logo Preview" class="h-10 w-10 rounded-full border border-slate-200"></div>
                        @endif
                    @endisset
                    @if (!isset($logoFile) && ($company ?? false) && ($company->logo ?? false))
                        <div class="mt-2"><img src="{{ asset('storage/' . $company->logo) }}" alt="Logo" class="h-10 w-10 rounded-full border border-slate-200"></div>
                    @elseif (!isset($logoFile))
                        <div class="mt-2"><img src="{{ asset('images/branding/wlalogo.svg') }}" alt="Default Logo" class="h-10 w-10 rounded-full border border-slate-200"></div>
                    @endif
                    @error('logo') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Cover Image / Banner</label>
                    <input type="file" wire:model="bannerFile" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100" />
                    @isset($bannerFile)
                        @if ($bannerFile)
                            <div class="mt-2"><img src="{{ $bannerFile->temporaryUrl() }}" alt="Banner Preview" class="h-16 rounded-xl border border-slate-200"></div>
                        @endif
                    @endisset
                    @if (!isset($bannerFile) && ($company ?? false) && ($company->banner ?? false))
                        <div class="mt-2"><img src="{{ asset('storage/' . $company->banner) }}" alt="Banner" class="h-16 rounded-xl border border-slate-200"></div>
                    @endif
                    @error('banner') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="flex justify-end mt-6">
                <button type="submit" class="inline-flex items-center px-6 py-2 rounded-xl bg-teal-600 text-white font-bold shadow hover:bg-teal-700 transition">Save</button>
            </div>
            @if (session()->has('success'))
                <div class="mt-4 text-green-600">{{ session('success') }}</div>
            @endif
        </div>
    </form>
</div>
</div>
