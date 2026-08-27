<?php

namespace Database\Seeders;

use App\Models\Driver;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $drivers = [
            ['name' => 'Saman Kumara',   'email' => 'saman@example.com',   'phone' => '+94 76 123 4567', 'status' => 'available',   'vehicle_type' => 'Bike', 'vehicle_number' => 'BE-4521', 'total_deliveries' => 142],
            ['name' => 'Nimal Siripala', 'email' => 'nimal@example.com',   'phone' => '+94 77 234 5678', 'status' => 'on_delivery', 'vehicle_type' => 'Tuk',  'vehicle_number' => 'QH-8892', 'total_deliveries' => 98],
            ['name' => 'Kasun Jayasuriya','email' => 'kasun@example.com',  'phone' => '+94 71 345 6789', 'status' => 'available',   'vehicle_type' => 'Van',  'vehicle_number' => 'WP-2234', 'total_deliveries' => 76],
            ['name' => 'Amara Bandara',  'email' => 'amara@example.com',   'phone' => '+94 76 456 7890', 'status' => 'offline',     'vehicle_type' => 'Bike', 'vehicle_number' => 'BE-7781', 'total_deliveries' => 53],
            ['name' => 'Dinesh Perera',  'email' => 'dinesh@example.com',  'phone' => '+94 72 567 8901', 'status' => 'on_delivery', 'vehicle_type' => 'Tuk',  'vehicle_number' => 'KG-1190', 'total_deliveries' => 211],
            ['name' => 'Sadia Rahman',   'email' => 'sadia@example.com',   'phone' => '+94 70 678 9012', 'status' => 'available',   'vehicle_type' => 'Van',  'vehicle_number' => 'WP-5501', 'total_deliveries' => 34],
        ];

        foreach ($drivers as $driver) {
            Driver::updateOrCreate(['email' => $driver['email']], $driver);
        }
    }
}
