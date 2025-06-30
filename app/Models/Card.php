<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Payment;

class Card extends Model
{
    protected $fillable = [
        'client_id', 'card_type', 'bank_name', 'card_number', 'available_limit'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
