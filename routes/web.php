<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberDashboardController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Admin\CompanyController as AdminCompanyController;
use App\Http\Controllers\Admin\DashboardContentController as AdminDashboardContentController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\WorkspaceController as AdminWorkspaceController;
use App\Http\Controllers\ProspectController;
use App\Http\Controllers\KanbanCommandCenterController;
use App\Http\Controllers\LeadPipelineController;

require __DIR__.'/member.php';

Route::redirect('admin/', '/admin/dashboard');

// Route::view('/', 'welcome');
Route::get('/', [LandingController::class, 'how'])->name('landing.how');

Route::view('privacy-policy', 'landing.privacy-policy')->name('privacy-policy');
Route::view('terms-of-service', 'landing.terms-of-service')->name('terms-of-service');
Route::view('cookie-policy', 'landing.cookie-policy')->name('cookie-policy');
Route::view('income-disclosure', 'landing.income-disclosure')->name('income-disclosure');

Route::get('member/dashboard', [MemberDashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('member.dashboard');

// Prospects (accessible to all authenticated users)
Route::middleware(['auth'])->group(function () {
    Route::get('prospects', [ProspectController::class, 'index'])->name('prospects.index');
    Route::get('prospects/funnel', [ProspectController::class, 'funnel'])->name('prospects.funnel');
    Route::get('prospects/import/template', [ProspectController::class, 'importTemplate'])->name('prospects.import-template');
    Route::get('prospects/lead-pipeline', [LeadPipelineController::class, 'index'])->name('lead-pipeline');
    Route::post('lead-pipeline/activity', [LeadPipelineController::class, 'storeActivity'])->name('lead-pipeline.activity');
    Route::get('prospects/kanban-command-center', [KanbanCommandCenterController::class, 'index'])->name('kanban-command-center');
});


// Company dashboards
use App\Http\Controllers\CompanyDashboardController;
Route::get('companies/{company}/dashboard', [CompanyDashboardController::class, 'show'])
    ->middleware(['auth', 'verified'])
    ->name('company.dashboard');

// Company ratings & reviews
use App\Http\Controllers\CompanyReviewController;
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('companies/{company}/reviews', [CompanyReviewController::class, 'store'])
        ->name('company.reviews.store');
    Route::delete('companies/{company}/reviews', [CompanyReviewController::class, 'destroy'])
        ->name('company.reviews.destroy');
});

// Default dashboard (fallback, can be removed if not needed)
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Admin routes for company and dashboard content management
Route::prefix('admin')->middleware(['auth', 'verified', 'role:admin|super-admin'])->name('admin.')->group(function () {
    Route::get('/', fn () => redirect()->route('admin.dashboard'))->name('home');
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('companies/create', fn () => view('admin.companies.onboarding'))->name('companies.create');
    Route::resource('companies', AdminCompanyController::class)->except(['create']);
    Route::post('companies/{company}/restore', [AdminCompanyController::class, 'restore'])->name('companies.restore');
    Route::post('companies/{company}/toggle-publish', [AdminCompanyController::class, 'togglePublish'])->name('companies.toggle-publish');

    Route::resource('companies.dashboard-contents', AdminDashboardContentController::class);
    Route::post('companies/{company}/dashboard-contents/{dashboard_content}/restore', [AdminDashboardContentController::class, 'restore'])->name('dashboard-contents.restore');

    Route::get('users', fn () => view('admin.users.index'))->name('users.index');
    Route::get('roles', fn () => view('admin.roles.index'))->name('roles.index');
    Route::get('sponsor-relationships', fn () => view('admin.sponsor-relationships.index'))->name('sponsor-relationships.index');
    Route::get('audit-logs', fn () => view('admin.audit-logs.index'))->name('audit-logs.index');
    Route::get('settings', [AdminWorkspaceController::class, 'settings'])->name('settings');

    Route::get('downline', [AdminWorkspaceController::class, 'downline'])->name('downline');
    Route::get('downline/placement-rules', [AdminWorkspaceController::class, 'placementRules'])->name('downline.placement-rules');
    Route::get('downline/spillover', [AdminWorkspaceController::class, 'spillover'])->name('downline.spillover');

    Route::get('prospects', fn () => view('admin.prospects.index'))->name('prospects.index');
    Route::get('pipeline', fn () => view('admin.prospects.pipeline'))->name('pipeline');
    Route::get('follow-ups', fn () => view('admin.prospects.follow-ups'))->name('follow-ups');
    Route::get('activity-logs', fn () => view('admin.activity-logs.index'))->name('activity-logs');
    Route::get('notifications', fn () => view('admin.notifications.index'))->name('notifications');
    Route::get('reports', fn () => view('admin.reports.index'))->name('reports');
    Route::get('ai-insights', fn () => view('admin.insights.index'))->name('ai-insights');
});


require __DIR__.'/auth.php';
