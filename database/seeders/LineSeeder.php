<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Line;

class LineSeeder extends Seeder
{
    public function run(): void
    {
        $lines = [
            ['Т-1',  '06:00:00', '23:00:00', 'Tram'],
            ['Т-2',  '05:30:00', '22:30:00', 'Tram'],
            ['Т-3',  '07:00:00', '21:00:00', 'Tram'],
            ['А-10', '06:00:00', '23:00:00', 'Bus'],
            ['А-15', '05:00:00', '00:00:00', 'Bus'],
            ['А-22', '07:30:00', '22:00:00', 'Bus'],
            ['М-5',  '06:00:00', '21:00:00', 'Nightliner'],
            ['М-8',  '07:00:00', '22:00:00', 'Nightliner'],
        ];

        foreach ($lines as [$code, $start, $end, $type]) {
            Line::create([
                'code'                 => $code,
                'start_time_operation' => $start,
                'end_time_operation'   => $end,
                'type'                 => $type,
                'map'                  => '',
            ]);
        }
    }
}