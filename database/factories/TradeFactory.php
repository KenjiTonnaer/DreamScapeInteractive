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

        $isOpenTrade = fake()->boolean(40); // 40% open trades

        $toUserId = null;
        $status = 'open';

        if (! $isOpenTrade) {
            $toUserId = User::query()
                ->whereKeyNot($fromUserId)
                ->inRandomOrder()
                ->value('id') ?? User::factory()->create()->id;

            if ($fromUserId === $toUserId) {
                $toUserId = User::factory()->create()->id;
            }

            $status = fake()->randomElement(['pending', 'accepted', 'rejected', 'cancelled']);
        }

        return [
            'from_user_id' => $fromUserId,
            'to_user_id' => $toUserId,
            'status' => $status,
        ];
    }
}
