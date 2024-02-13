<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderCharge extends Model
{
    use HasFactory;
    function charge(): BelongsTo
    {
        return $this->belongsTo(Charge::class, 'order_charge_id', 'id');
    }
}
