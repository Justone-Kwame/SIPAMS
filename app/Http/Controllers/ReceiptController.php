<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptController extends Controller
{
    /**
     * 80mm thermal receipt PDF
     */
    public function download(Sale $sale)
    {
        $sale->load(['items.product', 'user', 'customer']);

        $pdf = Pdf::loadView('receipts.thermal', compact('sale'))
                  ->setPaper([0, 0, 226.77, 800], 'portrait'); // 80mm width

        return $pdf->stream('receipt-' . $sale->receipt_no . '.pdf');
    }

    /**
     * A4 invoice PDF
     */
    public function invoice(Sale $sale)
    {
        $sale->load(['items.product', 'user', 'customer']);

        $pdf = Pdf::loadView('receipts.invoice', compact('sale'))
                  ->setPaper('a4', 'portrait');

        return $pdf->stream('invoice-' . $sale->receipt_no . '.pdf');
    }
}
