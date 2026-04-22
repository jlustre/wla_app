<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyDashboardController extends Controller
{
    public function show($company)
    {
        $company = Company::with('dashboardContents')->findOrFail($company);
        return view('company-dashboard', compact('company'));
    }
}
