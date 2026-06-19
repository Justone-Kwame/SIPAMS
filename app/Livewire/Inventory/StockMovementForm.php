<?php

namespace App\Livewire\Inventory;

use Livewire\Component;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Services\InventoryService;
use Illuminate\Support\Str;

class StockMovementForm extends Component
{
    public string $type = 'in'; // 'in' or 'out'
    public ?int $productId = null;
    public ?int $batchId = null;
    public int $quantity = 0;
    public float $costPrice = 0;
    public ?string $expiryDate = null;
    public ?string $notes = '';

    public function mount(string $type = 'in')
    {
        $this->type = $type;
    }

    public function updatedProductId()
    {
        if ($this->productId) {
            $product = Product::find($this->productId);
            if ($product) {
                $this->costPrice = $product->cost_price;
            }
        }
        $this->batchId = null;
    }

    public function save(InventoryService $inventoryService)
    {
        $this->validate([
            'type' => 'required|in:in,out',
            'productId' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'costPrice' => 'required_if:type,in|numeric|min:0',
            'batchId' => 'required_if:type,out|exists:product_batches,id',
            'expiryDate' => 'nullable|date',
            'notes' => 'nullable|string|max:500',
        ]);

        $product = Product::findOrFail($this->productId);

        if ($this->type === 'in') {
            $inventoryService->receiveStock(
                $product,
                $this->quantity,
                $this->costPrice,
                $this->expiryDate ?: null,
                'BCH-' . strtoupper(Str::random(6)),
                null,
                auth()->id()
            );
            session()->flash('success', 'Stock added successfully!');
        } else {
            $inventoryService->issueStock(
                $product,
                $this->quantity,
                'manual',
                null,
                auth()->id()
            );
            session()->flash('success', 'Stock removed successfully!');
        }

        $this->redirect(route('inventory.index'), navigate: true);
    }

    public function render()
    {
        $products = Product::orderBy('name')->get();
        $batches = $this->productId ? ProductBatch::where('product_id', $this->productId)
            ->active()
            ->where('quantity_remaining', '>', 0)
            ->get() : collect();

        return view('livewire.inventory.stock-movement-form', [
            'products' => $products,
            'batches' => $batches
        ])->layout('layouts.app');
    }
}
