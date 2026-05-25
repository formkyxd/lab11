<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Station;
use App\Models\Line;

class StationSeeder extends Seeder
{
    public function run(): void
    {
        $stationNames = [
            'Центральная площадь',
            'Вокзал',
            'Университет',
            'Парк культуры',
            'Рынок',
            'Больница',
            'Стадион',
            'Библиотека',
            'Школьная',
            'Заводская',
            'Проспект Мира',
            'Садовая',
            'Лесная',
            'Речной порт',
            'Аэропорт',
        ];

        $lines = Line::all();
        $nameIndex = 0;

        foreach ($lines as $line) {
            // Каждой линии 4-7 остановок
            $count = rand(4, 7);
            for ($pos = 1; $pos <= $count; $pos++) {
                Station::create([
                    'name'             => $stationNames[$nameIndex % count($stationNames)],
                    'position_station' => $pos,
                    'line_id'          => $line->id,
                ]);
                $nameIndex++;
            }
        }
    }
}
