<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    private static array $generatedNames = [];

    public function definition(): array
    {
        $type = $this->faker->randomElement(['weapon', 'armor', 'consumable', 'misc']);

        return [
            'name' => $this->generateUniqueName($type),
            'description' => $this->generateDescription($type),
            'type' => $type,
            'rarity' => $this->faker->randomElement(['common', 'uncommon', 'rare', 'epic', 'legendary']),
            'strength' => $this->faker->numberBetween(0, 100),
            'speed' => $this->faker->numberBetween(0, 100),
            'durability' => $this->faker->numberBetween(0, 100),
            'magical_property' => $this->faker->randomElement([
                '+' . $this->faker->numberBetween(5, 40) . '% fire damage',
                '+' . $this->faker->numberBetween(5, 35) . '% ice resistance',
                '+' . $this->faker->numberBetween(3, 25) . '% dodge chance',
                '+' . $this->faker->numberBetween(5, 30) . '% critical chance',
                'Absorbs ' . $this->faker->numberBetween(5, 30) . '% incoming damage',
                '+' . $this->faker->numberBetween(2, 15) . ' HP per second',
                'Stuns enemies for ' . $this->faker->numberBetween(1, 5) . ' sec',
                null,
            ]),
            'required_level' => $this->faker->numberBetween(1, 20),
        ];
    }

    private function generateUniqueName(string $type): string
    {
        $tries = 0;

        do {
            $tries++;
            $name = $this->generateRealisticName($type);

            $existsInBatch = in_array($name, self::$generatedNames, true);
            $existsInDb = Item::where('name', $name)->exists();

            if (!$existsInBatch && !$existsInDb) {
                self::$generatedNames[] = $name;
                return $name;
            }
        } while ($tries < 40);

        $fallback = $this->generateRealisticName($type) . ' #' . $this->faker->numberBetween(1000, 9999);
        self::$generatedNames[] = $fallback;

        return $fallback;
    }

    private function generateRealisticName(string $type): string
    {
        $pool = match ($type) {
            'weapon' => ['Zwaard van de Asvlam', 'Stormsnijder', 'Noordwind Speer', 'Hamer van de Wachter', 'Dageraad Dolk'],
            'armor' => ['Harnas van de Eedwacht', 'Schild van Valoria', 'Helm van Donkerhout', 'Maanwacht Mantel', 'Borstplaat van IJzerkroon'],
            'consumable' => ['Elixir van Herstel', 'Tonic van Reflexen', 'Essence van Oerkracht', 'Flacon van IJsweerstand', 'Serum van Standvastigheid'],
            default => ['Amulet van de Noorderster', 'Ring van Aster', 'Talisman van de Diepte', 'Reliek van de Wachter', 'Kristal van Stormlicht'],
        };

        return $this->faker->randomElement($pool);
    }

    private function generateDescription(string $type): string
    {
        return match ($type) {
            'weapon' => 'Een gebalanceerd wapen, gesmeed voor zware gevechten.',
            'armor' => 'Stevig pantserwerk met betrouwbare bescherming.',
            'consumable' => 'Een verbruiksitem met tijdelijke gevechtsvoordelen.',
            default => 'Een zeldzaam artefact met magische toepassingen.',
        };
    }
}
