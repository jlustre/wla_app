<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class ProspectController extends Controller
{
    /**
     * The "My Prospects" workspace page. All CRUD, filtering, pagination
     * and CSV import happen client-side through the Livewire component
     * embedded in resources/views/prospects/index.blade.php.
     */
    public function index()
    {
        return view('prospects.index');
    }

    /**
     * The "Prospect Funnel" page: a kanban board of every prospect by its
     * current stage plus a conversion funnel chart, both driven by the
     * Livewire component embedded in resources/views/prospects/funnel.blade.php.
     */
    public function funnel()
    {
        return view('prospects.funnel');
    }

    /**
     * A downloadable sample CSV so members know the expected import format.
     */
    public function importTemplate(): Response
    {
        $csv = "first_name,last_name,email,phone,source,hotness,notes\n"
            ."Jane,Doe,jane.doe@example.com,+1 555-010-0100,Referral,Hot,Met at the Austin meetup\n"
            ."John,Smith,,+1 555-010-0199,Facebook,Warm,Follow up after webinar\n";

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="prospects-import-template.csv"',
        ]);
    }
}
