<?php

namespace App\Http\Controllers;

use App\Models\Line;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends AdminController
{
    public function index()
    {
        return view('vehicle', [
            'vehicles' => Vehicle::with('line')->get(),
            'lines' => Line::all(),
            'lines_json' => Line::all(['id', 'type'])->toJson(),
        ]);
    }

    public function create()
    {
        return $this->index();
    }

    public function store(Request $request)
    {
        $request->validate([
            'Vehicle.name' => [
                'required',
                'max:30',
                'regex:/^[A-Za-zА-Яа-яЁё0-9\s\-№]+$/u',
            ],
            'Vehicle.capacity' => [
                'required',
                'integer',
                'min:1',
                'max:200',
            ],
            'Vehicle.type' => 'required|in:Tram,Bus,Nightliner',
            'line_id' => 'nullable|exists:lines,id',
        ], [
            'Vehicle.name.required' => 'Название обязательно',
            'Vehicle.name.regex' => 'Название содержит недопустимые символы',
            'Vehicle.capacity.integer' => 'Вместимость должна быть числом',
            'Vehicle.capacity.min' => 'Вместимость не может быть меньше 1',
            'Vehicle.capacity.max' => 'Вместимость не может превышать 200',
            'Vehicle.type.in' => 'Недопустимый тип транспорта',
        ]);

        if ($request->line_id) {
            $line = Line::find($request->line_id);

            $count = Vehicle::where('line_id', $request->line_id)
                ->where('type', $request->input('Vehicle.type'))
                ->count();
            if ($count >= 10) {
                return back()
                    ->withErrors(['line_id' => 'На линии может быть максимум 10 транспортных средств'])
                    ->withInput();
            }

            if ($line->type !== $request->input('Vehicle.type')) {
                return back()
                    ->withErrors(['Vehicle.type' => 'Тип транспорта должен совпадать с типом линии ('.$line->type.')'])
                    ->withInput();
            }
        }

        Vehicle::create([
            'name' => $request->input('Vehicle.name'),
            'capacity' => $request->input('Vehicle.capacity'),
            'type' => $request->input('Vehicle.type'),
            'line_id' => $request->input('line_id') ?: null,
        ]);

        return redirect()->route('vehicle')->with('success', 'Транспортное средство успешно добавлено');
    }

    public function edit(Vehicle $vehicle)
    {
        return view('vehicle', [
            'vehicle' => $vehicle,
            'vehicles' => Vehicle::with('line')->get(),
            'lines' => Line::all(),
            'lines_json' => Line::all(['id', 'type'])->toJson(),
        ]);
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'Vehicle.name' => [
                'required',
                'max:30',
                'regex:/^[A-Za-zА-Яа-яЁё0-9\s\-№]+$/u',
            ],
            'Vehicle.capacity' => [
                'required',
                'integer',
                'min:1',
                'max:200',
            ],
            'Vehicle.type' => 'required|in:Tram,Bus,Nightliner',
            'line_id' => 'nullable|exists:lines,id',
        ], [
            'Vehicle.name.regex' => 'Название содержит недопустимые символы',
            'Vehicle.capacity.integer' => 'Вместимость должна быть числом',
            'Vehicle.capacity.min' => 'Вместимость не может быть меньше 1',
            'Vehicle.capacity.max' => 'Вместимость не может превышать 200',
        ]);

        if ($request->line_id) {
            $line = Line::find($request->line_id);

            $count = Vehicle::where('line_id', $request->line_id)
                ->where('type', $request->input('Vehicle.type'))
                ->where('id', '!=', $vehicle->id)
                ->count();
            if ($count >= 10) {
                return back()
                    ->withErrors(['line_id' => 'На линии может быть максимум 10 транспортных средств'])
                    ->withInput();
            }

            if ($line->type !== $request->input('Vehicle.type')) {
                return back()
                    ->withErrors(['Vehicle.type' => 'Тип транспорта должен совпадать с типом линии ('.$line->type.')'])
                    ->withInput();
            }
        }

        $vehicle->update([
            'name' => $request->input('Vehicle.name'),
            'capacity' => $request->input('Vehicle.capacity'),
            'type' => $request->input('Vehicle.type'),
            'line_id' => $request->input('line_id') ?: null,
        ]);

        return redirect()->route('vehicle')->with('success', 'Транспортное средство обновлено');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        return redirect()->route('vehicle')->with('success', 'Транспортное средство удалено');
    }

    public function show(Vehicle $vehicle) {}
}
