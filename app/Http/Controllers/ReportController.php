<?php

namespace App\Http\Controllers;

use App\Models\CourtCase;
use App\Services\Reports\ArjiReportService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    protected ArjiReportService $arjiService;

    public function __construct(ArjiReportService $arjiService)
    {
        $this->arjiService = $arjiService;
    }

    public function downloadArji(CourtCase $case)
    {
        // Check authorization (e.g., policy) here

        $pdfContent = $this->arjiService->generate($case);

        return response()->streamDownload(
            fn () => print($pdfContent),
            "arji-{$case->id}.pdf"
        );
    }
}
