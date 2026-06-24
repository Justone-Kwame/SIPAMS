<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesReturn extends Model
{
    protected $guarded = [];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(SalesReturnItem::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
