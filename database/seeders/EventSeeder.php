<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $user = User::first() ?? User::factory()->create();

        $events = [
            [
                'name' => 'Wedding - Mr. Sunil',
                'start_date' => now()->addDays(2),
                'end_date' => now()->addDays(2),
                'total_cost' => 185000.00,
                'driver_id' => null,
            ],
            [
                'name' => 'Birthday Party - 50 Pax',
                'start_date' => now()->addDays(5),
                'end_date' => now()->addDays(5),
                'total_cost' => 45000.00,
                'driver_id' => null,
            ],
            [
                'name' => 'Corporate Gala - ABC Corp',
                'start_date' => now()->addDays(9),
                'end_date' => now()->addDays(10),
                'total_cost' => 320000.00,
                'driver_id' => null,
            ],
            [
                'name' => 'Anniversary Dinner - Perera',
                'start_date' => now()->subDays(3),
                'end_date' => now()->subDays(3),
                'total_cost' => 28000.00,
                'driver_id' => null,
            ],
        ];

        foreach ($events as $event) {
            Event::updateOrCreate(
                ['name' => $event['name'], 'start_date' => $event['start_date']],
                array_merge($event, ['user_id' => $user->id])
            );
        }
    }
}
