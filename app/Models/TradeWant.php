<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TradeWant extends Model
{
    use HasFactory;

    protected $fillable = [
        'trade_id',
        'item_id',
        'quantity',
    ];

    protected $casts = [
        'trade_id' => 'integer',
        'item_id' => 'integer',
        'quantity' => 'integer',
    ];

    public function trade(): BelongsTo
    {
        return $this->belongsTo(Trade::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
