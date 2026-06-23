<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Services\AccountingService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // Profit and Loss print preview
    public function profitLossPrint(Request $request, AccountingService $accounting)
    {
        $startDate = $request->start
            ? Carbon::parse($request->start)->startOfDay()
            : Carbon::now()->startOfMonth();
        $endDate = $request->end
            ? Carbon::parse($request->end)->endOfDay()
            : Carbon::now()->endOfMonth();

        $pl = $accounting->getProfitLoss($startDate, $endDate);

        return view('reports.profit-loss-print', [
            'pl' => $pl,
            'backUrl' => route('reports.profit-loss'),
        ]);
    }

    // Export sales report as CSV
    public function exportSalesCsv(Request $request)
    {
        $startDate = $request->start_date ?? Carbon::now()->startOfMonth();
        $endDate = $request->end_date ?? Carbon::now()->endOfMonth();

        $sales = Sale::with(['items.product', 'user'])
            ->whereBetween('date', [$startDate, $endDate])
            ->where('status', 'completed')
            ->orderBy('date', 'desc')
            ->get();

        $filename = "sales-report-{$startDate}-to-{$endDate}.csv";
        $handle = fopen('php://output', 'w');

        fputcsv($handle, ['Receipt No', 'Date', 'Items Count', 'Total Amount', 'Profit', 'Cashier']);

        foreach ($sales as $sale) {
            fputcsv($handle, [
                $sale->receipt_no,
                $sale->date,
                $sale->items->count(),
                $sale->net_amount,
                $sale->items->sum('profit'),
                $sale->user->name ?? '—',
            ]);
        }

        fclose($handle);

        return response()->streamDownload(function () {
            // Already written
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    // Export inventory report as CSV
    public function exportInventoryCsv()
    {
        $products = Product::with(['category', 'batches' => function ($q) {
            $q->where('status', 'active');
        }])->get();

        $filename = 'inventory-report-'.date('Y-m-d').'.csv';
        $handle = fopen('php://output', 'w');

        fputcsv($handle, ['Product Name', 'SKU', 'Category', 'Stock Qty', 'Cost Price', 'Inventory Value']);

        foreach ($products as $product) {
            $stockQty = $product->batches->sum('quantity_remaining');
            $inventoryValue = $stockQty * $product->cost_price;

            fputcsv($handle, [
                $product->name,
                $product->sku,
                $product->category->name ?? '—',
                $stockQty,
                $product->cost_price,
                $inventoryValue,
            ]);
        }

        fclose($handle);

        return response()->streamDownload(function () {
            // Already written
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    // Export sales report as PDF
    public function exportSalesPdf(Request $request)
    {
        $startDate = $request->start_date ?? Carbon::now()->startOfMonth();
        $endDate = $request->end_date ?? Carbon::now()->endOfMonth();

        $sales = Sale::with(['items.product', 'user'])
            ->whereBetween('date', [$startDate, $endDate])
            ->where('status', 'completed')
            ->orderBy('date', 'desc')
            ->get();

        $pdf = Pdf::loadView('reports.sales-pdf', compact('sales', 'startDate', 'endDate'));

        return $pdf->download("sales-report-{$startDate}-to-{$endDate}.pdf");
    }
}
