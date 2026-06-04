<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            LineSeeder::class,
            StationSeeder::class,
            VehicleSeeder::class,
            DriverSeeder::class,
        ]);
    }
}
