<?php

namespace App\Livewire\Customers;

use Livewire\Component;
use App\Models\Customer;

class CustomerForm extends Component
{
    public ?int $customerId = null;
    public string $name = '';
    public string $phone = '';
    public string $email = '';
    public string $address = '';
    public int $loyalty_points = 0;
    public float $total_spend = 0;

    public function mount(?int $customerId = null): void
    {
        if ($customerId) {
            $this->customerId = $customerId;
            $customer = Customer::findOrFail($customerId);

            $this->name = $customer->name;
            $this->phone = $customer->phone ?? '';
            $this->email = $customer->email ?? '';
            $this->address = $customer->address ?? '';
            $this->loyalty_points = $customer->loyalty_points ?? 0;
            $this->total_spend = $customer->total_spend ?? 0;
        }
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:1000',
            'loyalty_points' => 'nullable|integer|min:0',
            'total_spend' => 'nullable|numeric|min:0'
        ]);

        $data = [
            'name' => $this->name,
            'phone' => $this->phone ?: null,
            'email' => $this->email ?: null,
            'address' => $this->address ?: null,
            'loyalty_points' => $this->loyalty_points,
            'total_spend' => $this->total_spend
        ];

        if ($this->customerId) {
            $customer = Customer::findOrFail($this->customerId);
            $customer->update($data);
        } else {
            $customer = Customer::create($data);
        }

        session()->flash('success', $this->customerId ? 'Customer updated.' : 'Customer created.');
        $this->redirect(route('customers.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.customers.customer-form')
            ->layout('layouts.app');
    }
}
