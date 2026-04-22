<?php

namespace App\View\Components;

use App\Models\Company;
use Illuminate\View\Component;

class CompanySidebarMenu extends Component
{
    public $companies;

    public function __construct()
    {
        $this->companies = Company::orderBy('name')->get();
    }

    public function render()
    {
        return view('components.company-sidebar-menu');
    }
}
