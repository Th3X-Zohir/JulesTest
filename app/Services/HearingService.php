<?php

namespace App\Services;

use App\Models\CourtCase;
use App\Models\CaseHearing;
use Exception;

class HearingService
{
    /**
     * Schedule a new hearing.
     */
    public function scheduleHearing(CourtCase $case, array $data): CaseHearing
    {
        // Validation logic could go here (e.g., check if date is a holiday)

        return $case->hearings()->create([
            'hearing_date' => $data['hearing_date'],
            'hearing_time' => $data['hearing_time'] ?? null,
            'purpose' => $data['purpose'],
            'notes' => $data['notes'] ?? null,
            'status' => 'scheduled'
        ]);
    }
}
