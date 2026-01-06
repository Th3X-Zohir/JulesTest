<?php

namespace App\Http\Controllers;

use App\Models\CourtCase;
use App\Models\CaseOrder;
use Illuminate\Http\Request;
use App\Services\Reports\OrderSheetReportService;

class CaseOrderController extends Controller
{
    public function store(Request $request, CourtCase $case)
    {
        if (!auth()->user()->hasRole('Judge')) abort(403, 'Only judges can issue orders.');

        $validated = $request->validate([
            'content' => 'required|string',
            'type' => 'required|in:Interim,Final',
        ]);

        $case->orders()->create([
            'judge_id' => auth()->id(),
            'order_date' => now(),
            'content' => $validated['content'],
            'type' => $validated['type'],
        ]);

        return back()->with('success', 'Order issued successfully.');
    }

    public function download(CourtCase $case, CaseOrder $order, OrderSheetReportService $reportService)
    {
        // Add authorization check here if needed

        $pdfContent = $reportService->generate($order);

        return response()->streamDownload(
            fn () => print($pdfContent),
            "order-{$order->id}.pdf"
        );
    }
}
