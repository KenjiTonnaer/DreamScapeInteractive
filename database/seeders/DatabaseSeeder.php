<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Admin User',
                'username' => 'AdminUser',
                'password' => bcrypt('password123'),
                'role' => 'admin',
                'level' => 1,
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'username' => 'TestUser',
                'password' => bcrypt('password123'),
                'role' => 'user',
                'level' => 1,
            ]
        );

        $this->call(UserSeeder::class);
        $this->call(ItemSeeder::class);
        $this->call(Player_inventorySeeder::class);
        $this->call(TradeSeeder::class);
        $this->call(TradeItemSeeder::class);
        $this->call(TradeWantSeeder::class);
    }
}
