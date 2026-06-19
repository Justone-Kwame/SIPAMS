<?php

namespace App\Livewire\Products;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;

class ProductList extends Component
{
    use WithPagination;

    public string $search       = '';
    public string $categoryFilter = '';
    public string $stockFilter  = '';   // all | low | out
    public string $sortBy       = 'name';
    public string $sortDir      = 'asc';

    public ?int $deleteId = null;
    public bool $confirmDelete = false;

    protected $queryString = ['search', 'categoryFilter', 'stockFilter'];

    public function updatingSearch(): void    { $this->resetPage(); }
    public function updatingCategoryFilter(): void { $this->resetPage(); }
    public function updatingStockFilter(): void    { $this->resetPage(); }

    public function sortBy(string $field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy  = $field;
            $this->sortDir = 'asc';
        }
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId      = $id;
        $this->confirmDelete = true;
    }

    public function delete(): void
    {
        if ($this->deleteId) {
            Product::findOrFail($this->deleteId)->delete();
            session()->flash('success', 'Product deleted.');
        }
        $this->confirmDelete = false;
        $this->deleteId      = null;
    }

    public function cancelDelete(): void
    {
        $this->confirmDelete = false;
        $this->deleteId      = null;
    }

    public function render()
    {
        $query = Product::with(['category', 'batches' => fn($q) => $q->where('status', 'active')])
            ->when($this->search, fn($q) =>
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('sku', 'like', "%{$this->search}%")
                  ->orWhere('barcode', 'like', "%{$this->search}%")
            )
            ->when($this->categoryFilter, fn($q) =>
                $q->where('category_id', $this->categoryFilter)
            )
            ->orderBy($this->sortBy, $this->sortDir);

        $products = $query->paginate(15);

        // Apply stock filter after paginator (use collection filter on current page items)
        // We do this via post-processing so stock status is correct
        $categories = Category::orderBy('name')->get();

        return view('livewire.products.product-list', compact('products', 'categories'))
            ->layout('layouts.app');
    }
}
