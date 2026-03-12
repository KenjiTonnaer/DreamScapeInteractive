<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Trade;
use App\Models\Item;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class TradeWantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $trade = Trade::query()->inRandomOrder()->first() ?? Trade::factory()->create();
        $itemId = Item::query()->inRandomOrder()->value('id') ?? Item::factory()->create()->id;

        return [
            'trade_id' => $trade->id,
            'item_id' => $itemId,
            'quantity' => fake()->numberBetween(1, 3),
        ];
    }
}
