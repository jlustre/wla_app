<?php

namespace App\Livewire\Profile;

use Livewire\Component;

class MlmCompanyCard extends Component
{
    public array $company = [];

    public function mount(array $company): void
    {
        $this->company = $company;
    }

    public function badgeClasses(): string
    {
        return match ($this->company['status']) {
            'Joined' => 'bg-emerald-100 text-emerald-700',
            default => 'bg-slate-100 text-slate-600',
        };
    }

    public function logoClasses(): string
    {
        return match ($this->company['badge_color']) {
            'emerald' => 'bg-emerald-500',
            'cyan' => 'bg-cyan-500',
            'violet' => 'bg-violet-500',
            'amber' => 'bg-amber-500',
            'rose' => 'bg-rose-500',
            default => 'bg-slate-500',
        };
    }

    public function buttonClasses(): string
    {
        return match ($this->company['action_style']) {
            'gradient' => 'bg-gradient-to-r from-emerald-500 to-cyan-500 text-white shadow-md hover:shadow-lg',
            'dark' => 'bg-slate-900 text-white hover:bg-slate-800',
            'outline' => 'border border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100',
            'light' => 'bg-white text-emerald-700 ring-1 ring-emerald-200 hover:bg-emerald-600 hover:text-white',
            default => 'bg-white text-slate-700 border border-slate-200',
        };
    }

    public function render()
    {
        return view('livewire.profile.mlm-company-card');
    }
}