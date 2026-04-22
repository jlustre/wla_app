<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberDashboardController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Admin\CompanyController as AdminCompanyController;
use App\Http\Controllers\Admin\DashboardContentController as AdminDashboardContentController;
use App\Http\Controllers\ProspectController;
use App\Http\Controllers\KanbanCommandCenterController;
use App\Http\Controllers\LeadPipelineController;


// Route::view('/', 'welcome');
Route::get('/', [LandingController::class, 'how'])->name('landing.how');

Route::get('member/dashboard', [MemberDashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'role:member'])
    ->name('member.dashboard');

// Prospects (accessible to all authenticated users)
Route::middleware(['auth'])->group(function () {
    Route::get('prospects', [ProspectController::class, 'index'])->name('prospects.index');
    Route::get('prospects/lead-pipeline', [LeadPipelineController::class, 'index'])->name('lead-pipeline');
    Route::post('lead-pipeline/activity', [LeadPipelineController::class, 'storeActivity'])->name('lead-pipeline.activity');
    Route::get('prospects/create', [ProspectController::class, 'create'])->name('prospects.create');
    Route::post('prospects', [ProspectController::class, 'store'])->name('prospects.store');
    Route::get('prospects/{prospect}/edit', [ProspectController::class, 'edit'])->name('prospects.edit');
    Route::delete('prospects/{prospect}', [ProspectController::class, 'destroy'])->name('prospects.destroy');
    Route::get('prospects/kanban-command-center', [KanbanCommandCenterController::class, 'index'])->name('kanban-command-center');
});


// Company dashboards
use App\Http\Controllers\CompanyDashboardController;
Route::get('companies/{company}/dashboard', [CompanyDashboardController::class, 'show'])
    ->middleware(['auth', 'verified'])
    ->name('company.dashboard');

// Default dashboard (fallback, can be removed if not needed)
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Admin routes for company and dashboard content management
Route::prefix('admin')->middleware(['auth', 'verified', 'role:admin|super-admin'])->name('admin.')->group(function () {
    Route::resource('companies', AdminCompanyController::class);
    Route::post('companies/{company}/restore', [AdminCompanyController::class, 'restore'])->name('companies.restore');
    Route::post('companies/{company}/toggle-publish', [AdminCompanyController::class, 'togglePublish'])->name('companies.toggle-publish');

    Route::resource('companies.dashboard-contents', AdminDashboardContentController::class);
    Route::post('companies/{company}/dashboard-contents/{dashboard_content}/restore', [AdminDashboardContentController::class, 'restore'])->name('dashboard-contents.restore');
});

Route::view('admin/dashboard', 'admin-dashboard')
    ->middleware(['auth', 'verified', 'role:admin|super-admin'])
    ->name('admin.dashboard');

Route::get('how', [LandingController::class, 'how'])->name('landing.how');

// Livewire test route
Route::get('/test-livewire', function () {
    return view('livewire.test-livewire');
});

require __DIR__.'/auth.php';
