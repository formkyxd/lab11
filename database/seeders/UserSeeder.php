<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Администратор
        User::create([
            'name' => 'admin',
            'gender' => 'M',
            'birth_date' => '1985-03-15',
            'email' => 'admin@gts.ru',
            'login' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        $users = [
            ['Иванов Иван',    'M', '1990-06-20', 'ivanov@gts.ru',    'ivanov'],
            ['Петрова Мария',  'F', '1995-11-05', 'petrova@gts.ru',   'petrova'],
            ['Сидоров Алексей', 'M', '1988-02-28', 'sidorov@gts.ru',   'sidorov'],
            ['Козлова Елена',  'F', '1992-09-14', 'kozlova@gts.ru',   'kozlova'],
        ];

        foreach ($users as [$name, $gender, $birth, $email, $login]) {
            User::create([
                'name' => $name,
                'gender' => $gender,
                'birth_date' => $birth,
                'email' => $email,
                'login' => $login,
                'password' => Hash::make('password123'),
                'role' => 'user',
            ]);
        }
    }
}
