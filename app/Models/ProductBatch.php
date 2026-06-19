<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ProductBatch extends Model
{
    protected $fillable = [
        'product_id',
        'purchase_id',
        'batch_number',
        'quantity_initial',
        'quantity_remaining',
        'cost_price',
        'expiry_date',
        'status'
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'quantity_initial' => 'integer',
        'quantity_remaining' => 'integer',
        'cost_price' => 'decimal:2'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function purchase()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function movements()
    {
        return $this->hasMany(StockMovement::class, 'product_batch_id');
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('status', 'active');
    }
}
