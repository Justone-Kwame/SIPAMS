<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Expense;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
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
                $sale->user->name ?? '—'
            ]);
        }

        fclose($handle);

        return response()->streamDownload(function () use ($handle) {
            // Already written
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    // Export inventory report as CSV
    public function exportInventoryCsv()
    {
        $products = Product::with(['category', 'batches' => function($q) {
            $q->where('status', 'active');
        }])->get();

        $filename = "inventory-report-" . date('Y-m-d') . ".csv";
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
                $inventoryValue
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
