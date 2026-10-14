<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));
        $status = $request->get('status', '');
        $category = $request->get('category', '');

        $categories = Company::query()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $companies = Company::withTrashed()
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%");
                });
            })
            ->when($status === 'active', fn ($query) => $query->where('status', '1')->whereNull('deleted_at'))
            ->when($status === 'inactive', fn ($query) => $query->where('status', '0')->whereNull('deleted_at'))
            ->when($status === 'deleted', fn ($query) => $query->whereNotNull('deleted_at'))
            ->when($category !== '', fn ($query) => $query->where('category', $category))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.companies.index', compact('companies', 'categories', 'search', 'status', 'category'));
    }

    /**
     * The full set of company fields managed by the onboarding wizard
     * (app/Livewire/Admin/Company/Onboarding.php), kept here too so these
     * REST endpoints stay in sync for any non-Livewire caller (API, scripts).
     */
    protected function fieldRules(?Company $company = null): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|alpha_dash|max:255|unique:companies,slug,' . ($company?->id ?? 'NULL'),
            'logo' => 'nullable|string',
            'banner' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'status' => 'nullable|in:0,1',
            'tagline' => 'nullable|string|max:255',
            'ceo_name' => 'nullable|string|max:255',
            'theme_id' => 'nullable|integer',
            'website_link' => 'nullable|url|max:255',
            'signup_link' => 'nullable|url|max:255',
            'signup_link_type' => ['nullable', 'in:' . implode(',', array_keys(Company::SIGNUP_LINK_TYPES))],
            'signup_sponsor_field' => ['nullable', 'in:' . implode(',', array_keys(Company::SPONSOR_FIELDS))],
            'comp_plan_link' => 'nullable|url|max:255',
            'backoffice_link' => 'nullable|url|max:255',
            'intro_video_link' => 'nullable|url|max:255',
            'webinar_link' => 'nullable|url|max:255',
            'phone' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'full_description' => 'nullable|string',
            'primary_color' => 'nullable|string|max:20',
            'secondary_color' => 'nullable|string|max:20',
            'highlight_color' => 'nullable|string|max:20',
            'background_color' => 'nullable|string|max:20',
            'style_meta' => 'nullable|array',
        ];
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->fieldRules());
        Company::create($data);
        return redirect()->route('admin.companies.index')->with('success', 'Company created.');
    }

    public function edit(Company $company)
    {
        return view('admin.companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $data = $request->validate($this->fieldRules($company));
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
