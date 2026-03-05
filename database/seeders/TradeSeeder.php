<?php

namespace Database\Seeders;

use App\Models\Trade;
use App\Models\User;
use Illuminate\Database\Seeder;

class TradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ...existing code...
        $minUsers = 12;

        if (User::count() < $minUsers) {
            User::factory()->count($minUsers - User::count())->create();
        }

        Trade::factory()->count(30)->create();
        // ...existing code...
    }
}
