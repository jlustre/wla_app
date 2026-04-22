<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KanbanCommandCenterController extends Controller
{
    public function index()
    {
        return view('prospects.kanban-command-center');
    }
}