<?php

namespace Database\Factories;

use App\Models\Trade;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trade>
 */
class TradeFactory extends Factory
{
    // ...existing code...
    protected $model = Trade::class;
    // ...existing code...

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fromUserId = User::query()->inRandomOrder()->value('id') ?? User::factory()->create()->id;

        $toUserId = User::query()
            ->whereKeyNot($fromUserId)
            ->inRandomOrder()
            ->value('id') ?? User::factory()->create()->id;

        if ($fromUserId === $toUserId) {
            $toUserId = User::factory()->create()->id;
        }

        return [
            'from_user_id' => $fromUserId,
            'to_user_id' => $toUserId,
            // Meer "pending" zodat je backlog-gevoel krijgt
            'status' => fake()->randomElement([
                'pending', 'pending', 'pending', 'pending',
                'accepted', 'rejected', 'cancelled',
            ]),
        ];
    }
}
