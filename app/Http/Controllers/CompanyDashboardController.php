<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\InviteCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyDashboardController extends Controller
{
    public function show($company)
    {
        $company = Company::with('dashboardContents')
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->findOrFail($company);

        $user = Auth::user();

        $isJoined = $user
            ? $company->memberAffiliations()
                ->where('user_id', $user->id)
                ->where('status', 'active')
                ->exists()
            : false;

        $myReview = $user
            ? $company->reviews()->where('user_id', $user->id)->first()
            : null;

        $reviews = $company->reviews()
            ->with('user:id,username')
            ->when($myReview, fn ($query) => $query->where('user_id', '!=', $user->id))
            ->latest()
            ->paginate(10, ['*'], 'reviews_page')
            ->withQueryString();

        $ratingBreakdown = $company->ratingBreakdown();
        $categoryAverages = $company->categoryAverages();

        // Admin-managed sections (products, compensation, resources, …) keyed by their
        // `section` value so each tab can render whatever content has been configured
        // for this company, falling back to generic copy when nothing has been added yet.
        $contentSections = $company->dashboardContents
            ->sortBy('order')
            ->groupBy('section');

        $inviteCode = $user ? InviteCode::currentFor($user) : null;
        $inviteLink = $inviteCode
            ? url('/register?ref='.$inviteCode->code.'&company='.$company->slug)
            : url('/register?company='.$company->slug);

        return view('company-dashboard', [
            'company' => $company,
            'isJoined' => $isJoined,
            'contentSections' => $contentSections,
            'inviteLink' => $inviteLink,
            'inviteCode' => $inviteCode,
            'myReview' => $myReview,
            'reviews' => $reviews,
            'ratingBreakdown' => $ratingBreakdown,
            'categoryAverages' => $categoryAverages,
        ]);
    }
}
