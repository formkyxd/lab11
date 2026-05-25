<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;
use App\Models\Line;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $lines = Line::all();

        $vehicleNames = [
            'Tram'       => ['КТМ-5', 'Татра Т3', 'ЛВС-86', 'КТМ-19', 'Stadler'],
            'Bus'        => ['ЛиАЗ-5256', 'МАЗ-203', 'НефАЗ-5299', 'ПАЗ-3205', 'Volgabus'],
            'Nightliner' => ['ГАЗель Next', 'Форд Транзит', 'Mersedes Sprinter', 'ПАЗ-320435', 'Iveco'],
        ];

        $capacities = [
            'Tram'       => [100, 120, 140, 160, 180],
            'Bus'        => [60,  80,  100, 110, 120],
            'Nightliner' => [13,  18,  20,  22,  25],
        ];

        foreach ($lines as $line) {
            $count = rand(3, 6);
            $names = $vehicleNames[$line->type];
            $caps  = $capacities[$line->type];

            for ($i = 0; $i < $count; $i++) {
                Vehicle::create([
                    'name'     => $names[$i % count($names)] . ' №' . rand(100, 999),
                    'capacity' => $caps[array_rand($caps)],
                    'type'     => $line->type,
                    'line_id'  => $line->id,
                ]);
            }
        }
    }
}
