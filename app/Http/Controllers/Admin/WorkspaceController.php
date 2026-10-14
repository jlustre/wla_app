<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\View\View;

class WorkspaceController extends Controller
{
    public function settings(): View
    {
        return view('admin.workspace.settings', [
            'appName' => config('app.name'),
            'timezone' => config('app.timezone'),
            'environment' => config('app.env'),
            'companyCount' => Company::query()->count(),
            'memberCount' => User::query()->count(),
        ]);
    }

    public function downline(): View
    {
        return view('admin.workspace.downline', [
            'title' => 'Downline Management',
            'description' => 'Search and manage any member\'s sponsorship position, explore the live tree from any branch, and reassign sponsors when needed.',
        ]);
    }

    public function placementRules(): View
    {
        return view('admin.workspace.panel', [
            'title' => 'Placement Rules',
            'description' => 'How WLA places new members under a qualified sponsor without breaking company-specific downline rules.',
            'cards' => [
                ['title' => 'Sponsor first', 'body' => 'New members attach to the inviting sponsor when that sponsor is active and eligible for the company being joined.'],
                ['title' => 'Company eligibility', 'body' => 'Placement is evaluated per affiliated company so a member can sit correctly in more than one compensation tree.'],
                ['title' => 'Admin override', 'body' => 'Reassignments are recorded in audit logs and should keep a clear original sponsor, new sponsor, and reason.'],
            ],
        ]);
    }

    public function spillover(): View
    {
        return view('admin.workspace.panel', [
            'title' => 'Spillover Logic',
            'description' => 'How overflow is handled when a sponsor’s available placement slots are already filled.',
            'cards' => [
                ['title' => 'Qualified overflow', 'body' => 'Spillover only moves to the next eligible downline seat after the original sponsor relationship has been recorded.'],
                ['title' => 'No silent skips', 'body' => 'Skipped uplines and company-specific exceptions must remain visible in genealogy and audit history.'],
                ['title' => 'Future company rules', 'body' => 'Each affiliated company can later supply its own spillover depth without changing the core WLA sponsor link.'],
            ],
        ]);
    }

    protected function panel(string $title, string $description, array $columns, $rows, $paginator = null): View
    {
        return view('admin.workspace.panel', [
            'title' => $title,
            'description' => $description,
            'columns' => $columns,
            'rows' => $rows,
            'paginator' => $paginator,
        ]);
    }

}
