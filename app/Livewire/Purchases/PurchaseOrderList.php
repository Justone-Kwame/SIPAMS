<?php

namespace App\Livewire\Purchases;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PurchaseOrder;
use App\Models\Supplier;

class PurchaseOrderList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $supplierFilter = '';
    public string $statusFilter = '';
    public string $sortBy = 'order_date';
    public string $sortDir = 'desc';

    public ?int $deleteId = null;
    public bool $confirmDelete = false;

    protected $queryString = ['search', 'supplierFilter', 'statusFilter'];

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingSupplierFilter(): void { $this->resetPage(); }
    public function updatingStatusFilter(): void { $this->resetPage(); }

    public function sortBy(string $field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDir = 'asc';
        }
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
        $this->confirmDelete = true;
    }

    public function delete(): void
    {
        if ($this->deleteId) {
            PurchaseOrder::findOrFail($this->deleteId)->delete();
            session()->flash('success', 'Purchase Order deleted.');
        }
        $this->confirmDelete = false;
        $this->deleteId = null;
    }

    public function cancelDelete(): void
    {
        $this->confirmDelete = false;
        $this->deleteId = null;
    }

    public function render()
    {
        $query = PurchaseOrder::with(['supplier', 'items'])
            ->when($this->search, fn($q) =>
                $q->where('po_number', 'like', "%{$this->search}%")
                  ->orWhereHas('supplier', fn($sq) => $sq->where('name', 'like', "%{$this->search}%"))
            )
            ->when($this->supplierFilter, fn($q) => $q->where('supplier_id', $this->supplierFilter))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->orderBy($this->sortBy, $this->sortDir);

        $purchaseOrders = $query->paginate(15);
        $suppliers = Supplier::orderBy('name')->get();

        return view('livewire.purchases.purchase-order-list', compact('purchaseOrders', 'suppliers'))
            ->layout('layouts.app');
    }
}
