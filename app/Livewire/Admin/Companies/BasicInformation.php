<?php

namespace App\Livewire\Admin\Companies;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Company;
use Illuminate\Support\Facades\Log;

class BasicInformation extends Component
{
    use WithFileUploads;

    public $companyId = null;
    public $company = null;
    public $name = '';
    public $slug = '';
    public $short_description = '';
    public $full_description = '';
    public $logoFile = null;
    public $bannerFile = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:companies,slug',
        'short_description' => 'nullable|string|max:500',
        'full_description' => 'nullable|string',
        'logoFile' => 'nullable|image|max:2048',
        'bannerFile' => 'nullable|image|max:4096',
    ];

    public function mount($companyId = null)
    {
        $this->companyId = $companyId;
        if ($companyId) {
            $this->company = Company::findOrFail($companyId);
            $this->name = $this->company->name ?? '';
            $this->slug = $this->company->slug ?? '';
            $this->short_description = $this->company->short_description ?? '';
            $this->full_description = $this->company->full_description ?? '';
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        $validated = $this->validate();

        if ($this->logoFile) {
            $validated['logo'] = $this->logoFile->store('logos', 'public');
        } elseif ($this->company && $this->company->logo) {
            $validated['logo'] = $this->company->logo;
        }

        if ($this->bannerFile) {
            $validated['banner'] = $this->bannerFile->store('banners', 'public');
        } elseif ($this->company && $this->company->banner) {
            $validated['banner'] = $this->company->banner;
        }

        if ($this->company) {
            $this->company->update($validated);
            session()->flash('success', 'Company updated successfully!');
        } else {
            Company::create($validated);
            session()->flash('success', 'Company created successfully!');
        }
    }

    public function render()
    {
      
    return view('livewire.admin.companies.basic-information', [
            'logoFile' => $this->logoFile,
            'bannerFile' => $this->bannerFile,
            'company' => $this->company,
        ]);
    }
}
