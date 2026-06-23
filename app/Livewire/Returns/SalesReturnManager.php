<?php

namespace App\Livewire\Returns;

use App\Models\Sale;
use App\Models\SalesReturn;
use App\Services\ReturnService;
use Livewire\Component;
use Livewire\WithPagination;

class SalesReturnManager extends Component
{
    use WithPagination;

    public int|string $saleId = '';

    public array $lines = [];

    public string $reason = '';

    public function updatedSaleId($value): void
    {
        $this->lines = [];

        if (! $value) {
            return;
        }

        $sale = Sale::with('items.product')->find($value);
        if (! $sale) {
            return;
        }

        foreach ($sale->items as $item) {
            $this->lines[] = [
                'product_id' => $item->product_id,
                'name' => $item->product?->name ?? 'Product #'.$item->product_id,
                'sold_qty' => (int) $item->quantity,
                'unit_price' => (float) $item->unit_price,
                'return_qty' => 0,
            ];
        }
    }

    public function getReturnTotalProperty(): float
    {
        $total = 0.0;
        foreach ($this->lines as $line) {
            $total += min((int) $line['return_qty'], (int) $line['sold_qty']) * (float) $line['unit_price'];
        }

        return $total;
    }

    public function save(ReturnService $returnService): void
    {
        $this->validate([
            'saleId' => 'required|exists:sales,id',
            'lines' => 'required|array|min:1',
            'lines.*.return_qty' => 'numeric|min:0',
            'reason' => 'nullable|string|max:500',
        ]);

        $sale = Sale::findOrFail($this->saleId);

        $toReturn = [];
        foreach ($this->lines as $line) {
            $qty = (int) $line['return_qty'];
            if ($qty <= 0) {
                continue;
            }
            if ($qty > (int) $line['sold_qty']) {
                $this->addError('lines', "Cannot return more than sold for {$line['name']}.");

                return;
            }
            $toReturn[] = [
                'product_id' => $line['product_id'],
                'quantity' => $qty,
                'unit_price' => (float) $line['unit_price'],
            ];
        }

        if (empty($toReturn)) {
            $this->addError('lines', 'Enter a return quantity for at least one item.');

            return;
        }

        try {
            $returnService->createSalesReturn($sale, $toReturn, $this->reason ?: null, auth()->id());
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());

            return;
        }

        session()->flash('success', 'Sales return recorded.');
        $this->reset(['saleId', 'lines', 'reason']);
        $this->resetPage();
    }

    public function render()
    {
        $sales = Sale::completed()->orderByDesc('date')->limit(100)->get();

        $returns = SalesReturn::with(['sale', 'customer'])
            ->orderByDesc('date')
            ->paginate(10);

        return view('livewire.returns.sales-return-manager', compact('sales', 'returns'))
            ->layout('layouts.app');
    }
}
