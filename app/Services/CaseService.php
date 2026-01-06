<?php

namespace App\Services;

use App\Models\CourtCase;
use App\Models\CaseStatus;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class CaseService
{
    public function createCase(User $user, array $data): CourtCase
    {
        return DB::transaction(function () use ($user, $data) {
            // Determine initial status (e.g., Draft or Pending Approval)
            $status = CaseStatus::where('slug', 'draft')->firstOrFail();

            $case = CourtCase::create([
                'filed_by' => $user->id,
                'case_status_id' => $status->id,
                'title' => $data['title'] ?? null,
                'description' => $data['description'] ?? null,
                'year' => date('Y'),
                'filing_date' => now(),
            ]);

            // Add Parties
            if (isset($data['parties'])) {
                foreach ($data['parties'] as $partyData) {
                    $case->parties()->create($partyData);
                }
            }

            return $case;
        });
    }

    public function generateCaseNumber(CourtCase $case): string
    {
        // Simple logic for example: CASE-ID/YEAR
        return "CASE-{$case->id}/{$case->year}";
    }
}
