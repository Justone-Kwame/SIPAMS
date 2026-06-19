<?php

namespace App\Livewire\Products;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\Category;
use App\Services\ProductService;
use App\Services\AuditService;
use Illuminate\Support\Str;

class ProductForm extends Component
{
    use WithFileUploads;

    // ── Route model binding (edit mode) ──────────────────────────────────────
    public ?int $productId = null;

    // ── Product Registration ─────────────────────────────────────────────────
    public string $name        = '';
    public string $sku         = '';
    public string $barcode     = '';
    public int|string $category_id = '';
    public string $brand       = '';
    public string $unit        = 'pcs';
    public string $description = '';
    public $image; // uploaded file

    // ── Pricing ──────────────────────────────────────────────────────────────
    public float|string $cost_price    = '';
    public float|string $selling_price = '';

    // ── Computed pricing (read-only display) ─────────────────────────────────
    public float $profit_amount  = 0;
    public float $profit_percent = 0;

    // ── Stock Information ─────────────────────────────────────────────────────
    public int|string $initial_quantity = 0;
    public int|string $reorder_level    = 10;
    public int|string $max_stock        = '';

    // ── Lifecycle ─────────────────────────────────────────────────────────────
    public function mount(?int $productId = null): void
    {
        if ($productId) {
            $this->productId = $productId;
            $product = Product::with(['batches' => fn($q) => $q->where('status', 'active')])->findOrFail($productId);

            $this->name         = $product->name;
            $this->sku          = $product->sku ?? '';
            $this->barcode      = $product->barcode ?? '';
            $this->category_id  = $product->category_id;
            $this->brand        = $product->brand ?? '';
            $this->unit         = $product->unit ?? 'pcs';
            $this->description  = $product->description ?? '';
            $this->cost_price   = $product->cost_price;
            $this->selling_price = $product->selling_price;
            $this->reorder_level = $product->reorder_level ?? 10;
            $this->max_stock    = $product->max_stock ?? '';
            $this->initial_quantity = $product->batches->sum('quantity_remaining');

            $this->recalcProfit();
        }
    }

    // ── Watchers: live recalculate profit ─────────────────────────────────────
    public function updatedCostPrice(): void    { $this->recalcProfit(); }
    public function updatedSellingPrice(): void { $this->recalcProfit(); }

    public function recalcProfit(): void
    {
        $cost    = (float) $this->cost_price;
        $selling = (float) $this->selling_price;

        $this->profit_amount  = $selling - $cost;
        $this->profit_percent = $cost > 0 ? round((($selling - $cost) / $cost) * 100, 2) : 0;
    }

    // ── Auto-generate SKU from name ───────────────────────────────────────────
    public function updatedName(): void
    {
        if (empty($this->sku)) {
            $prefix = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $this->name), 0, 3));
            $this->sku = $prefix . '-' . strtoupper(Str::random(5));
        }
    }

    // ── Computed: inventory value ─────────────────────────────────────────────
    public function getInventoryValueProperty(): float
    {
        return (float) $this->initial_quantity * (float) $this->cost_price;
    }

    // ── Save ─────────────────────────────────────────────────────────────────
    public function save(ProductService $productService): void
    {
        $this->validate([
            'name'          => 'required|string|max:255',
            'category_id'   => 'required|exists:categories,id',
            'cost_price'    => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'unit'          => 'required|string|max:50',
            'reorder_level' => 'required|integer|min:0',
            'max_stock'     => 'nullable|integer|min:0',
            'initial_quantity' => 'required|integer|min:0',
            'image'         => 'nullable|image|max:2048',
            'sku'           => 'nullable|string|max:100|unique:products,sku' . ($this->productId ? ",{$this->productId}" : ''),
            'barcode'       => 'nullable|string|max:100|unique:products,barcode' . ($this->productId ? ",{$this->productId}" : ''),
        ]);

        $data = [
            'name'          => $this->name,
            'sku'           => $this->sku ?: null,
            'barcode'       => $this->barcode ?: null,
            'category_id'   => $this->category_id,
            'brand'         => $this->brand,
            'unit'          => $this->unit,
            'description'   => $this->description,
            'cost_price'    => $this->cost_price,
            'selling_price' => $this->selling_price,
            'reorder_level' => $this->reorder_level,
            'max_stock'     => $this->max_stock ?: null,
        ];

        if ($this->image) {
            $data['image_path'] = $this->image->store('products', 'public');
        }

        if ($this->productId) {
            $product = Product::findOrFail($this->productId);
            $product->update($data);
            AuditService::log('Product Updated', "Updated product '{$product->name}'", $product);
        } else {
            $product = $productService->createProduct($data);
            AuditService::log('Product Created', "Created new product '{$product->name}'", $product);

            // Create initial batch if quantity > 0
            if ((int) $this->initial_quantity > 0) {
                ProductBatch::create([
                    'product_id'         => $product->id,
                    'batch_number'       => 'INIT-' . strtoupper(Str::random(6)),
                    'quantity_initial'   => (int) $this->initial_quantity,
                    'quantity_remaining' => (int) $this->initial_quantity,
                    'cost_price'         => $this->cost_price,
                    'status'             => 'active',
                ]);
                AuditService::log('Stock Added', "Added {$this->initial_quantity} units of '{$product->name}'", $product);
            }
        }

        session()->flash('success', $this->productId ? 'Product updated.' : 'Product created.');
        $this->redirect(route('products.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.products.product-form', [
            'categories' => Category::orderBy('name')->get(),
            'units'      => ['pcs', 'kg', 'g', 'litre', 'ml', 'box', 'carton', 'pack', 'dozen', 'm', 'cm'],
        ])->layout('layouts.app');
    }
}
