<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Player_inventory;
use App\Models\User;
use Illuminate\Database\Seeder;

class Player_inventorySeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $itemIds = Item::pluck('id')->all();

        if ($users->isEmpty() || empty($itemIds)) {
            return;
        }

        foreach ($users as $user) {
            $picked = collect($itemIds)
                ->shuffle()
                ->take(rand(3, min(8, count($itemIds))));

            foreach ($picked as $itemId) {
                Player_inventory::updateOrCreate(
                    ['user_id' => $user->id, 'item_id' => $itemId],
                    ['quantity' => rand(1, 5)]
                );
            }
        }
    }
}
