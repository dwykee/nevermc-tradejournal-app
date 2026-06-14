<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    public function getPnlFormattedAttribute(): string
{
    $sign = $this->net_pnl >= 0 ? '+' : '';
    return $sign . '$' . number_format($this->net_pnl, 2);
}
}
