<?php

namespace App\Livewire\Suppliers;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Supplier;

class SupplierList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $sortBy = 'name';
    public string $sortDir = 'asc';

    public ?int $deleteId = null;
    public bool $confirmDelete = false;

    protected $queryString = ['search'];

    public function updatingSearch(): void { $this->resetPage(); }

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
            Supplier::findOrFail($this->deleteId)->delete();
            session()->flash('success', 'Supplier deleted.');
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
        $query = Supplier::with(['purchaseOrders'])
            ->when($this->search, fn($q) =>
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('phone', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%")
            )
            ->orderBy($this->sortBy, $this->sortDir);

        $suppliers = $query->paginate(15);

        return view('livewire.suppliers.supplier-list', compact('suppliers'))
            ->layout('layouts.app');
    }
}
