<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'mgpdesaman@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('88222006'),
            ]
        );

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
        ]);

        $this->call(OrderSeeder::class);
        $this->call(DriverSeeder::class);
        $this->call(EventSeeder::class);
    }
}
