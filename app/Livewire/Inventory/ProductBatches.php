<?php

namespace App\Livewire\Inventory;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ProductBatch;
use App\Models\Product;

class ProductBatches extends Component
{
    use WithPagination;

    public string $search = '';
    public ?string $status = null;
    public ?int $productId = null;
    public string $sortBy = 'created_at';
    public string $sortDir = 'desc';

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDir = 'asc';
        }
    }

    public function render()
    {
        $batches = ProductBatch::with(['product'])
            ->when($this->search, function ($q) {
                $q->whereHas('product', function ($subQ) {
                    $subQ->where('name', 'like', "%{$this->search}%")
                        ->orWhere('sku', 'like', "%{$this->search}%");
                })->orWhere('batch_number', 'like', "%{$this->search}%");
            })
            ->when($this->status, function ($q) {
                $q->where('status', $this->status);
            })
            ->when($this->productId, function ($q) {
                $q->where('product_id', $this->productId);
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate(20);

        $products = Product::orderBy('name')->get();

        return view('livewire.inventory.product-batches', [
            'batches' => $batches,
            'products' => $products
        ])->layout('layouts.app');
    }
}
