<?php

namespace App\Livewire\Returns;

use App\Models\PurchaseOrder;
use App\Models\PurchaseReturn;
use App\Services\ReturnService;
use Livewire\Component;
use Livewire\WithPagination;

class PurchaseReturnManager extends Component
{
    use WithPagination;

    public int|string $purchaseOrderId = '';

    public array $lines = [];

    public string $reason = '';

    public function updatedPurchaseOrderId($value): void
    {
        $this->lines = [];

        if (! $value) {
            return;
        }

        $po = PurchaseOrder::with('items.product')->find($value);
        if (! $po) {
            return;
        }

        foreach ($po->items as $item) {
            $received = (int) ($item->quantity_received ?: $item->quantity_ordered);
            $this->lines[] = [
                'product_id' => $item->product_id,
                'name' => $item->product?->name ?? 'Product #'.$item->product_id,
                'received_qty' => $received,
                'unit_cost' => (float) $item->unit_cost,
                'return_qty' => 0,
            ];
        }
    }

    public function getReturnTotalProperty(): float
    {
        $total = 0.0;
        foreach ($this->lines as $line) {
            $total += min((int) $line['return_qty'], (int) $line['received_qty']) * (float) $line['unit_cost'];
        }

        return $total;
    }

    public function save(ReturnService $returnService): void
    {
        $this->validate([
            'purchaseOrderId' => 'required|exists:purchase_orders,id',
            'lines' => 'required|array|min:1',
            'lines.*.return_qty' => 'numeric|min:0',
            'reason' => 'nullable|string|max:500',
        ]);

        $po = PurchaseOrder::findOrFail($this->purchaseOrderId);

        $toReturn = [];
        foreach ($this->lines as $line) {
            $qty = (int) $line['return_qty'];
            if ($qty <= 0) {
                continue;
            }
            if ($qty > (int) $line['received_qty']) {
                $this->addError('lines', "Cannot return more than received for {$line['name']}.");

                return;
            }
            $toReturn[] = [
                'product_id' => $line['product_id'],
                'quantity' => $qty,
                'unit_cost' => (float) $line['unit_cost'],
            ];
        }

        if (empty($toReturn)) {
            $this->addError('lines', 'Enter a return quantity for at least one item.');

            return;
        }

        try {
            $returnService->createPurchaseReturn($po, $toReturn, $this->reason ?: null, auth()->id());
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());

            return;
        }

        session()->flash('success', 'Purchase return recorded.');
        $this->reset(['purchaseOrderId', 'lines', 'reason']);
        $this->resetPage();
    }

    public function render()
    {
        $purchaseOrders = PurchaseOrder::with('supplier')
            ->whereIn('status', ['delivered', 'ordered'])
            ->orderByDesc('order_date')
            ->limit(100)
            ->get();

        $returns = PurchaseReturn::with(['purchaseOrder', 'supplier'])
            ->orderByDesc('date')
            ->paginate(10);

        return view('livewire.returns.purchase-return-manager', compact('purchaseOrders', 'returns'))
            ->layout('layouts.app');
    }
}
