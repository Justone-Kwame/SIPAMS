<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Expense;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\StockMovement;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Overview extends Component
{
    public string $selectedYear;
    public array  $years = [];

    public function mount(): void
    {
        $this->selectedYear = (string) now()->year;
        for ($y = now()->year; $y >= now()->year - 4; $y--) {
            $this->years[] = $y;
        }
    }

    public function render()
    {
        $year    = (int) $this->selectedYear;
        $today   = now();

        // ── Date range helpers ────────────────────────────────────────────────
        $dayStart   = $today->copy()->startOfDay();
        $dayEnd     = $today->copy()->endOfDay();
        $weekStart  = $today->copy()->startOfWeek();
        $weekEnd    = $today->copy()->endOfWeek();
        $monStart   = $today->copy()->startOfMonth();
        $monEnd     = $today->copy()->endOfMonth();
        $yearStart  = Carbon::create($year)->startOfYear();
        $yearEnd    = Carbon::create($year)->endOfYear();

        // ── INVENTORY SUMMARY ─────────────────────────────────────────────────
        $totalProducts    = Product::count();
        $totalStockQty    = (int) ProductBatch::where('status', 'active')->sum('quantity_remaining');
        $inventoryValue   = (float) ProductBatch::where('status', 'active')
                                ->selectRaw('SUM(quantity_remaining * cost_price) as val')
                                ->value('val') ?? 0;

        // ── SALES SUMMARY ─────────────────────────────────────────────────────
        $dailySales   = (float) Sale::completed()->whereBetween('date', [$dayStart,  $dayEnd])->sum('net_amount');
        $weeklySales  = (float) Sale::completed()->whereBetween('date', [$weekStart, $weekEnd])->sum('net_amount');
        $monthlySales = (float) Sale::completed()->whereBetween('date', [$monStart,  $monEnd])->sum('net_amount');
        
        // ── PURCHASE SUMMARY ──────────────────────────────────────────────────
        $dailyPurchases   = (float) \App\Models\PurchaseOrder::where('status', 'delivered')->whereBetween('order_date', [$dayStart,  $dayEnd])->sum('total_amount');
        $weeklyPurchases  = (float) \App\Models\PurchaseOrder::where('status', 'delivered')->whereBetween('order_date', [$weekStart, $weekEnd])->sum('total_amount');
        $monthlyPurchases = (float) \App\Models\PurchaseOrder::where('status', 'delivered')->whereBetween('order_date', [$monStart,  $monEnd])->sum('total_amount');
        
        // ── SALES RETURNS ─────────────────────────────────────────────────────
        $dailySalesReturns   = 0; // Placeholder - adjust based on your return model
        $weeklySalesReturns  = 0;
        $monthlySalesReturns = 0;
        
        // ── PURCHASE RETURNS ──────────────────────────────────────────────────
        $dailyPurchaseReturns   = 0; // Placeholder - adjust based on your return model
        $weeklyPurchaseReturns  = 0;
        $monthlyPurchaseReturns = 0;

        // ── PROFIT SUMMARY ────────────────────────────────────────────────────
        $dailyGross   = (float) SaleItem::inPeriod($dayStart,  $dayEnd)->sum('profit');
        $weeklyGross  = (float) SaleItem::inPeriod($weekStart, $weekEnd)->sum('profit');
        $monthlyGross = (float) SaleItem::inPeriod($monStart,  $monEnd)->sum('profit');

        $dailyExpenses   = (float) Expense::whereBetween('date', [$dayStart,  $dayEnd])->sum('amount');
        $weeklyExpenses  = (float) Expense::whereBetween('date', [$weekStart, $weekEnd])->sum('amount');
        $monthlyExpenses = (float) Expense::whereBetween('date', [$monStart,  $monEnd])->sum('amount');

        $dailyProfit   = $dailyGross   - $dailyExpenses;
        $weeklyProfit  = $weeklyGross  - $weeklyExpenses;
        $monthlyProfit = $monthlyGross - $monthlyExpenses;

        // ── EXPENSE SUMMARY ───────────────────────────────────────────────────
        // (dailyExpenses & monthlyExpenses already computed above)

        // ── ALERTS ───────────────────────────────────────────────────────────
        $allProducts = Product::with(['batches' => fn($q) => $q->where('status', 'active')])->get();

        $lowStockProducts = $allProducts
            ->filter(fn($p) => $p->batches->sum('quantity_remaining') > 0
                             && $p->batches->sum('quantity_remaining') <= $p->reorder_level)
            ->take(5)
            ->values();

        $outOfStockCount = $allProducts
            ->filter(fn($p) => $p->batches->sum('quantity_remaining') == 0)
            ->count();

        $expiredProducts = ProductBatch::with('product:id,name')
            ->where('status', 'active')
            ->whereNotNull('expiry_date')
            ->whereDate('expiry_date', '<', $today->toDateString())
            ->orderBy('expiry_date')
            ->limit(5)
            ->get();

        $expiringSoon = ProductBatch::with('product:id,name')
            ->where('status', 'active')
            ->whereNotNull('expiry_date')
            ->whereBetween('expiry_date', [$today->toDateString(), $today->copy()->addDays(30)->toDateString()])
            ->orderBy('expiry_date')
            ->limit(5)
            ->get();

        // ── MONTHLY TRENDS (12 months) ────────────────────────────────────────
        $monthlySalesData   = [];
        $monthlyProfitData  = [];
        $monthlyStockIn     = [];
        $monthlyStockOut    = [];
        $monthLabels        = [];

        for ($m = 1; $m <= 12; $m++) {
            $ms = Carbon::create($year, $m)->startOfMonth();
            $me = Carbon::create($year, $m)->endOfMonth();

            $r  = (float) Sale::completed()->whereBetween('date', [$ms, $me])->sum('net_amount');
            $gp = (float) SaleItem::inPeriod($ms, $me)->sum('profit');
            $ex = (float) Expense::whereBetween('date', [$ms, $me])->sum('amount');
            $si = (int)   StockMovement::where('type', 'in')->whereBetween('created_at', [$ms, $me])->sum('quantity');
            $so = (int)   StockMovement::where('type', 'out')->whereBetween('created_at', [$ms, $me])->sum('quantity');

            $monthlySalesData[]  = $r;
            $monthlyProfitData[] = $gp - $ex;
            $monthlyStockIn[]    = $si;
            $monthlyStockOut[]   = abs($so);
            $monthLabels[]       = $ms->format('M');
        }

        // ── FAST MOVING PRODUCTS (current month) ──────────────────────────────
        $fastMoving = SaleItem::select('product_id',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(subtotal)  as total_revenue')
            )
            ->whereHas('sale', fn($q) => $q->completed()->whereBetween('date', [$monStart, $monEnd]))
            ->with('product:id,name,sku')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(8)
            ->get();

        // ── TOP SELLING PRODUCTS WITH DETAILS ─────────────────────────────────
        $topSellingProducts = SaleItem::select('product_id',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(subtotal)  as grand_total')
            )
            ->whereHas('sale', fn($q) => $q->completed()->whereBetween('date', [$monStart, $monEnd]))
            ->with('product:id,name')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // ── TOP 5 CUSTOMERS ───────────────────────────────────────────────────
        $topCustomers = Sale::completed()
            ->whereBetween('date', [$monStart, $monEnd])
            ->select('customer_id', DB::raw('COUNT(*) as order_count'), DB::raw('SUM(net_amount) as total_spent'))
            ->groupBy('customer_id')
            ->with('customer:id,name')
            ->orderByDesc('total_spent')
            ->limit(5)
            ->get();

        // ── RECENT SALES ──────────────────────────────────────────────────────
        $recentSales = Sale::completed()
            ->with('customer:id,name')
            ->orderByDesc('date')
            ->limit(5)
            ->get();

        return view('livewire.dashboard.overview', compact(
            'year',
            // inventory
            'totalProducts', 'totalStockQty', 'inventoryValue',
            // sales
            'dailySales', 'weeklySales', 'monthlySales',
            // purchases
            'dailyPurchases', 'weeklyPurchases', 'monthlyPurchases',
            // returns
            'dailySalesReturns', 'weeklySalesReturns', 'monthlySalesReturns',
            'dailyPurchaseReturns', 'weeklyPurchaseReturns', 'monthlyPurchaseReturns',
            // profit & expenses
            'dailyGross', 'dailyProfit', 'weeklyProfit', 'monthlyProfit',
            'dailyExpenses', 'monthlyExpenses',
            // alerts
            'lowStockProducts', 'outOfStockCount', 'expiredProducts', 'expiringSoon',
            // charts
            'monthlySalesData', 'monthlyProfitData',
            'monthlyStockIn', 'monthlyStockOut', 'monthLabels',
            'fastMoving',
            // new sections
            'topSellingProducts', 'topCustomers', 'recentSales',
        ))->layout('layouts.app');
    }
}
