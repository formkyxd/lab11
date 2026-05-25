<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Driver;
use App\Models\Vehicle;

class DriverSeeder extends Seeder
{
    public function run(): void
    {
        $firstNames = [
            'M' => ['Александр', 'Дмитрий', 'Сергей', 'Андрей', 'Михаил', 'Иван', 'Алексей', 'Николай', 'Владимир', 'Юрий'],
            'F' => ['Елена', 'Ольга', 'Татьяна', 'Наталья', 'Марина', 'Ирина', 'Светлана', 'Анна', 'Юлия', 'Екатерина'],
        ];

        $lastNames = [
            'M' => ['Иванов', 'Петров', 'Сидоров', 'Козлов', 'Новиков', 'Морозов', 'Попов', 'Лебедев', 'Соколов', 'Волков'],
            'F' => ['Иванова', 'Петрова', 'Сидорова', 'Козлова', 'Новикова', 'Морозова', 'Попова', 'Лебедева', 'Соколова', 'Волкова'],
        ];

        $patronymics = [
            'M' => ['Александрович', 'Дмитриевич', 'Сергеевич', 'Андреевич', 'Михайлович'],
            'F' => ['Александровна', 'Дмитриевна', 'Сергеевна', 'Андреевна', 'Михайловна'],
        ];

        $phones = [
            '+7 (495) 123-45-67',
            '+7 (812) 234-56-78',
            '+7 (383) 345-67-89',
            '+7 (343) 456-78-90',
            '+7 (831) 567-89-01',
            '+7 (846) 678-90-12',
            '+7 (861) 789-01-23',
            '+7 (863) 890-12-34',
            '+7 (351) 901-23-45',
            '+7 (423) 012-34-56',
        ];

        // Берём все ТС — каждому назначим водителя
        $vehicles = Vehicle::all()->shuffle();

        foreach ($vehicles as $index => $vehicle) {
            $gender = $index % 3 === 0 ? 'F' : 'M'; // каждый третий — женщина

            $lastName   = $lastNames[$gender][array_rand($lastNames[$gender])];
            $firstName  = $firstNames[$gender][array_rand($firstNames[$gender])];
            $patronymic = $patronymics[$gender][array_rand($patronymics[$gender])];

            $birthYear  = rand(1965, 1995);
            $birthMonth = rand(1, 12);
            $birthDay   = rand(1, 28);

            Driver::create([
                'name'       => "$lastName $firstName $patronymic",
                'birth_date' => sprintf('%04d-%02d-%02d', $birthYear, $birthMonth, $birthDay),
                'email'      => strtolower($lastName) . $index . '@gts.ru',
                'phone'      => $phones[$index % count($phones)],
                'avatar'     => null,
                'vehicle_id' => $vehicle->id,
            ]);
        }
    }
}
