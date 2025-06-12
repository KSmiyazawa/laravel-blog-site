<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a test user for development
        User::factory()->create([
            'name' => 'Test User',
            'username' => 'testuser',
        ]);

        // Run the post seeder
        $this->call([
            PostSeeder::class,
        ]);
    }
}
