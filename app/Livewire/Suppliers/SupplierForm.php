<?php

namespace App\Livewire\Suppliers;

use Livewire\Component;
use App\Models\Supplier;

class SupplierForm extends Component
{
    public ?int $supplierId = null;
    public string $name = '';
    public string $phone = '';
    public string $email = '';
    public string $address = '';

    public function mount(?int $supplierId = null): void
    {
        if ($supplierId) {
            $this->supplierId = $supplierId;
            $supplier = Supplier::findOrFail($supplierId);

            $this->name = $supplier->name;
            $this->phone = $supplier->phone ?? '';
            $this->email = $supplier->email ?? '';
            $this->address = $supplier->address ?? '';
        }
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:1000',
        ]);

        $data = [
            'name' => $this->name,
            'phone' => $this->phone ?: null,
            'email' => $this->email ?: null,
            'address' => $this->address ?: null,
        ];

        if ($this->supplierId) {
            $supplier = Supplier::findOrFail($this->supplierId);
            $supplier->update($data);
        } else {
            $supplier = Supplier::create($data);
        }

        session()->flash('success', $this->supplierId ? 'Supplier updated.' : 'Supplier created.');
        $this->redirect(route('suppliers.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.suppliers.supplier-form')
            ->layout('layouts.app');
    }
}
