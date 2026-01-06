<?php

namespace App\Services;

use App\Models\CourtCase;
use App\Models\CaseStatus;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;

class CaseService
{
    /**
     * Create a new case draft.
     */
    public function createCase(User $user, array $data): CourtCase
    {
        return DB::transaction(function () use ($user, $data) {
            $status = CaseStatus::where('slug', 'draft')->firstOrFail();

            $case = CourtCase::create([
                'filed_by' => $user->id,
                'case_status_id' => $status->id,
                'title' => $data['title'] ?? null,
                'description' => $data['description'] ?? null,
                'year' => date('Y'),
                'filing_date' => now(),
            ]);

            if (isset($data['parties'])) {
                foreach ($data['parties'] as $partyData) {
                    $case->parties()->create($partyData);
                }
            }

            return $case;
        });
    }

    /**
     * Submit a case for approval.
     */
    public function submitCase(CourtCase $case): CourtCase
    {
        if ($case->caseStatus->slug !== 'draft') {
            throw new Exception("Only draft cases can be submitted.");
        }

        $pendingStatus = CaseStatus::where('slug', 'pending-approval')->firstOrFail();

        $case->update([
            'case_status_id' => $pendingStatus->id,
            'filing_date' => now(), // Update filing date to submission time
        ]);

        return $case;
    }

    /**
     * Generate a unique case number.
     * Format: CASE-{Year}-{ID} (Simple implementation)
     */
    public function generateCaseNumber(CourtCase $case): string
    {
        return sprintf("CIVIL-%s-%06d", $case->year, $case->id);
    }

    /**
     * Invite a lawyer to a case.
     */
    public function inviteLawyer(CourtCase $case, User $lawyer, User $requester): void
    {
        // Check if already assigned
        if ($case->caseLawyers()->where('lawyer_id', $lawyer->id)->exists()) {
            throw new Exception("Lawyer is already assigned or invited.");
        }

        $case->caseLawyers()->create([
            'lawyer_id' => $lawyer->id,
            'assigned_by' => $requester->id,
            'status' => 'pending',
        ]);
    }

    /**
     * Accept a lawyer invitation.
     */
    public function acceptLawyerInvitation(CourtCase $case, User $lawyer): void
    {
        $assignment = $case->caseLawyers()
            ->where('lawyer_id', $lawyer->id)
            ->where('status', 'pending')
            ->firstOrFail();

        $assignment->update(['status' => 'accepted']);
    }
}
