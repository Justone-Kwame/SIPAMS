<?php

namespace App\Repositories\Contracts;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function findByBarcode(string $barcode);
    public function findBySku(string $sku);
    public function getLowStockProducts();
}
