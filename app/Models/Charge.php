<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Charge extends Model
{
    use HasFactory;

    public function tax()
    {
        return $this->belongsTo(Tax::class, 'referred_tax', 'id');
    }
}
