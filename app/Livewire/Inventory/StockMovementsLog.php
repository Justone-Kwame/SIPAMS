<?php

namespace App\Livewire\Inventory;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\StockMovement;
use App\Models\Product;

class StockMovementsLog extends Component
{
    use WithPagination;

    public string $search = '';
    public ?string $type = null;
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
        $movements = StockMovement::with(['product', 'batch', 'user'])
            ->when($this->search, function ($q) {
                $q->whereHas('product', function ($subQ) {
                    $subQ->where('name', 'like', "%{$this->search}%")
                        ->orWhere('sku', 'like', "%{$this->search}%");
                });
            })
            ->when($this->type, function ($q) {
                $q->where('type', $this->type);
            })
            ->when($this->productId, function ($q) {
                $q->where('product_id', $this->productId);
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate(20);

        $products = Product::orderBy('name')->get();

        return view('livewire.inventory.stock-movements-log', [
            'movements' => $movements,
            'products' => $products
        ])->layout('layouts.app');
    }
}
