<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name', 'phone', 'email', 'address', 'loyalty_points', 'total_spend'
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
