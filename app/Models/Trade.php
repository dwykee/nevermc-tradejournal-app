<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trade extends Model
{
    protected $fillable = [
        'user_id',
        'account_id',
        'instrument',
        'direction',
        'quantity',
        'entry_price',
        'exit_price',
        'entry_time',
        'exit_time',
        'gross_pnl',
        'commission',
        'net_pnl',
        'setup',
        'notes',
        'status',
        'rating',
        'screenshot_before',
        'screenshot_after',
    ];

    protected $casts = [
        'entry_time'  => 'datetime',
        'exit_time'   => 'datetime',
        'quantity'    => 'decimal:4',
        'entry_price' => 'decimal:4',
        'exit_price'  => 'decimal:4',
        'gross_pnl'   => 'decimal:2',
        'commission'  => 'decimal:2',
        'net_pnl'     => 'decimal:2',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
