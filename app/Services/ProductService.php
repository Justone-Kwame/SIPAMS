<?php

namespace App\Services;

use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Str;

class ProductService extends BaseService
{
    protected ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function createProduct(array $data)
    {
        if (isset($data['cost_price']) && isset($data['selling_price'])) {
            $data['profit_amount'] = $data['selling_price'] - $data['cost_price'];
        }

        if (empty($data['sku'])) {
            $data['sku'] = $this->generateSku($data['name']);
        }

        return $this->productRepository->create($data);
    }

    public function updateProduct($id, array $data)
    {
        return $this->productRepository->update($id, $data);
    }

    protected function generateSku($name)
    {
        $prefix = strtoupper(substr($name, 0, 3));
        $uniqueId = strtoupper(Str::random(5));
        return $prefix . '-' . $uniqueId;
    }
}
