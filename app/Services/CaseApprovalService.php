<?php

namespace App\Services;

use App\Models\CourtCase;
use App\Models\CaseStatus;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;

class CaseApprovalService
{
    protected CaseService $caseService;

    public function __construct(CaseService $caseService)
    {
        $this->caseService = $caseService;
    }

    /**
     * Approve a pending case.
     */
    public function approveCase(User $approver, CourtCase $case): CourtCase
    {
        if ($case->caseStatus->slug !== 'pending-approval') {
            throw new Exception("Case is not pending approval.");
        }

        if (!$approver->hasRole('Serestadar') && !$approver->hasRole('Superadmin')) {
             throw new Exception("Unauthorized to approve cases.");
        }

        return DB::transaction(function () use ($case) {
            $activeStatus = CaseStatus::where('slug', 'active')->firstOrFail();
            $caseNumber = $this->caseService->generateCaseNumber($case);

            $case->update([
                'case_status_id' => $activeStatus->id,
                'case_number' => $caseNumber,
            ]);

            return $case;
        });
    }

    /**
     * Reject/Return a case for correction.
     */
    public function returnCase(User $approver, CourtCase $case, string $note): CourtCase
    {
        if (!$approver->hasRole('Serestadar') && !$approver->hasRole('Superadmin')) {
            throw new Exception("Unauthorized.");
        }

        // Return to draft for edits
        $draftStatus = CaseStatus::where('slug', 'draft')->firstOrFail();

        $case->update([
            'case_status_id' => $draftStatus->id,
            // In a real app, we would log the rejection note in an audit log or separate table
        ]);

        return $case;
    }
}
