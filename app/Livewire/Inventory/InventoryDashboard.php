<?php

namespace App\Livewire\Inventory;

use Livewire\Component;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\StockMovement;
use Carbon\Carbon;

class InventoryDashboard extends Component
{
    public string $search = '';
    public ?string $filterStatus = null;

    public function render()
    {
        $totalStockValue = ProductBatch::active()->sum(
            \DB::raw('quantity_remaining * cost_price')
        );

        // Get all products with batches and calculate counts in PHP
        $productsWithBatches = Product::with(['batches' => fn($q) => $q->active()])->get();
        $lowStockCount = 0;
        $outOfStockCount = 0;

        foreach ($productsWithBatches as $product) {
            $totalQty = $product->batches->sum('quantity_remaining');
            if ($totalQty == 0) {
                $outOfStockCount++;
            } elseif ($totalQty <= $product->reorder_level) {
                $lowStockCount++;
            }
        }

        $expiringCount = ProductBatch::with('product')
            ->active()
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays(30))
            ->where('expiry_date', '>=', now())
            ->count();

        $recentMovements = StockMovement::with(['product', 'user'])
            ->latest()
            ->take(10)
            ->get();

        $productsQuery = Product::with(['category', 'batches' => fn($q) => $q->active()])
            ->when($this->search, function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('sku', 'like', "%{$this->search}%")
                  ->orWhere('barcode', 'like', "%{$this->search}%");
            });

        $products = $productsQuery->orderBy('name')->paginate(15);

        return view('livewire.inventory.inventory-dashboard', [
            'totalStockValue' => $totalStockValue,
            'lowStockCount' => $lowStockCount,
            'outOfStockCount' => $outOfStockCount,
            'expiringCount' => $expiringCount,
            'recentMovements' => $recentMovements,
            'products' => $products
        ])->layout('layouts.app');
    }
}
