<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Card;

class Payment extends Model
{
    // Definimos los campos que pueden asignarse masivamente
    protected $fillable = [
        'card_id',
        'original_amount',
        'final_amount',
        'installments',
    ];

    /**
     * Relación inversa: Un pago pertenece a una tarjeta.
     *
     * @return BelongsTo
     */
    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}
