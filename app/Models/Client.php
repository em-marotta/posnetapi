<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['dni', 'first_name', 'last_name'];

    public function cards()
    {
        return $this->hasMany(Card::class);
    }
}
