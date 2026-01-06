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

    public function show(CourtCase $case): Response
    {
        $case->load(['parties', 'caseStatus', 'caseLawyers.lawyer', 'assignedJudge', 'orders.judge']);

        // Fetch Judges if user is Serestadar (for assignment)
        $judges = auth()->user()->hasRole('Serestadar')
            ? \App\Models\User::role('Judge')->get(['id', 'name'])
            : [];

        return Inertia::render('Cases/Show', [
            'case' => $case,
            'judges' => $judges,
            'auth' => [
                'user' => auth()->user(),
                'roles' => auth()->user()->getRoleNames(),
            ]
        ]);
    }

    public function assignJudge(Request $request, CourtCase $case)
    {
        if (!auth()->user()->hasRole('Serestadar')) abort(403);

        $request->validate(['judge_id' => 'required|exists:users,id']);

        $case->update(['assigned_judge_id' => $request->judge_id]);

        return back()->with('success', 'Judge assigned successfully.');
    }

    public function inviteLawyer(Request $request, CourtCase $case)
    {
        $request->validate(['email' => 'required|email']);

        $lawyer = \App\Models\User::where('email', $request->email)->firstOrFail();

        // Ensure user is actually a lawyer
        if (!$lawyer->hasRole('Lawyer')) abort(400, 'User is not a lawyer');

        $this->caseService->inviteLawyer($case, $lawyer, $request->user());

        return back()->with('success', 'Lawyer invited successfully.');
    }

    public function acceptLawyer(CourtCase $case)
    {
        $this->caseService->acceptLawyerInvitation($case, auth()->user());
        return back()->with('success', 'Invitation accepted.');
    }

    public function submit(CourtCase $case)
    {
        $this->caseService->submitCase($case);
        return back()->with('success', 'Case submitted for approval.');
    }

    public function storeHearing(Request $request, CourtCase $case, \App\Services\HearingService $hearingService)
    {
        if (!auth()->user()->isJudge() && !auth()->user()->isSerestadar()) abort(403);

        $validated = $request->validate([
            'hearing_date' => 'required|date|after:today',
            'purpose' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        $hearingService->scheduleHearing($case, $validated);

        return back()->with('success', 'Hearing scheduled.');
    }

    public function approve(CourtCase $case, \App\Services\CaseApprovalService $approvalService)
    {
        $approvalService->approveCase(auth()->user(), $case);
        return back()->with('success', 'Case approved and number assigned.');
    }

    public function reject(Request $request, CourtCase $case, \App\Services\CaseApprovalService $approvalService)
    {
        $request->validate(['note' => 'required|string']);
        $approvalService->returnCase(auth()->user(), $case, $request->note);
        return back()->with('success', 'Case returned for correction.');
    }
}
