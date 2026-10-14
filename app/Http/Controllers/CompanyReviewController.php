<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyReview;
use Illuminate\Http\Request;

class CompanyReviewController extends Controller
{
    public function store(Request $request, Company $company)
    {
        $rules = ['review' => ['nullable', 'string', 'max:2000']];
        foreach (CompanyReview::CATEGORIES as $column => $label) {
            $rules[$column] = ['required', 'integer', 'min:1', 'max:5'];
        }

        $validated = $request->validate($rules, [], collect(CompanyReview::CATEGORIES)->mapWithKeys(
            fn ($label, $column) => [$column => strtolower($label)]
        )->all());

        $company->reviews()->updateOrCreate(
            ['user_id' => $request->user()->id],
            [
                ...array_intersect_key($validated, CompanyReview::CATEGORIES),
                'review' => $validated['review'] ?? null,
            ]
        );

        return back()->with('status', 'review-saved')->withFragment('reviews');
    }

    public function destroy(Request $request, Company $company)
    {
        $company->reviews()->where('user_id', $request->user()->id)->delete();

        return back()->with('status', 'review-deleted')->withFragment('reviews');
    }
}
