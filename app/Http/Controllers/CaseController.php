<?php

namespace App\Http\Controllers;

use App\Models\CourtCase;
use App\Services\CaseService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CaseController extends Controller
{
    protected CaseService $caseService;

    public function __construct(CaseService $caseService)
    {
        $this->caseService = $caseService;
    }

    public function index(): Response
    {
        $cases = CourtCase::with(['caseStatus', 'parties'])
            ->where('filed_by', auth()->id())
            ->latest()
            ->get();

        return Inertia::render('Cases/Index', [
            'cases' => $cases
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Cases/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'required|string',
            'parties' => 'array',
            'parties.*.name' => 'required|string',
            'parties.*.party_type' => 'required|in:plaintiff,defendant',
            'parties.*.phone' => 'nullable|string',
        ]);

        $this->caseService->createCase($request->user(), $validated);

        return redirect()->route('cases.index')
            ->with('success', 'Case draft created successfully.');
    }
}
