<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'username' => 'TestUser',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);
        $this->call(UserSeeder::class);
        $this->call(ItemSeeder::class);
        $this->call(Player_inventorySeeder::class);
        $this->call(TradeSeeder::class);
        $this->call(TradeItemSeeder::class);
    }
}
