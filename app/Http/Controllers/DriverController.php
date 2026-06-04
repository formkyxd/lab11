<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class DriverController extends AdminController
{
    public function index()
    {
        return view('driver', [
            'drivers' => Driver::with('vehicle')->get(),
            'vehicles' => Vehicle::whereDoesntHave('driver')->get(),
        ]);
    }

    public function create()
    {
        return $this->index();
    }

    public function store(Request $request)
    {
        $request->validate([
            'Driver.name' => [
                'required',
                'max:45',
                'regex:/^[A-Za-zА-Яа-яЁё\s\-]+$/u',
            ],
            'Driver.birth_date' => [
                'required',
                'date',
                'before:today',
                'after:1900-01-01',
            ],
            'Driver.email' => [
                'required',
                'email',
                'max:50',
                'unique:drivers,email',
            ],
            'Driver.phone' => [
                'required',
                'max:40',
                'regex:/^[\+\d\s\-\(\)]+$/',
            ],
            'vehicle_id' => 'nullable|exists:vehicles,id',
        ], [
            'Driver.name.required' => 'ФИО обязательно',
            'Driver.name.regex' => 'ФИО должно содержать только буквы, пробелы и дефисы',
            'Driver.birth_date.before' => 'Дата рождения должна быть в прошлом',
            'Driver.birth_date.after' => 'Некорректная дата рождения',
            'Driver.email.unique' => 'Водитель с таким email уже существует',
            'Driver.phone.regex' => 'Телефон содержит недопустимые символы',
        ]);

        if ($request->vehicle_id) {
            $busy = Driver::where('vehicle_id', $request->vehicle_id)->exists();
            if ($busy) {
                return back()
                    ->withErrors(['vehicle_id' => 'Это транспортное средство уже имеет водителя'])
                    ->withInput();
            }
        }

        $avatar = null;
        if ($request->hasFile('Driver.avatar')) {
            $avatar = $request->file('Driver.avatar')->store('drivers', 'public');
        }

        Driver::create([
            'name' => $request->input('Driver.name'),
            'birth_date' => $request->input('Driver.birth_date'),
            'email' => $request->input('Driver.email'),
            'phone' => $request->input('Driver.phone'),
            'avatar' => $avatar,
            'vehicle_id' => $request->input('vehicle_id') ?: null,
        ]);

        return redirect()->route('driver')->with('success', 'Водитель успешно добавлен');
    }

    public function edit(Driver $driver)
    {
        return view('driver', [
            'driver' => $driver,
            'drivers' => Driver::with('vehicle')->get(),
            'vehicles' => Vehicle::whereDoesntHave('driver')
                ->orWhere('id', $driver->vehicle_id)
                ->get(),
        ]);
    }

    public function update(Request $request, Driver $driver)
    {
        $request->validate([
            'Driver.name' => [
                'required',
                'max:45',
                'regex:/^[A-Za-zА-Яа-яЁё\s\-]+$/u',
            ],
            'Driver.birth_date' => [
                'required',
                'date',
                'before:today',
                'after:1900-01-01',
            ],
            'Driver.email' => [
                'required',
                'email',
                'max:50',
                'unique:drivers,email,'.$driver->id,
            ],
            'Driver.phone' => [
                'required',
                'max:40',
                'regex:/^[\+\d\s\-\(\)]+$/',
            ],
            'vehicle_id' => 'nullable|exists:vehicles,id',
        ], [
            'Driver.name.regex' => 'ФИО должно содержать только буквы, пробелы и дефисы',
            'Driver.birth_date.before' => 'Дата рождения должна быть в прошлом',
            'Driver.birth_date.after' => 'Некорректная дата рождения',
            'Driver.email.unique' => 'Водитель с таким email уже существует',
            'Driver.phone.regex' => 'Телефон содержит недопустимые символы',
        ]);

        if ($request->vehicle_id) {
            $busy = Driver::where('vehicle_id', $request->vehicle_id)
                ->where('id', '!=', $driver->id)
                ->exists();
            if ($busy) {
                return back()
                    ->withErrors(['vehicle_id' => 'Это транспортное средство уже имеет водителя'])
                    ->withInput();
            }
        }

        $data = [
            'name' => $request->input('Driver.name'),
            'birth_date' => $request->input('Driver.birth_date'),
            'email' => $request->input('Driver.email'),
            'phone' => $request->input('Driver.phone'),
            'vehicle_id' => $request->input('vehicle_id') ?: null,
        ];

        if ($request->hasFile('Driver.avatar')) {
            $data['avatar'] = $request->file('Driver.avatar')->store('drivers', 'public');
        }

        $driver->update($data);

        return redirect()->route('driver')->with('success', 'Водитель обновлён');
    }

    public function destroy(Driver $driver)
    {
        $driver->delete();

        return redirect()->route('driver')->with('success', 'Водитель удалён');
    }

    public function show(Driver $driver) {}
}
