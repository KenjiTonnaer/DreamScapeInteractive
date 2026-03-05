<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\Player_inventory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class Player_inventoryFactory extends Factory
{
    protected $model = Player_inventory::class;

    public function definition(): array
    {
        return [
            'user_id' => User::query()->inRandomOrder()->value('id') ?? User::factory(),
            'item_id' => Item::query()->inRandomOrder()->value('id') ?? Item::factory(),
            'quantity' => $this->faker->numberBetween(1, 5),
        ];
    }
}
