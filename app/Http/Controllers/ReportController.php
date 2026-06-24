<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PurchaseOrder;
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

        $backUrl = $request->routeIs('accounting.*')
            ? route('accounting.profit-loss')
            : route('reports.profit-loss');

        return view('reports.profit-loss-print', [
            'pl' => $pl,
            'backUrl' => $backUrl,
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

    // Build the purchases query shared by the list exports
    protected function purchasesQuery(Request $request)
    {
        $search = $request->search;
        $supplierFilter = $request->supplierFilter;
        $statusFilter = $request->statusFilter;

        return PurchaseOrder::with(['supplier'])
            ->when($search, fn ($q) => $q->where(fn ($sub) => $sub->where('po_number', 'like', "%{$search}%")
                ->orWhereHas('supplier', fn ($sq) => $sq->where('name', 'like', "%{$search}%"))
            )
            )
            ->when($supplierFilter, fn ($q) => $q->where('supplier_id', $supplierFilter))
            ->when($statusFilter, fn ($q) => $q->where('status', $statusFilter))
            ->orderBy('order_date', 'desc')
            ->get();
    }

    protected function paymentStatusLabel(PurchaseOrder $po): string
    {
        $due = (float) $po->total_amount - (float) $po->paid_amount;

        if ((float) $po->total_amount > 0 && $due <= 0) {
            return 'Paid';
        }

        if ((float) $po->paid_amount <= 0) {
            return 'Unpaid';
        }

        return 'Partial';
    }

    // Export purchases list as CSV
    public function exportPurchasesCsv(Request $request)
    {
        $purchases = $this->purchasesQuery($request);

        $filename = 'purchases-'.date('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($purchases) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Date', 'Reference', 'Supplier', 'Status', 'Total', 'Paid', 'Due', 'Payment Status']);

            foreach ($purchases as $po) {
                $due = max((float) $po->total_amount - (float) $po->paid_amount, 0);

                fputcsv($handle, [
                    Carbon::parse($po->order_date)->format('Y-m-d'),
                    $po->po_number,
                    $po->supplier->name ?? '—',
                    ucfirst($po->status),
                    number_format($po->total_amount, 2),
                    number_format($po->paid_amount, 2),
                    number_format($due, 2),
                    $this->paymentStatusLabel($po),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    // Export purchases list as PDF
    public function exportPurchasesPdf(Request $request)
    {
        $purchases = $this->purchasesQuery($request);

        $pdf = Pdf::loadView('reports.purchases-pdf', [
            'purchases' => $purchases,
            'paymentStatus' => fn (PurchaseOrder $po) => $this->paymentStatusLabel($po),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('purchases-'.date('Y-m-d').'.pdf');
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
