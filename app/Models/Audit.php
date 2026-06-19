<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    protected $fillable = [
        'user_id',
        'activity',
        'description',
        'ip_address',
        'model_type',
        'model_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Morph to the model that was audited
    public function auditable()
    {
        return $this->morphTo(__FUNCTION__, 'model_type', 'model_id');
    }
}
