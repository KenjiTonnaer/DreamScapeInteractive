<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'rarity',
        'strength',
        'speed',
        'durability',
        'magical_property',
        'required_level',
    ];

    protected $casts = [
        'strength' => 'integer',
        'speed' => 'integer',
        'durability' => 'integer',
        'required_level' => 'integer',
    ];
}
