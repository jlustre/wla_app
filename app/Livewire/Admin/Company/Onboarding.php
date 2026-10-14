<?php

namespace App\Livewire\Admin\Company;

use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Onboarding extends Component
{
    use WithFileUploads;

    public int $step = 1;

    public bool $slugTouched = false;

    // When set, the wizard edits this existing company instead of creating a new one.
    public ?int $companyId = null;

    public ?Company $company = null;

    // Step 1 - Company Profile
    public string $name = '';
    public string $slug = '';
    public string $category = '';
    public string $status = '1';
    public string $tagline = '';
    public string $ceo_name = '';
    public ?int $theme_id = null;

    // Step 2 - Description & Branding
    public string $short_description = '';
    public string $full_description = '';
    public $logo;
    public $banner;
    public ?string $existingLogo = null;
    public ?string $existingBanner = null;
    public string $primary_color = '';
    public string $secondary_color = '';
    public string $highlight_color = '';
    public string $background_color = '';
    public string $style_meta_json = '';

    // Step 3 - Links & Contact
    public string $website_link = '';
    public string $signup_link = '';
    public string $signup_link_type = Company::SIGNUP_LINK_TYPE_DIRECT;
    public ?string $signup_sponsor_field = null;
    public string $comp_plan_link = '';
    public string $backoffice_link = '';
    public string $intro_video_link = '';
    public string $webinar_link = '';
    public string $phone = '';
    public string $location = '';

    public function mount(?int $companyId = null): void
    {
        if (! $companyId) {
            return;
        }

        $this->companyId = $companyId;
        $this->company = Company::findOrFail($companyId);
        $this->slugTouched = true;

        $this->name = $this->company->name ?? '';
        $this->slug = $this->company->slug ?? '';
        $this->category = $this->company->category ?? '';
        $this->status = (string) ($this->company->status ?? '1');
        $this->tagline = $this->company->tagline ?? '';
        $this->ceo_name = $this->company->ceo_name ?? '';
        $this->theme_id = $this->company->theme_id;

        $this->short_description = $this->company->short_description ?? '';
        $this->full_description = $this->company->full_description ?? '';
        $this->existingLogo = $this->company->logo;
        $this->existingBanner = $this->company->banner;
        $this->primary_color = $this->company->primary_color ?? '';
        $this->secondary_color = $this->company->secondary_color ?? '';
        $this->highlight_color = $this->company->highlight_color ?? '';
        $this->background_color = $this->company->background_color ?? '';
        $this->style_meta_json = $this->company->style_meta
            ? json_encode($this->company->style_meta, JSON_PRETTY_PRINT)
            : '';

        $this->website_link = $this->company->website_link ?? '';
        $this->signup_link = $this->company->signup_link ?? '';
        $this->signup_link_type = $this->company->signup_link_type ?? Company::SIGNUP_LINK_TYPE_DIRECT;
        $this->signup_sponsor_field = $this->company->signup_sponsor_field;
        $this->comp_plan_link = $this->company->comp_plan_link ?? '';
        $this->backoffice_link = $this->company->backoffice_link ?? '';
        $this->intro_video_link = $this->company->intro_video_link ?? '';
        $this->webinar_link = $this->company->webinar_link ?? '';
        $this->phone = $this->company->phone ?? '';
        $this->location = $this->company->location ?? '';
    }

    public function updatedName(string $value): void
    {
        if (! $this->slugTouched) {
            $this->slug = Str::slug($value);
        }
    }

    public function updatedSlug(): void
    {
        $this->slugTouched = true;
    }

    public function isEditing(): bool
    {
        return (bool) $this->companyId;
    }

    protected function stepFields(): array
    {
        return [
            1 => ['name', 'slug', 'category', 'status', 'tagline', 'ceo_name', 'theme_id'],
            2 => ['short_description', 'full_description', 'logo', 'banner', 'primary_color', 'secondary_color', 'highlight_color', 'background_color', 'style_meta_json'],
            3 => ['website_link', 'signup_link', 'signup_link_type', 'signup_sponsor_field', 'comp_plan_link', 'backoffice_link', 'intro_video_link', 'webinar_link', 'phone', 'location'],
        ];
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => ['required', 'alpha_dash', 'max:255', Rule::unique('companies', 'slug')->ignore($this->companyId)],
            'category' => 'required|string|max:255',
            'status' => 'required|in:0,1',
            'tagline' => 'nullable|string|max:255',
            'ceo_name' => 'nullable|string|max:255',
            'theme_id' => 'nullable|integer',

            'short_description' => 'required|string|max:500',
            'full_description' => 'nullable|string',
            'logo' => $this->isEditing() ? 'nullable|image|max:2048' : 'required|image|max:2048',
            'banner' => 'nullable|image|max:4096',
            'primary_color' => 'nullable|string|max:20',
            'secondary_color' => 'nullable|string|max:20',
            'highlight_color' => 'nullable|string|max:20',
            'background_color' => 'nullable|string|max:20',
            'style_meta_json' => 'nullable|json',

            'website_link' => 'required|url|max:255',
            'signup_link' => 'required|url|max:255',
            'signup_link_type' => ['required', Rule::in(array_keys(Company::SIGNUP_LINK_TYPES))],
            'signup_sponsor_field' => [
                Rule::requiredIf(fn () => $this->signup_link_type === Company::SIGNUP_LINK_TYPE_SPONSOR_ENTRY),
                'nullable',
                Rule::in(array_keys(Company::SPONSOR_FIELDS)),
            ],
            'comp_plan_link' => 'required|url|max:255',
            'backoffice_link' => 'nullable|url|max:255',
            'intro_video_link' => 'nullable|url|max:255',
            'webinar_link' => 'nullable|url|max:255',
            'phone' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:255',
        ];
    }

    public function themeOptions()
    {
        return DB::table('themes')->orderBy('name')->get(['id', 'name']);
    }

    public function nextStep(): void
    {
        $this->validateStep($this->step);
        $this->step = min($this->step + 1, 4);
    }

    public function previousStep(): void
    {
        $this->step = max($this->step - 1, 1);
    }

    public function goToStep(int $step): void
    {
        if ($step > $this->step) {
            for ($current = $this->step; $current < $step; $current++) {
                $this->validateStep($current);
            }
        }

        $this->step = max(1, min($step, 4));
    }

    protected function validateStep(int $step): void
    {
        if (! array_key_exists($step, $this->stepFields())) {
            return;
        }

        $fields = $this->stepFields()[$step];
        $rules = collect($this->rules())->only($fields)->all();

        $this->validate($rules);
    }

    /**
     * Every pertinent piece of company information, grouped by step, with
     * completion state — this powers the live checklist panel.
     */
    public function checklist(): array
    {
        return [
            ['key' => 'name', 'label' => 'Company name', 'step' => 1, 'required' => true, 'done' => filled($this->name)],
            ['key' => 'slug', 'label' => 'URL slug', 'step' => 1, 'required' => true, 'done' => filled($this->slug)],
            ['key' => 'category', 'label' => 'Category', 'step' => 1, 'required' => true, 'done' => filled($this->category)],
            ['key' => 'status', 'label' => 'Status', 'step' => 1, 'required' => true, 'done' => filled($this->status)],
            ['key' => 'tagline', 'label' => 'Tagline', 'step' => 1, 'required' => false, 'done' => filled($this->tagline)],
            ['key' => 'ceo_name', 'label' => 'CEO / contact name', 'step' => 1, 'required' => false, 'done' => filled($this->ceo_name)],
            ['key' => 'theme_id', 'label' => 'Dashboard theme', 'step' => 1, 'required' => false, 'done' => filled($this->theme_id)],

            ['key' => 'short_description', 'label' => 'Short description', 'step' => 2, 'required' => true, 'done' => filled($this->short_description)],
            ['key' => 'full_description', 'label' => 'Full description', 'step' => 2, 'required' => false, 'done' => filled($this->full_description)],
            ['key' => 'logo', 'label' => 'Logo', 'step' => 2, 'required' => true, 'done' => (bool) $this->logo || filled($this->existingLogo)],
            ['key' => 'banner', 'label' => 'Banner image', 'step' => 2, 'required' => false, 'done' => (bool) $this->banner || filled($this->existingBanner)],
            ['key' => 'colors', 'label' => 'Brand colors', 'step' => 2, 'required' => false, 'done' => filled($this->primary_color) || filled($this->secondary_color)],
            ['key' => 'style_meta_json', 'label' => 'Advanced styling / highlights', 'step' => 2, 'required' => false, 'done' => filled($this->style_meta_json)],

            ['key' => 'website_link', 'label' => 'Website link', 'step' => 3, 'required' => true, 'done' => filled($this->website_link)],
            ['key' => 'signup_link', 'label' => 'Sign-up link', 'step' => 3, 'required' => true, 'done' => filled($this->signup_link)],
            ['key' => 'signup_link_type', 'label' => 'Sponsor tracking method', 'step' => 3, 'required' => false, 'done' => filled($this->signup_link_type)],
            ['key' => 'comp_plan_link', 'label' => 'Compensation plan link', 'step' => 3, 'required' => true, 'done' => filled($this->comp_plan_link)],
            ['key' => 'backoffice_link', 'label' => 'Back-office link', 'step' => 3, 'required' => false, 'done' => filled($this->backoffice_link)],
            ['key' => 'intro_video_link', 'label' => 'Intro video link', 'step' => 3, 'required' => false, 'done' => filled($this->intro_video_link)],
            ['key' => 'webinar_link', 'label' => 'Webinar link', 'step' => 3, 'required' => false, 'done' => filled($this->webinar_link)],
            ['key' => 'phone', 'label' => 'Phone number', 'step' => 3, 'required' => false, 'done' => filled($this->phone)],
            ['key' => 'location', 'label' => 'Location', 'step' => 3, 'required' => false, 'done' => filled($this->location)],
        ];
    }

    public function checklistSummary(): array
    {
        $items = $this->checklist();
        $required = array_values(array_filter($items, fn ($item) => $item['required']));
        $requiredDone = array_filter($required, fn ($item) => $item['done']);
        $allDone = array_filter($items, fn ($item) => $item['done']);

        return [
            'total' => count($items),
            'done' => count($allDone),
            'requiredTotal' => count($required),
            'requiredDone' => count($requiredDone),
            'percent' => count($items) > 0 ? (int) round(count($allDone) / count($items) * 100) : 0,
            'readyToSubmit' => count($required) === count($requiredDone),
        ];
    }

    public function save()
    {
        $this->validate();

        $summary = $this->checklistSummary();

        if (! $summary['readyToSubmit']) {
            $this->addError('submit', 'Please complete all required checklist items before saving the company.');
            $this->step = 4;

            return;
        }

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'category' => $this->category,
            'status' => $this->status,
            'tagline' => $this->tagline,
            'ceo_name' => $this->ceo_name,
            'theme_id' => $this->theme_id,
            'short_description' => $this->short_description,
            'full_description' => $this->full_description,
            'primary_color' => $this->primary_color,
            'secondary_color' => $this->secondary_color,
            'highlight_color' => $this->highlight_color,
            'background_color' => $this->background_color,
            'style_meta' => $this->style_meta_json !== '' ? json_decode($this->style_meta_json, true) : null,
            'website_link' => $this->website_link,
            'signup_link' => $this->signup_link,
            'signup_link_type' => $this->signup_link_type,
            'signup_sponsor_field' => $this->signup_link_type === Company::SIGNUP_LINK_TYPE_SPONSOR_ENTRY
                ? ($this->signup_sponsor_field ?? Company::SPONSOR_FIELD_USERNAME)
                : null,
            'comp_plan_link' => $this->comp_plan_link,
            'backoffice_link' => $this->backoffice_link,
            'intro_video_link' => $this->intro_video_link,
            'webinar_link' => $this->webinar_link,
            'phone' => $this->phone,
            'location' => $this->location,
        ];

        if ($this->logo) {
            $data['logo'] = $this->logo->store('logos', 'public');
        }

        if ($this->banner) {
            $data['banner'] = $this->banner->store('banners', 'public');
        }

        if ($this->isEditing()) {
            $this->company->update($data);
            session()->flash('success', 'Company "'.$this->company->name.'" was updated successfully.');

            return redirect()->route('admin.companies.edit', $this->company);
        }

        $company = Company::create($data);

        session()->flash('success', 'Company "'.$company->name.'" was created successfully.');

        return redirect()->route('admin.companies.edit', $company);
    }

    public function render()
    {
        return view('livewire.admin.company.onboarding', [
            'checklist' => $this->checklist(),
            'summary' => $this->checklistSummary(),
            'themes' => $this->themeOptions(),
        ]);
    }
}
