<?php

namespace App\Services\Reports;

use App\Models\CourtCase;
use Mpdf\Mpdf;

class ArjiReportService
{
    public function generate(CourtCase $case): string
    {
        // In a real app, this would use a Blade view (e.g., reports.arji)
        // For this scaffold, we construct simple HTML

        $mpdf = new Mpdf([
            // 'default_font' => 'kalpurush', // Disabled to prevent error in default setup
            'mode' => 'utf-8',
        ]);

        $html = "
        <div style='text-align: center; margin-bottom: 20px;'>
            <h1>IN THE COURT OF DISTRICT JUDGE, DHAKA</h1>
            <h3>Case No: " . ($case->case_number ?? 'DRAFT') . "</h3>
        </div>

        <div style='margin-bottom: 20px;'>
            <strong>Petitioner:</strong><br>
            " . $case->parties->where('party_type', 'plaintiff')->pluck('name')->join(', ') . "
        </div>

        <div style='margin-bottom: 20px; text-align: center;'>
            <strong>VERSUS</strong>
        </div>

        <div style='margin-bottom: 20px;'>
            <strong>Defendant:</strong><br>
            " . $case->parties->where('party_type', 'defendant')->pluck('name')->join(', ') . "
        </div>

        <div style='margin-top: 40px;'>
            <h4>Subject: " . ($case->title ?? 'Suit for Declaration') . "</h4>
            <p>" . nl2br($case->description) . "</p>
        </div>

        <div style='margin-top: 100px;'>
            ____________________<br>
            Signature
        </div>
        ";

        $mpdf->WriteHTML($html);

        return $mpdf->Output('', 'S'); // Return as string
    }
}
