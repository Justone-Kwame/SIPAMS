<?php

namespace App\Livewire\Purchases;

use Livewire\Component;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use App\Models\Product;
use App\Services\PurchaseService;
use Illuminate\Support\Carbon;

class PurchaseOrderForm extends Component
{
    public ?int $purchaseOrderId = null;
    public int|string $supplier_id = '';
    public string $order_date = '';
    public ?string $expected_delivery_date = null;
    public string $status = 'draft';
    public string $payment_status = 'unpaid';
    public ?string $notes = null;

    public array $items = [];
    public float $subtotal = 0;
    public float $order_tax = 0;
    public float $order_tax_percent = 0;
    public float $discount = 0;
    public float $shipping = 0;
    public float $total_amount = 0;

    public function mount(?int $purchaseOrderId = null): void
    {
        $this->order_date = Carbon::now()->format('Y-m-d');

        if ($purchaseOrderId) {
            $this->purchaseOrderId = $purchaseOrderId;
            $po = PurchaseOrder::with(['items.product'])->findOrFail($purchaseOrderId);

            $this->supplier_id = $po->supplier_id;
            $this->order_date = $po->order_date;
            $this->expected_delivery_date = $po->expected_delivery_date;
            $this->status = $po->status;
            $this->payment_status = $po->payment_status ?? 'unpaid';
            $this->notes = $po->notes;
            $this->total_amount = $po->total_amount;
            $this->order_tax = $po->order_tax ?? 0;
            $this->discount = $po->discount ?? 0;
            $this->shipping = $po->shipping ?? 0;

            $this->items = $po->items->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'quantity' => $item->quantity_ordered,
                    'unit_cost' => $item->unit_cost,
                    'discount' => $item->discount ?? 0,
                    'tax' => $item->tax ?? 0,
                    'total' => $item->total_cost,
                ];
            })->toArray();
        } else {
            $this->items = [['product_id' => '', 'quantity' => 1, 'unit_cost' => 0, 'discount' => 0, 'tax' => 0, 'total' => 0]];
        }
    }

    public function addItem(): void
    {
        $this->items[] = ['product_id' => '', 'quantity' => 1, 'unit_cost' => 0, 'discount' => 0, 'tax' => 0, 'total' => 0];
    }

    public function removeItem(int $index): void
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->calculateTotal();
    }

    public function updatedItems($value, $key): void
    {
        $parts = explode('.', $key);
        $index = $parts[0];
        $field = $parts[1] ?? null;

        if ($field === 'product_id' && !empty($value)) {
            $product = Product::find($value);
            if ($product) {
                $this->items[$index]['product_name'] = $product->name;
                $this->items[$index]['unit_cost'] = $product->cost_price;
            }
        }

        if (in_array($field, ['quantity', 'unit_cost', 'discount', 'tax'])) {
            $qty = (float) $this->items[$index]['quantity'] ?? 0;
            $cost = (float) $this->items[$index]['unit_cost'] ?? 0;
            $discount = (float) $this->items[$index]['discount'] ?? 0;
            $tax = (float) $this->items[$index]['tax'] ?? 0;
            
            $this->items[$index]['total'] = ($qty * $cost) - $discount + $tax;
        }

        $this->calculateTotal();
    }

    public function updatedOrderTaxPercent(): void
    {
        $this->order_tax = ($this->subtotal * $this->order_tax_percent) / 100;
        $this->calculateTotal();
    }

    public function updatedOrderTax(): void
    {
        $this->calculateTotal();
    }

    public function updatedDiscount(): void
    {
        $this->calculateTotal();
    }

    public function updatedShipping(): void
    {
        $this->calculateTotal();
    }

    public function calculateTotal(): void
    {
        $this->subtotal = array_sum(array_column($this->items, 'total'));
        $this->total_amount = $this->subtotal + $this->order_tax - $this->discount + $this->shipping;
    }

    public function save(PurchaseService $purchaseService): void
    {
        $this->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date',
            'status' => 'required|in:draft,ordered,delivered,cancelled',
            'payment_status' => 'required|in:unpaid,partial,paid',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0',
        ]);

        $data = [
            'supplier_id' => $this->supplier_id,
            'order_date' => $this->order_date,
            'expected_delivery_date' => $this->expected_delivery_date,
            'status' => $this->status,
            'payment_status' => $this->payment_status,
            'notes' => $this->notes,
            'order_tax' => $this->order_tax,
            'discount' => $this->discount,
            'shipping' => $this->shipping,
            'total_amount' => $this->total_amount,
        ];

        $items = collect($this->items)->map(function ($item) {
            return [
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_cost' => $item['unit_cost'],
                'discount' => $item['discount'] ?? 0,
                'tax' => $item['tax'] ?? 0,
                'total' => $item['total'],
            ];
        })->toArray();

        if ($this->purchaseOrderId) {
            $po = PurchaseOrder::findOrFail($this->purchaseOrderId);
            $po->update($data);
            $po->items()->delete();
            foreach ($items as $item) {
                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'product_id' => $item['product_id'],
                    'quantity_ordered' => $item['quantity'],
                    'quantity_received' => 0,
                    'unit_cost' => $item['unit_cost'],
                    'discount' => $item['discount'],
                    'tax' => $item['tax'],
                    'total_cost' => $item['total'],
                ]);
            }
        } else {
            $po = $purchaseService->createPurchaseOrder($data, $items, auth()->id());
        }
        session()->flash('success', $this->purchaseOrderId ? 'Purchase Order updated.' : 'Purchase Order created.');
        $this->redirect(route('purchases.index'), navigate: true);
    }

    public function render()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('livewire.purchases.purchase-order-form', compact('suppliers', 'products'))
            ->layout('layouts.app');
    }
}