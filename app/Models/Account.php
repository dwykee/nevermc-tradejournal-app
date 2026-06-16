<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'broker',
        'type',
        'starting_balance',
        'balance',
        'currency',
        'notes',
    ];

    protected $casts = [
        'starting_balance' => 'decimal:2',
        'balance'          => 'decimal:2',
    ];

    public function trades(): \Illuminate\Database\Eloquent\Relations\HasMany 
    {
        return $this->hasMany(Trade::class);
    }
}