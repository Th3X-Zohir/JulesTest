<?php

namespace App\Http\Controllers;

use App\Models\CaseHearing;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CauseListController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));

        $hearings = CaseHearing::with(['courtCase', 'courtCase.caseStatus'])
            ->whereDate('hearing_date', $date)
            ->orderBy('hearing_date')
            ->get();

        return Inertia::render('CauseList', [
            'hearings' => $hearings,
            'date' => $date,
        ]);
    }
}
