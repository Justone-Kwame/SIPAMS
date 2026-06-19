<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    protected $guarded = [];

    // ── Scopes ─────────────────────────────────────────────────────────────────
    public function scopeInPeriod($query, $start, $end)
    {
        return $query->whereHas('sale', fn($q) => $q->completed()->whereBetween('date', [$start, $end]));
    }

    // ── Relations ──────────────────────────────────────────────────────────────
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
