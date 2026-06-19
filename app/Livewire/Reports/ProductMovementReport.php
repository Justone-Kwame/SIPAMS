<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use App\Models\Product;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductMovementReport extends Component
{
    public $activeTab = 'fast'; // fast, slow
    public $period = '30'; // days

    public function render()
    {
        $startDate = Carbon::now()->subDays((int)$this->period);
        $endDate = Carbon::now();

        $productSales = SaleItem::select(
            'product_id',
            DB::raw('SUM(quantity) as total_sold'),
            DB::raw('SUM(subtotal) as total_revenue')
        )
            ->whereHas('sale', fn($q) => $q->whereBetween('date', [$startDate, $endDate])->where('status', 'completed'))
            ->groupBy('product_id')
            ->orderBy('total_sold', 'desc')
            ->get();

        $products = Product::with('category')->get()->keyBy('id');

        foreach ($productSales as $sale) {
            $sale->product = $products->get($sale->product_id);
        }

        $fastMoving = $productSales->filter(fn($s) => $s->total_sold >= 10)->take(20);
        $slowMoving = $productSales->filter(fn($s) => $s->total_sold < 10)->take(20);

        return view('livewire.reports.product-movement-report', compact(
            'fastMoving',
            'slowMoving'
        ))->layout('layouts.app');
    }
}
