<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function findByBarcode(string $barcode)
    {
        return $this->model->where('barcode', $barcode)->first();
    }

    public function findBySku(string $sku)
    {
        return $this->model->where('sku', $sku)->first();
    }

    public function getLowStockProducts()
    {
        // This is a simplified check. A true low stock check might aggregate batch quantities
        // if stock isn't kept directly on the product table. Assuming we maintain a current_stock field or compute it.
        // For now we'll just return products that need reorder based on related batches.
        return $this->model->whereHas('batches', function ($query) {
            $query->where('status', 'active');
        })->get()->filter(function ($product) {
            return $product->batches->sum('quantity_remaining') <= $product->reorder_level;
        });
    }
}
