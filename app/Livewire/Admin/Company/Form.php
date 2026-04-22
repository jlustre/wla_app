<?php

namespace App\Livewire\Admin\Company;

use Livewire\Component;
use App\Models\Company;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;

class Form extends Component
{
    use WithFileUploads;

    public $companyId;
    public $name = '';
    public $slug = '';
    public $short_description = '';
    public $full_description = '';
    public $logo;
    public $banner;
    public $company;

    protected function rules()
    {
        $uniqueSlug = 'unique:companies,slug';
        if ($this->companyId) {
            $uniqueSlug .= ',' . $this->companyId;
        }
        return [
            'name' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', $uniqueSlug],
            'short_description' => 'nullable|string|max:500',
            'full_description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'banner' => 'nullable|image|max:4096',
        ];
    }

    public function mount($companyId = null)
    {
        $this->companyId = $companyId;
        if ($companyId) {
            $this->company = Company::findOrFail($companyId);
            $this->name = $this->company->name ?? '';
            $this->slug = $this->company->slug ?? '';
            $this->short_description = $this->company->short_description ?? '';
            $this->full_description = $this->company->full_description ?? '';
            $this->logo = $this->company->logo ?? '';
            $this->banner = $this->company->banner ?? '';
        }
    }

    public function save()
    {
        try {
            Log::info('Livewire save() called', [
                'companyId' => $this->companyId,
                'name' => $this->name,
                'slug' => $this->slug,
            ]);
            $validated = $this->validate();

            // Handle file uploads
            if ($this->logo && is_object($this->logo)) {
                $validated['logo'] = $this->logo->store('logos', 'public');
            } elseif ($this->company && $this->company->logo) {
                $validated['logo'] = $this->company->logo;
            }

            if ($this->banner && is_object($this->banner)) {
                $validated['banner'] = $this->banner->store('banners', 'public');
            } elseif ($this->company && $this->company->banner) {
                $validated['banner'] = $this->company->banner;
            }

            if ($this->companyId) {
                $this->company->update($validated);
                session()->flash('success', 'Company updated successfully!');
            } else {
                $company = Company::create($validated);
                $this->companyId = $company->id;
                session()->flash('success', 'Company created successfully!');
            }
        } catch (\Throwable $e) {
            Log::error('Livewire save() error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.company.form');
    }
}
