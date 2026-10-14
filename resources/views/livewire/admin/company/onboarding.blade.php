@php
    $steps = [1 => 'Company Profile', 2 => 'Description & Branding', 3 => 'Links & Contact', 4 => 'Review & Checklist'];
    $inputClass = 'w-full rounded-xl border border-white/10 bg-slate-900/70 px-4 py-3 text-sm text-slate-100 placeholder:text-slate-500 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-500/10';
    $labelClass = 'mb-2 block text-sm font-semibold text-slate-200';
@endphp

<div class="space-y-6">

    {{-- Stepper header --}}
    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-xl">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-sky-300">{{ $this->isEditing() ? 'Company Management' : 'Company Onboarding' }}</p>
                <h2 class="mt-2 text-2xl font-extrabold text-white">{{ $this->isEditing() ? 'Edit '.$company->name : 'Add New Affiliated Company' }}</h2>
            </div>
            <div class="flex items-center gap-3">
                @if ($this->isEditing())
                    <a href="{{ route('admin.companies.dashboard-contents.index', $company) }}" class="inline-flex items-center justify-center rounded-xl border border-white/10 bg-slate-900/60 px-4 py-2.5 text-sm font-semibold text-slate-200 transition hover:bg-white/10">
                        Manage Products / Compensation / Resources
                    </a>
                @endif
                <div class="text-right">
                    <p class="text-sm font-semibold text-white">{{ $summary['percent'] }}% complete</p>
                    <p class="text-xs text-slate-400">{{ $summary['requiredDone'] }} of {{ $summary['requiredTotal'] }} required items done</p>
                </div>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-2 gap-3 lg:grid-cols-4">
            @foreach ($steps as $num => $label)
                <button type="button" wire:click="goToStep({{ $num }})" class="rounded-2xl border px-4 py-3 text-left transition {{ $step === $num ? 'border-sky-400 bg-sky-500/10' : 'border-white/10 bg-slate-900/60 hover:border-white/20' }}">
                    <span class="text-xs font-semibold {{ $step === $num ? 'text-sky-300' : 'text-slate-500' }}">Step {{ $num }}</span>
                    <p class="mt-1 text-sm font-bold {{ $step === $num ? 'text-white' : 'text-slate-300' }}">{{ $label }}</p>
                </button>
            @endforeach
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-[1fr_360px]">

        {{-- Step content --}}
        <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-xl">
            @if (session('success'))
                <div class="mb-6 rounded-2xl border border-emerald-400/30 bg-emerald-500/10 p-4 text-sm text-emerald-200">{{ session('success') }}</div>
            @endif

            @if ($errors->has('submit'))
                <div class="mb-6 rounded-2xl border border-rose-400/30 bg-rose-500/10 p-4 text-sm text-rose-200">{{ $errors->first('submit') }}</div>
            @endif

            <form wire:submit.prevent="{{ $step === 4 ? 'save' : 'nextStep' }}">

                {{-- Step 1: Company Profile --}}
                @if ($step === 1)
                    <div class="space-y-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-sky-300">Step 1 of 4</p>
                        <h3 class="text-xl font-extrabold text-white">Company Profile</h3>

                        <div class="grid gap-5 md:grid-cols-2">
                            <div>
                                <label class="{{ $labelClass }}">Company Name <span class="text-rose-400">*</span></label>
                                <input type="text" wire:model.live.debounce.400ms="name" placeholder="e.g. Blue Ocean Consulting" class="{{ $inputClass }}">
                                @error('name') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="{{ $labelClass }}">URL Slug <span class="text-rose-400">*</span></label>
                                <input type="text" wire:model.live.debounce.400ms="slug" placeholder="blue-ocean-consulting" class="{{ $inputClass }}">
                                @error('slug') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="{{ $labelClass }}">Category <span class="text-rose-400">*</span></label>
                                <input type="text" wire:model.live.debounce.400ms="category" placeholder="e.g. Consulting, Health, Technology" class="{{ $inputClass }}">
                                @error('category') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="{{ $labelClass }}">Status <span class="text-rose-400">*</span></label>
                                <select wire:model.live="status" class="{{ $inputClass }}">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                @error('status') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="{{ $labelClass }}">Tagline</label>
                                <input type="text" wire:model.live.debounce.400ms="tagline" placeholder="A short marketing tagline" class="{{ $inputClass }}">
                            </div>
                            <div>
                                <label class="{{ $labelClass }}">CEO / Contact Name</label>
                                <input type="text" wire:model.live.debounce.400ms="ceo_name" placeholder="e.g. Jane Smith" class="{{ $inputClass }}">
                            </div>
                            <div class="md:col-span-2">
                                <label class="{{ $labelClass }}">Dashboard Theme</label>
                                <select wire:model.live="theme_id" class="{{ $inputClass }}">
                                    <option value="">No theme selected</option>
                                    @foreach ($themes as $theme)
                                        <option value="{{ $theme->id }}">{{ $theme->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Step 2: Description & Branding --}}
                @if ($step === 2)
                    <div class="space-y-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-sky-300">Step 2 of 4</p>
                        <h3 class="text-xl font-extrabold text-white">Description & Branding</h3>

                        <div>
                            <label class="{{ $labelClass }}">Short Description <span class="text-rose-400">*</span></label>
                            <textarea wire:model.live.debounce.400ms="short_description" rows="3" placeholder="One or two sentences introducing this company" class="{{ $inputClass }}"></textarea>
                            @error('short_description') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="{{ $labelClass }}">Full Description</label>
                            <textarea wire:model.live.debounce.400ms="full_description" rows="6" placeholder="A complete overview of the company, its products, and its opportunity" class="{{ $inputClass }}"></textarea>
                        </div>

                        <div class="grid gap-5 md:grid-cols-2">
                            <div>
                                <label class="{{ $labelClass }}">Logo @unless ($this->isEditing()) <span class="text-rose-400">*</span> @endunless</label>
                                <input type="file" wire:model="logo" accept="image/*" class="block w-full text-sm text-slate-300 file:mr-4 file:rounded-full file:border-0 file:bg-sky-500/10 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-sky-300 hover:file:bg-sky-500/20">
                                <div wire:loading wire:target="logo" class="mt-2 text-xs text-slate-400">Uploading…</div>
                                @if ($logo)
                                    <img src="{{ $logo->temporaryUrl() }}" class="mt-3 h-16 w-16 rounded-xl border border-white/10 object-cover" alt="Logo preview">
                                @elseif ($existingLogo)
                                    <div class="mt-3 flex items-center gap-2">
                                        <img src="{{ asset('storage/'.$existingLogo) }}" class="h-16 w-16 rounded-xl border border-white/10 object-cover" alt="Current logo">
                                        <span class="text-xs text-slate-400">Current logo &mdash; choose a file to replace it.</span>
                                    </div>
                                @endif
                                @error('logo') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="{{ $labelClass }}">Banner Image</label>
                                <input type="file" wire:model="banner" accept="image/*" class="block w-full text-sm text-slate-300 file:mr-4 file:rounded-full file:border-0 file:bg-sky-500/10 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-sky-300 hover:file:bg-sky-500/20">
                                <div wire:loading wire:target="banner" class="mt-2 text-xs text-slate-400">Uploading…</div>
                                @if ($banner)
                                    <img src="{{ $banner->temporaryUrl() }}" class="mt-3 h-16 w-full rounded-xl border border-white/10 object-cover" alt="Banner preview">
                                @elseif ($existingBanner)
                                    <div class="mt-3">
                                        <img src="{{ asset('storage/'.$existingBanner) }}" class="h-16 w-full rounded-xl border border-white/10 object-cover" alt="Current banner">
                                        <p class="mt-1 text-xs text-slate-400">Current banner &mdash; choose a file to replace it.</p>
                                    </div>
                                @endif
                                @error('banner') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <p class="{{ $labelClass }}">Brand Colors</p>
                            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                                @foreach (['primary_color' => 'Primary', 'secondary_color' => 'Secondary', 'highlight_color' => 'Highlight', 'background_color' => 'Background'] as $field => $label)
                                    <div class="flex items-center gap-2 rounded-xl border border-white/10 bg-slate-900/70 p-2">
                                        <input type="color" wire:model.live="{{ $field }}" class="h-9 w-9 shrink-0 cursor-pointer rounded-lg border-0 bg-transparent">
                                        <input type="text" wire:model.live.debounce.400ms="{{ $field }}" placeholder="{{ $label }} (#hex)" class="w-full bg-transparent text-sm text-slate-100 outline-none placeholder:text-slate-500">
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label class="{{ $labelClass }}">Advanced Styling &amp; Highlights (JSON)</label>
                            <textarea wire:model.live.debounce.400ms="style_meta_json" rows="4" placeholder='{"main_products": "Nutrition, skincare", "compensation": "Binary + unilevel"}' class="{{ $inputClass }} font-mono text-xs"></textarea>
                            <p class="mt-1 text-xs text-slate-500">Optional. Powers the "Main Products" / "Compensation" quick-facts on the member dashboard, plus custom color overrides (e.g. <code>hero_bg</code>, <code>card_bg</code>). Leave blank to use defaults.</p>
                            @error('style_meta_json') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
                        </div>
                    </div>
                @endif

                {{-- Step 3: Links & Contact --}}
                @if ($step === 3)
                    <div class="space-y-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-sky-300">Step 3 of 4</p>
                        <h3 class="text-xl font-extrabold text-white">Links & Contact</h3>

                        <div class="grid gap-5 md:grid-cols-2">
                            <div>
                                <label class="{{ $labelClass }}">Website Link <span class="text-rose-400">*</span></label>
                                <input type="text" wire:model.live.debounce.400ms="website_link" placeholder="https://company.com" class="{{ $inputClass }}">
                                @error('website_link') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="{{ $labelClass }}">Sign-up Link <span class="text-rose-400">*</span></label>
                                <input type="text" wire:model.live.debounce.400ms="signup_link" placeholder="https://company.com/join" class="{{ $inputClass }}">
                                @error('signup_link') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="{{ $labelClass }}">How Do New Members Join? <span class="text-rose-400">*</span></label>
                                <select wire:model.live="signup_link_type" class="{{ $inputClass }}">
                                    @foreach (\App\Models\Company::SIGNUP_LINK_TYPES as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-xs text-slate-500">
                                    Unlike WLA's own invite link (a fixed link with a tracked reference code), each
                                    company's registration page is external and varies &mdash; it may be one shared
                                    link, or a form where the new member types in their sponsor's details themselves.
                                </p>
                                @error('signup_link_type') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
                            </div>
                            @if ($signup_link_type === \App\Models\Company::SIGNUP_LINK_TYPE_SPONSOR_ENTRY)
                                <div>
                                    <label class="{{ $labelClass }}">Sponsor Field New Members Enter <span class="text-rose-400">*</span></label>
                                    <select wire:model.live="signup_sponsor_field" class="{{ $inputClass }}">
                                        <option value="">Select one&hellip;</option>
                                        @foreach (\App\Models\Company::SPONSOR_FIELDS as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <p class="mt-1 text-xs text-slate-500">
                                        Shown to sponsors so they know exactly what to give their prospect
                                        (e.g. "Enter my username" vs. "Enter my member ID") when registering here.
                                    </p>
                                    @error('signup_sponsor_field') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
                                </div>
                            @endif
                            <div>
                                <label class="{{ $labelClass }}">Compensation Plan Link <span class="text-rose-400">*</span></label>
                                <input type="text" wire:model.live.debounce.400ms="comp_plan_link" placeholder="https://company.com/comp-plan" class="{{ $inputClass }}">
                                @error('comp_plan_link') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="{{ $labelClass }}">Back-office Link</label>
                                <input type="text" wire:model.live.debounce.400ms="backoffice_link" placeholder="https://company.com/backoffice" class="{{ $inputClass }}">
                            </div>
                            <div>
                                <label class="{{ $labelClass }}">Intro Video Link</label>
                                <input type="text" wire:model.live.debounce.400ms="intro_video_link" placeholder="https://youtube.com/..." class="{{ $inputClass }}">
                            </div>
                            <div>
                                <label class="{{ $labelClass }}">Webinar Link</label>
                                <input type="text" wire:model.live.debounce.400ms="webinar_link" placeholder="https://company.com/webinar" class="{{ $inputClass }}">
                            </div>
                            <div>
                                <label class="{{ $labelClass }}">Phone Number</label>
                                <input type="text" wire:model.live.debounce.400ms="phone" placeholder="+1 555-010-2200" class="{{ $inputClass }}">
                            </div>
                            <div>
                                <label class="{{ $labelClass }}">Location</label>
                                <input type="text" wire:model.live.debounce.400ms="location" placeholder="City, Country" class="{{ $inputClass }}">
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Step 4: Review & Checklist --}}
                @if ($step === 4)
                    <div class="space-y-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-sky-300">Step 4 of 4</p>
                        <h3 class="text-xl font-extrabold text-white">{{ $this->isEditing() ? 'Review & Save' : 'Review & Create' }}</h3>
                        <p class="text-sm text-slate-400">Confirm the pertinent information below, then {{ $this->isEditing() ? 'save your changes' : 'create the company' }}. You can keep editing everything afterward.</p>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                                <p class="text-xs uppercase tracking-[0.18em] text-slate-400">Name</p>
                                <p class="mt-1 font-semibold text-white">{{ $name ?: '—' }}</p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                                <p class="text-xs uppercase tracking-[0.18em] text-slate-400">Category</p>
                                <p class="mt-1 font-semibold text-white">{{ $category ?: '—' }}</p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                                <p class="text-xs uppercase tracking-[0.18em] text-slate-400">Website</p>
                                <p class="mt-1 truncate font-semibold text-white">{{ $website_link ?: '—' }}</p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                                <p class="text-xs uppercase tracking-[0.18em] text-slate-400">Status</p>
                                <p class="mt-1 font-semibold text-white">{{ $status === '1' ? 'Active' : 'Inactive' }}</p>
                            </div>
                        </div>

                        @unless ($summary['readyToSubmit'])
                            <div class="rounded-2xl border border-amber-400/30 bg-amber-500/10 p-4 text-sm text-amber-200">
                                {{ $summary['requiredTotal'] - $summary['requiredDone'] }} required checklist item(s) still need attention before you can create this company. Use the checklist on the right to jump to any missing field.
                            </div>
                        @endunless
                    </div>
                @endif

                <div class="mt-8 flex items-center justify-between border-t border-white/10 pt-6">
                    <button type="button" wire:click="previousStep" @if ($step === 1) disabled @endif class="inline-flex items-center justify-center rounded-xl border border-white/10 px-5 py-2.5 text-sm font-semibold text-slate-200 transition hover:bg-white/10 disabled:cursor-not-allowed disabled:opacity-40">
                        Back
                    </button>

                    @if ($step < 4)
                        <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-sky-500 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-sky-400">
                            Continue
                        </button>
                    @else
                        <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-emerald-500 px-6 py-2.5 text-sm font-semibold text-slate-950 transition hover:bg-emerald-400 disabled:cursor-not-allowed disabled:opacity-40" @if (! $summary['readyToSubmit']) disabled @endif>
                            {{ $this->isEditing() ? 'Save Changes' : 'Create Company' }}
                        </button>
                    @endif
                </div>
            </form>
        </div>

        {{-- Checklist sidebar --}}
        <div class="space-y-4">
            <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-xl">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-sky-300">Completeness</p>
                    <span class="text-sm font-bold text-white">{{ $summary['done'] }}/{{ $summary['total'] }}</span>
                </div>
                <div class="mt-4 h-3 overflow-hidden rounded-full bg-slate-800">
                    <div class="h-3 rounded-full bg-gradient-to-r from-sky-400 to-emerald-400 transition-all" style="width: {{ $summary['percent'] }}%"></div>
                </div>
                <p class="mt-3 text-xs text-slate-400">{{ $summary['requiredDone'] }} of {{ $summary['requiredTotal'] }} required fields completed</p>
            </div>

            <div class="rounded-3xl border border-white/10 bg-white/5 p-5 shadow-xl">
                <p class="mb-3 text-xs font-semibold uppercase tracking-[0.18em] text-sky-300">Pertinent Information Checklist</p>
                <ul class="space-y-1">
                    @foreach ($checklist as $item)
                        <li>
                            <button type="button" wire:click="goToStep({{ $item['step'] }})" class="flex w-full items-center gap-3 rounded-xl px-3 py-2 text-left transition hover:bg-white/5">
                                <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full {{ $item['done'] ? 'bg-emerald-500 text-slate-950' : ($item['required'] ? 'border border-rose-400/60 text-rose-300' : 'border border-slate-600 text-slate-500') }}">
                                    @if ($item['done'])
                                        <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-7 7a1 1 0 01-1.4 0l-3-3a1 1 0 111.4-1.4L9 11.6l6.3-6.3a1 1 0 011.4 0z" clip-rule="evenodd" /></svg>
                                    @endif
                                </span>
                                <span class="min-w-0 flex-1 truncate text-sm {{ $item['done'] ? 'text-slate-300' : 'text-white' }}">{{ $item['label'] }}</span>
                                @if ($item['required'] && ! $item['done'])
                                    <span class="shrink-0 text-[10px] font-bold uppercase tracking-wide text-rose-300">Required</span>
                                @endif
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
