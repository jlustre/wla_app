<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DashboardContent;
use App\Models\Company;
use Illuminate\Http\Request;

class DashboardContentController extends Controller
{
    public function index(Company $company)
    {
        $contents = $company->dashboardContents()->withTrashed()->orderBy('order')->get();
        return view('admin.dashboard_contents.index', compact('company', 'contents'));
    }

    public function create(Company $company)
    {
        return view('admin.dashboard_contents.create', compact('company'));
    }

    public function store(Request $request, Company $company)
    {
        $data = $request->validate([
            'section' => 'required',
            'title' => 'nullable',
            'content' => 'nullable',
            'image' => 'nullable',
            'meta' => 'nullable',
            'order' => 'nullable|integer',
            'element_class' => 'nullable',
            'element_type' => 'nullable',
        ]);
        $data['company_id'] = $company->id;
        DashboardContent::create($data);
        return redirect()->route('admin.companies.dashboard-contents.index', $company)->with('success', 'Content created.');
    }

    public function edit(Company $company, DashboardContent $dashboardContent)
    {
        return view('admin.dashboard_contents.edit', compact('company', 'dashboardContent'));
    }

    public function update(Request $request, Company $company, DashboardContent $dashboardContent)
    {
        $data = $request->validate([
            'section' => 'required',
            'title' => 'nullable',
            'content' => 'nullable',
            'image' => 'nullable',
            'meta' => 'nullable',
            'order' => 'nullable|integer',
            'element_class' => 'nullable',
            'element_type' => 'nullable',
        ]);
        $dashboardContent->update($data);
        return redirect()->route('admin.companies.dashboard-contents.index', $company)->with('success', 'Content updated.');
    }

    public function destroy(Company $company, DashboardContent $dashboardContent)
    {
        $dashboardContent->delete();
        return redirect()->route('admin.companies.dashboard-contents.index', $company)->with('success', 'Content deleted.');
    }

    public function restore($companyId, $id)
    {
        $dashboardContent = DashboardContent::withTrashed()->findOrFail($id);
        $dashboardContent->restore();
        return redirect()->route('admin.companies.dashboard-contents.index', $companyId)->with('success', 'Content restored.');
    }
}
