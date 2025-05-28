<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => fake()->email,
            "password" => Hash::make('12345678'),
        ]);

        User::factory()->count(5)->create();
        $this->call(CategorySeeder::class);
        $this->call(TicketSeeder::class);
    }
}
