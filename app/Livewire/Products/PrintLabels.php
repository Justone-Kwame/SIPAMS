<?php

namespace App\Livewire\Products;

use Livewire\Component;
use App\Models\Product;

class PrintLabels extends Component
{
    public string $search      = '';
    public string $paperSize   = '40_a4';
    public bool   $displayPrice = true;
    public bool   $autoPrint    = true;

    public array $selectedProducts = [];
    public array $searchResults    = [];

    public array $paperSizes = [
        '40_a4' => '40 per sheet (A4)  (1.799 × 1.003 in)',
        '24_a4' => '24 per sheet (A4)  (2.624 × 1.334 in)',
        '10_a4' => '10 per sheet (A4)  (3.937 × 1.102 in)',
    ];

    public function updatedSearch(): void
    {
        $q = trim($this->search);
        if ($q === '') {
            $this->searchResults = [];
            return;
        }
        $this->searchResults = Product::where('name', 'like', "%{$q}%")
            ->orWhere('sku',     'like', "%{$q}%")
            ->orWhere('barcode', 'like', "%{$q}%")
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name', 'sku', 'barcode', 'selling_price'])
            ->toArray();
    }

    public function addProduct(int $productId): void
    {
        foreach ($this->selectedProducts as $i => $p) {
            if ((int) $p['id'] === $productId) {
                $this->selectedProducts[$i]['quantity']++;
                $this->search = '';
                $this->searchResults = [];
                return;
            }
        }

        $product = Product::find($productId);
        if (!$product) return;

        $code = $product->sku ?: ($product->barcode ?: (string) $product->id);

        $this->selectedProducts[] = [
            'id'            => $product->id,
            'name'          => $product->name,
            'sku'           => $code,
            'selling_price' => (float) $product->selling_price,
            'quantity'      => 1,
        ];

        $this->search = '';
        $this->searchResults = [];
    }

    public function removeProduct(int $index): void
    {
        array_splice($this->selectedProducts, $index, 1);
    }

    public function resetProducts(): void
    {
        $this->selectedProducts = [];
        $this->search = '';
        $this->searchResults = [];
    }

    public function printLabels(): void
    {
        if (empty($this->selectedProducts)) return;

        session(['print_labels' => [
            'products'     => $this->selectedProducts,
            'displayPrice' => $this->displayPrice,
            'paperSize'    => $this->paperSize,
            'autoPrint'    => $this->autoPrint,
        ]]);

        $this->dispatch('open-print-window');
    }

    public function render()
    {
        return view('livewire.products.print-labels')
            ->layout('layouts.app', ['title' => 'Print Labels']);
    }
}
