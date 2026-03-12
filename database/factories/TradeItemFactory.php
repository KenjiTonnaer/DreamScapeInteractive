<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\Trade;
use App\Models\TradeItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TradeItem>
 */
class TradeItemFactory extends Factory
{
    protected $model = TradeItem::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $trade = Trade::query()->inRandomOrder()->first() ?? Trade::factory()->create();
        $itemId = Item::query()->inRandomOrder()->value('id') ?? Item::factory()->create()->id;

        $fromUserPool = array_filter([
            $trade->from_user_id,
            $trade->to_user_id,
        ]);

        $fromUserId = fake()->randomElement(
            !empty($fromUserPool) ? $fromUserPool : [$trade->from_user_id]
        );

        return [
            'trade_id' => $trade->id,
            'item_id' => $itemId,
            'from_user_id' => $fromUserId,
            'quantity' => fake()->numberBetween(1, 5),
        ];
    }
}
