<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Trade;
use App\Models\TradeWant;


class TradeWantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Trade::count() === 0) {
            Trade::factory()->count(10)->create();
        }

        foreach (Trade::all() as $trade) {
            TradeWant::factory()
                ->count(fake()->numberBetween(1, 3))
                ->create(['trade_id' => $trade->id]);
        }
    }
}
