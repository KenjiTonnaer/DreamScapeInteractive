<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $fixedItems = [
            [
                'name' => 'Zwaard des Vuur',
                'description' => 'Een mythisch zwaard met een vlammende gloed.',
                'type' => 'weapon',
                'rarity' => 'legendary',
                'strength' => 90,
                'speed' => 60,
                'durability' => 80,
                'magical_property' => '+30% fire damage',
                'required_level' => 20,
            ],
            [
                'name' => 'IJs Amulet',
                'description' => 'Een amulet dat de drager beschermt tegen kou.',
                'type' => 'misc',
                'rarity' => 'epic',
                'strength' => 20,
                'speed' => 10,
                'durability' => 70,
                'magical_property' => '+25% ice resistance',
                'required_level' => 15,
            ],
            [
                'name' => 'Demonen Harnas',
                'description' => 'Een verdoemd harnas met duistere krachten.',
                'type' => 'armor',
                'rarity' => 'legendary',
                'strength' => 75,
                'speed' => 50,
                'durability' => 95,
                'magical_property' => 'Absorbs 20% incoming damage',
                'required_level' => 22,
            ],
        ];

        foreach ($fixedItems as $item) {
            Item::updateOrCreate(['name' => $item['name']], $item);
        }

        Item::factory()->count(30)->create();
    }
}
