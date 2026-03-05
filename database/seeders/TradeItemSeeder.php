<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Trade;
use App\Models\TradeItem;
use Illuminate\Database\Seeder;

class TradeItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $minTrades = 1;
        $minItems = 1;

        if (Trade::count() < $minTrades) {
            Trade::factory()->count($minTrades - Trade::count())->create();
        }

        if (Item::count() < $minItems) {
            Item::factory()->count($minItems - Item::count())->create();
        }

        TradeItem::factory()->count(100)->create();
    }
}
