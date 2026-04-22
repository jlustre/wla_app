<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::withTrashed()->orderBy('name')->get();
        return view('admin.companies.index', compact('companies'));
    }

    public function create()
    {
        return view('admin.companies.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:companies',
            'logo' => 'nullable',
            'category' => 'nullable',
            'website_link' => 'nullable',
            'short_description' => 'nullable',
            'full_description' => 'nullable',
            'status' => 'nullable',
            'primary_color' => 'nullable',
            'secondary_color' => 'nullable',
            'highlight_color' => 'nullable',
            'background_color' => 'nullable',
            'style_meta' => 'nullable',
        ]);
        Company::create($data);
        return redirect()->route('admin.companies.index')->with('success', 'Company created.');
    }

    public function edit(Company $company)
    {
        return view('admin.companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $data = $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:companies,slug,' . $company->id,
            'logo' => 'nullable',
            'category' => 'nullable',
            'website_link' => 'nullable',
            'short_description' => 'nullable',
            'full_description' => 'nullable',
            'status' => 'nullable',
            'primary_color' => 'nullable',
            'secondary_color' => 'nullable',
            'highlight_color' => 'nullable',
            'background_color' => 'nullable',
            'style_meta' => 'nullable',
        ]);
        $company->update($data);
        return redirect()->route('admin.companies.index')->with('success', 'Company updated.');
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('admin.companies.index')->with('success', 'Company deleted.');
    }

    public function restore($id)
    {
        $company = Company::withTrashed()->findOrFail($id);
        $company->restore();
        return redirect()->route('admin.companies.index')->with('success', 'Company restored.');
    }
    /**
     * Toggle the publish status of a company.
     */
    public function togglePublish(Company $company)
    {
        $company->is_publish = !$company->is_publish;
        $company->save();
        return back()->with('success', $company->is_publish ? 'Company published.' : 'Company unpublished.');
    }
}
