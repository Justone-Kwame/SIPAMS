<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use App\Models\Product;
use App\Models\ProductBatch;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InventoryReport extends Component
{
    public $activeTab = 'current'; // current, low, out, expiry

    public function render()
    {
        $currentStock = Product::with(['category', 'batches' => function ($q) {
            $q->where('status', 'active');
        }])->get();

        // Calculate low/out of stock by summing remaining quantity for each product
        $productsWithStock = Product::with(['category', 'batches' => function ($q) {
            $q->where('status', 'active');
        }])->get();

        $lowStock = collect();
        $outOfStock = collect();

        foreach ($productsWithStock as $product) {
            $totalQty = $product->batches->sum('quantity_remaining');
            if ($totalQty == 0) {
                $outOfStock->push($product);
            } elseif ($totalQty <= $product->reorder_level) {
                $lowStock->push($product);
            }
        }

        $expiringProducts = ProductBatch::with(['product'])
            ->where('status', 'active')
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', Carbon::now()->addDays(30))
            ->where('expiry_date', '>=', Carbon::now())
            ->orderBy('expiry_date', 'asc')
            ->get();

        $expiredProducts = ProductBatch::with(['product'])
            ->where('status', 'active')
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<', Carbon::now())
            ->orderBy('expiry_date', 'desc')
            ->get();

        return view('livewire.reports.inventory-report', compact(
            'currentStock',
            'lowStock',
            'outOfStock',
            'expiringProducts',
            'expiredProducts'
        ))->layout('layouts.app');
    }
}
