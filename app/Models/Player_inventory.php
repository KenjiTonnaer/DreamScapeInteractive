<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player_inventory extends Model
{
    use HasFactory;

    protected $table = 'player_inventory';

    protected $fillable = [
        'user_id',
        'item_id',
        'quantity',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'item_id' => 'integer',
        'quantity' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
