<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
    use HasFactory;
    function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    function uom(): BelongsTo
    {
        return $this->belongsTo(Size::class, 'uom_id', 'id');
    }
    public function make(): BelongsTo
    {
        if ($this->make_id) {
            return $this->belongsTo(Vendor::class, 'make_id', 'id');
        } elseif ($this->make_id2) {
            return $this->belongsTo(Vendor::class, 'make_id2', 'id');
        } else {
            // If neither make_id nor make2_id is set, return null or a default value
            return null; // or return a default vendor if needed
        }
    }
}
