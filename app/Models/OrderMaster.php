<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderMaster extends Model
{
    use HasFactory;
    function collectiontax(): BelongsTo
    {
        return $this->belongsTo(CollectionTax::class);

    }
    function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }
    function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    function invoiceEntity(): BelongsTo
    {
        return $this->belongsTo(InvoiceEntity::class, 'invoice_entity_id', 'id');
    }
}
