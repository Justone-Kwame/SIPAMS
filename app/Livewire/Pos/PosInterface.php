<?php

namespace App\Livewire\Pos;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use App\Services\PosService;

class PosInterface extends Component
{
    public string  $barcode        = '';
    public string  $search         = '';
    public array   $cart           = [];
    public string  $paymentMethod  = 'cash';
    public float   $discount       = 0;
    public string  $discountType   = 'fixed';   // 'fixed' or 'percentage'
    public float   $tax            = 0;
    public float   $shipping       = 0;
    public int     $selectedCategory = 0;   // 0 = All
    public string  $selectedBrand  = '';   // '' = All brands
    public int     $selectedCustomer = 0;   // 0 = Walk-in customer
    public int     $selectedWarehouse = 1;  // Default warehouse
    public bool    $showPayModal    = false;
    public string  $amountTendered = '';

    // ── Last completed sale (for receipt links) ──────────────────────────
    public ?int    $lastSaleId     = null;
    public bool    $showReceiptModal = false;

    // ── Per-item line discount (keyed by product_id) ─────────────────────
    public array   $lineDiscounts  = [];   // [product_id => discount_amount]

    // ── Cart helpers ─────────────────────────────────────────────────────

    public function addToCartById(int $productId): void
    {
        $product = Product::find($productId);
        if ($product) $this->addToCart($product);
    }

    public function addToCart($product): void
    {
        $id = is_array($product) ? $product['product_id'] : $product->id;

        if (isset($this->cart[$id])) {
            $this->cart[$id]['quantity']++;
        } else {
            $this->cart[$id] = [
                'product_id' => is_array($product) ? $product['product_id'] : $product->id,
                'name'       => is_array($product) ? $product['name']       : $product->name,
                'price'      => is_array($product) ? $product['price']      : $product->selling_price,
                'unit'       => is_array($product) ? ($product['unit'] ?? '') : ($product->unit ?? ''),
                'quantity'   => 1,
            ];
        }
    }

    public function incrementQty(int $id): void
    {
        if (isset($this->cart[$id])) $this->cart[$id]['quantity']++;
    }

    public function decrementQty(int $id): void
    {
        if (!isset($this->cart[$id])) return;
        $this->cart[$id]['quantity']--;
        if ($this->cart[$id]['quantity'] <= 0) unset($this->cart[$id]);
    }

    public function removeFromCart(int $id): void
    {
        unset($this->cart[$id]);
    }

    public function clearCart(): void
    {
        $this->cart          = [];
        $this->discount      = 0;
        $this->tax           = 0;
        $this->shipping      = 0;
        $this->lineDiscounts = [];
    }

    // ── Per-item line discount ────────────────────────────────────────────
    public function setLineDiscount(int $id, string $value): void
    {
        $this->lineDiscounts[$id] = max(0, (float) $value);
    }

    // ── Barcode scan ─────────────────────────────────────────────────────

    public function scan(): void
    {
        if (empty($this->barcode)) return;

        $product = Product::where('barcode', $this->barcode)
                          ->orWhere('sku', $this->barcode)
                          ->first();

        if ($product) {
            $this->addToCart($product);
        } else {
            session()->flash('error', 'Product not found.');
        }

        $this->barcode = '';
    }

    // ── Category filter ───────────────────────────────────────────────────

    public function selectCategory(int $id): void
    {
        $this->selectedCategory = $id;
        $this->search = '';
    }

    // ── Payment ───────────────────────────────────────────────────────────

    public function openPayModal(): void
    {
        if (!empty($this->cart)) $this->showPayModal = true;
    }

    public function checkout(PosService $posService): void
    {
        if (empty($this->cart)) return;

        try {
            $sale = $posService->processCheckout($this->cart, [
                'method'   => $this->paymentMethod,
                'discount' => $this->discount,
                'tax'      => 0,
            ], null, auth()->id());

            $this->lastSaleId     = $sale->id;
            $this->showPayModal   = false;
            $this->showReceiptModal = true;
            $this->clearCart();
            $this->amountTendered = '';
        } catch (\Exception $e) {
            $this->showPayModal = false;
            session()->flash('error', $e->getMessage());
        }
    }

    // ── Computed helpers ──────────────────────────────────────────────────

    private function subtotal(): float
    {
        $total = 0;
        foreach ($this->cart as $id => $item) {
            $lineTotal    = $item['price'] * $item['quantity'];
            $lineDiscount = (float) ($this->lineDiscounts[$id] ?? 0);
            $total += max(0, $lineTotal - $lineDiscount);
        }
        return $total;
    }

    private function totalQty(): int
    {
        $qty = 0;
        foreach ($this->cart as $item) {
            $qty += $item['quantity'];
        }
        return $qty;
    }

    // ── Render ────────────────────────────────────────────────────────────

    public function render()
    {
        $categories = Category::orderBy('name')->get();

        $productQuery = Product::with(['category', 'batches' => fn($q) => $q->where('status', 'active')])
            ->where(function ($q) {
                if ($this->search !== '') {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('sku',  'like', '%' . $this->search . '%')
                      ->orWhere('barcode', 'like', '%' . $this->search . '%');
                }
            });

        if ($this->selectedCategory > 0) {
            $productQuery->where('category_id', $this->selectedCategory);
        }

        // Filter by brand if selected
        if ($this->selectedBrand !== '') {
            $productQuery->where('brand', $this->selectedBrand);
        }

        $products = $productQuery->orderBy('name')->get();

        // Get all brands from products
        $brands = Product::select('brand')
            ->whereNotNull('brand')
            ->where('brand', '!=', '')
            ->distinct()
            ->orderBy('brand')
            ->pluck('brand')
            ->toArray();

        // Get customers (if applicable)
        $customers = \App\Models\Customer::orderBy('name')->limit(50)->get();

        $subtotal = $this->subtotal();
        $discountAmount = $this->discountType === 'percentage'
            ? round($subtotal * ($this->discount / 100), 2)
            : $this->discount;
        $afterDiscount = max(0, $subtotal - $discountAmount);
        $taxAmount = $this->tax;
        $total    = round($afterDiscount + $taxAmount + $this->shipping, 2);
        $totalQty = $this->totalQty();
        $change   = max(0, (float) $this->amountTendered - $total);

        return view('livewire.pos.pos-interface', compact(
            'categories', 'brands', 'customers', 'products', 'subtotal', 'discountAmount',
            'taxAmount', 'total', 'totalQty', 'change'
        ))->layout('layouts.pos');
    }
}
