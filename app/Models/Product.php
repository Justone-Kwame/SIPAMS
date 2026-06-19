<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'sku', 'barcode', 'brand', 'unit',
        'description', 'image_path', 'cost_price', 'selling_price',
        'reorder_level', 'max_stock'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function batches()
    {
        return $this->hasMany(ProductBatch::class);
    }
}
