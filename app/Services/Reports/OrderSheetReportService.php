<?php

namespace App\Services\Reports;

use App\Models\CaseOrder;
use Mpdf\Mpdf;

class OrderSheetReportService
{
    public function generate(CaseOrder $order): string
    {
        $mpdf = new Mpdf([
            // 'default_font' => 'kalpurush',
            'mode' => 'utf-8',
        ]);

        $html = "
        <div style='text-align: center; margin-bottom: 20px;'>
            <h1>IN THE COURT OF DISTRICT JUDGE, DHAKA</h1>
            <h3>Order Sheet</h3>
            <h4>Case No: " . $order->courtCase->case_number . "</h4>
        </div>

        <div style='margin-bottom: 20px;'>
            <strong>Date:</strong> " . $order->order_date->format('d M, Y') . "<br>
            <strong>Order Type:</strong> " . $order->type . "<br>
            <strong>Presiding Judge:</strong> " . $order->judge->name . "
        </div>

        <div style='margin-top: 40px; padding: 20px; border: 1px solid #ddd;'>
            <p>" . nl2br($order->content) . "</p>
        </div>

        <div style='margin-top: 100px; text-align: right;'>
            <img src='' alt='Signature Placeholder' /><br>
            ____________________<br>
            " . $order->judge->name . "<br>
            District Judge
        </div>
        ";

        $mpdf->WriteHTML($html);

        return $mpdf->Output('', 'S');
    }
}
