<script>
function companyForm(initial) {
    return {
        ...initial,
        logoPreview: null,
        bannerPreview: null,
        success: '',
        error: '',
        handleFile(event, type) {
            const file = event.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (e) => {
                if (type === 'logo') {
                    this.logoPreview = e.target.result;
                } else if (type === 'banner') {
                    this.bannerPreview = e.target.result;
                }
            };
            reader.readAsDataURL(file);
        },
        async submitForm() {
            this.success = '';
            this.error = '';
            const formData = new FormData(this.$refs.companyForm);
            // Add Alpine state fields
            for (const key of Object.keys(this)) {
                if (typeof this[key] !== 'function' && !formData.has(key)) {
                    formData.append(key, this[key]);
                }
            }
            let url = this.companyId ? `/admin/companies/${this.companyId}` : '/admin/companies';
            let method = this.companyId ? 'POST' : 'POST';
            if (this.companyId) formData.append('_method', 'PUT');
            try {
                const res = await fetch(url, {
                    method,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: formData
                });
                const data = await res.json();
                if (res.ok) {
                    this.success = data.message || 'Saved!';
                } else {
                    this.error = data.message || 'Error saving.';
                }
            } catch (e) {
                this.error = 'Network error.';
            }
        }
    }
}
</script>
<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
     x-data="companyForm({
        companyId: {{ isset($company) && $company->id ? $company->id : 'null' }},
        name: '{{ isset($company) ? addslashes($company->name) : '' }}',
        slug: '{{ isset($company) ? addslashes($company->slug) : '' }}',
        short_description: `{{ isset($company) ? addslashes($company->short_description) : '' }}`,
        full_description: `{{ isset($company) ? addslashes($company->full_description) : '' }}`,
        logoUrl: '{{ isset($company) && $company->logo ? asset('storage/' . $company->logo) : '' }}',
        bannerUrl: '{{ isset($company) && $company->banner ? asset('storage/' . $company->banner) : '' }}',
        theme_id: '{{ isset($company) ? $company->theme_id : '' }}',
        category: '{{ isset($company) ? addslashes($company->category) : '' }}',
        website_link: '{{ isset($company) ? addslashes($company->website_link) : '' }}',
        comp_plan_link: '{{ isset($company) ? addslashes($company->comp_plan_link) : '' }}',
        signup_link: '{{ isset($company) ? addslashes($company->signup_link) : '' }}',
        backoffice_link: '{{ isset($company) ? addslashes($company->backoffice_link) : '' }}',
        intro_video_link: '{{ isset($company) ? addslashes($company->intro_video_link) : '' }}',
        webinar_link: '{{ isset($company) ? addslashes($company->webinar_link) : '' }}',
        location: '{{ isset($company) ? addslashes($company->location) : '' }}',
        phone: '{{ isset($company) ? addslashes($company->phone) : '' }}',
        tagline: '{{ isset($company) ? addslashes($company->tagline) : '' }}',
        ceo_name: '{{ isset($company) ? addslashes($company->ceo_name) : '' }}',
        status: '{{ isset($company) ? addslashes($company->status) : '' }}',
        primary_color: '{{ isset($company) ? addslashes($company->primary_color) : '' }}',
        secondary_color: '{{ isset($company) ? addslashes($company->secondary_color) : '' }}',
        highlight_color: '{{ isset($company) ? addslashes($company->highlight_color) : '' }}',
        background_color: '{{ isset($company) ? addslashes($company->background_color) : '' }}',
        style_meta: '{{ isset($company) ? json_encode($company->style_meta) : '' }}'
    })">
    <form @submit.prevent="submitForm" enctype="multipart/form-data">
        <template x-if="success">
            <div class="mb-4 p-3 rounded bg-green-100 text-green-800 font-semibold" x-text="success"></div>
        </template>
        <template x-if="error">
            <div class="mb-4 p-3 rounded bg-rose-100 text-rose-800 font-semibold" x-text="error"></div>
        </template>
       
        <!-- Tabs -->
        <div x-data="{ tab: 'basic' }" class="px-2 lg:px-4 pb-4">
            <div class="flex border-b border-slate-200 mb-4">
                <button type="button" :class="tab === 'basic' ? 'border-b-2 border-sky-500 text-sky-700' : 'text-slate-500'" class="px-4 py-2 font-semibold focus:outline-none" @click="tab = 'basic'">Basic Info</button>
                <button type="button" :class="tab === 'links' ? 'border-b-2 border-sky-500 text-sky-700' : 'text-slate-500'" class="px-4 py-2 font-semibold focus:outline-none" @click="tab = 'links'">Links</button>
                <button type="button" :class="tab === 'images' ? 'border-b-2 border-sky-500 text-sky-700' : 'text-slate-500'" class="px-4 py-2 font-semibold focus:outline-none" @click="tab = 'images'">Images</button>
                <button type="button" :class="tab === 'theme' ? 'border-b-2 border-sky-500 text-sky-700' : 'text-slate-500'" class="px-4 py-2 font-semibold focus:outline-none" @click="tab = 'theme'">Theme</button>
            </div>
        
            <!-- Basic Info Tab -->
            <div x-show="tab === 'basic'" class="space-y-6">
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div class="md:col-span-1">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Company Name<span class="text-rose-500">*</span></label>
                        <input type="text" x-model="name" name="name" placeholder="Enter company name" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-4 focus:ring-sky-100" required />
                    </div>
                    <div class="md:col-span-1">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Slug <span class="text-rose-500">*</span></label>
                        <div class="flex overflow-hidden rounded-xl border border-slate-300 bg-white focus-within:border-sky-500 focus-within:ring-4 focus-within:ring-sky-100">
                            <span class="inline-flex items-center border-r border-slate-200 bg-slate-50 px-4 text-sm text-slate-500">/companies/</span>
                            <input type="text" x-model="slug" name="slug" placeholder="company-slug" class="w-full px-4 py-3 text-sm text-slate-800 outline-none placeholder:text-slate-400" required />
                        </div>
                    </div>
                    <div class="md:col-span-1">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Category</label>
                        <input type="text" x-model="category" name="category" placeholder="Category" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-4 focus:ring-sky-100" />
                    </div>
                    <div class="md:col-span-1">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Status</label>
                        <select x-model="status" name="status" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="md:col-span-1">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Tagline</label>
                        <input type="text" x-model="tagline" name="tagline" placeholder="Tagline" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-4 focus:ring-sky-100" />
                    </div>
                    <div class="md:col-span-1">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">CEO Name</label>
                        <input type="text" x-model="ceo_name" name="ceo_name" placeholder="CEO Name" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-4 focus:ring-sky-100" />
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Short Description</label>
                    <textarea x-model="short_description" name="short_description" rows="3" placeholder="Write a short summary of the company..." class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-4 focus:ring-sky-100"></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Full Description</label>
                    <textarea x-model="full_description" name="full_description" rows="6" placeholder="Write a more complete company full description..." class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-4 focus:ring-sky-100"></textarea>
                </div>
            </div>

            <!-- Links Tab -->
            <div x-show="tab === 'links'" class="space-y-6">
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div class="md:col-span-1">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Website Link</label>
                        <input type="text" x-model="website_link" name="website_link" placeholder="https://company.com" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-4 focus:ring-sky-100" />
                    </div>
                    <div class="md:col-span-1">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Comp Plan Link</label>
                        <input type="text" x-model="comp_plan_link" name="comp_plan_link" placeholder="Compensation Plan Link" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-4 focus:ring-sky-100" />
                    </div>
                    <div class="md:col-span-1">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Signup Link</label>
                        <input type="text" x-model="signup_link" name="signup_link" placeholder="Signup Link" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-4 focus:ring-sky-100" />
                    </div>
                    <div class="md:col-span-1">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Backoffice Link</label>
                        <input type="text" x-model="backoffice_link" name="backoffice_link" placeholder="Backoffice Link" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-4 focus:ring-sky-100" />
                    </div>
                    <div class="md:col-span-1">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Intro Video Link</label>
                        <input type="text" x-model="intro_video_link" name="intro_video_link" placeholder="Intro Video Link" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-4 focus:ring-sky-100" />
                    </div>
                    <div class="md:col-span-1">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Webinar Link</label>
                        <input type="text" x-model="webinar_link" name="webinar_link" placeholder="Webinar Link" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-4 focus:ring-sky-100" />
                    </div>
                </div>
            </div>

            <!-- Images Tab -->
            <div x-show="tab === 'images'" class="space-y-6">
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div class="md:col-span-1">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Company Logo</label>
                        <input type="file" @change="handleFile($event, 'logo')" name="logo" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100" />
                        <template x-if="logoPreview || logoUrl">
                            <div class="mt-2"><img :src="logoPreview ? logoPreview : logoUrl" class="h-16 rounded" alt="Logo Preview"></div>
                        </template>
                    </div>
                    <div class="md:col-span-1">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Cover Image / Banner</label>
                        <input type="file" @change="handleFile($event, 'banner')" name="banner" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100" />
                        <template x-if="bannerPreview || bannerUrl">
                            <div class="mt-2"><img :src="bannerPreview ? bannerPreview : bannerUrl" class="h-16 rounded" alt="Banner Preview"></div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Theme Tab -->
            <div x-show="tab === 'theme'" class="space-y-6">
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div class="md:col-span-1">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Theme ID</label>
                        <input type="number" x-model="theme_id" name="theme_id" placeholder="Theme ID" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-4 focus:ring-sky-100" />
                    </div>
                    <div class="md:col-span-1">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Primary Color</label>
                        <input type="text" x-model="primary_color" name="primary_color" placeholder="#000000" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-4 focus:ring-sky-100" />
                    </div>
                    <div class="md:col-span-1">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Secondary Color</label>
                        <input type="text" x-model="secondary_color" name="secondary_color" placeholder="#FFFFFF" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-4 focus:ring-sky-100" />
                    </div>
                    <div class="md:col-span-1">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Highlight Color</label>
                        <input type="text" x-model="highlight_color" name="highlight_color" placeholder="#FFD700" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-4 focus:ring-sky-100" />
                    </div>
                    <div class="md:col-span-1">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Background Color</label>
                        <input type="text" x-model="background_color" name="background_color" placeholder="#F0F0F0" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-4 focus:ring-sky-100" />
                    </div>
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Style Meta (JSON)</label>
                        <textarea x-model="style_meta" name="style_meta" rows="2" placeholder='{"key":"value"}' class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-4 focus:ring-sky-100"></textarea>
                    </div>
                </div>
            </div>
       
            <div class="flex justify-end mt-6">
                <button type="submit" class="inline-flex items-center px-6 py-2 rounded-xl bg-teal-600 text-white font-bold shadow hover:bg-teal-700 transition">Save</button>
            </div>
        </div>
    </form>
</div>

