<?php
namespace App\Http\Controllers;

use App\Models\Station;
use App\Models\Line;
use Illuminate\Http\Request;

class StationController extends Controller
{
    public function index()
    {
        $lines    = Line::all();
        $stations = Station::with('line')->get();
        return view('station', compact('lines', 'stations'));
    }

    public function create()
    {
        $lines    = Line::all();
        $stations = Station::with('line')->get();
        return view('station', compact('lines', 'stations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Station.name'     => [
                'required',
                'max:80',
                'regex:/^[A-Za-zА-Яа-яЁё0-9\s\-\.]+$/u',
            ],
            'position_station' => [
                'required',
                'integer',
                'min:1',
                'max:7',
            ],
            'line_id'          => 'nullable|exists:lines,id',
        ], [
            'Station.name.required'    => 'Название остановки обязательно',
            'Station.name.regex'       => 'Название содержит недопустимые символы',
            'position_station.integer' => 'Позиция должна быть числом',
            'position_station.min'     => 'Позиция не может быть меньше 1',
            'position_station.max'     => 'Позиция не может быть больше 7',
        ]);

        // Максимум 7 остановок на линию
        if ($request->line_id) {
            $count = Station::where('line_id', $request->line_id)->count();
            if ($count >= 7) {
                return back()
                    ->withErrors(['line_id' => 'У линии может быть максимум 7 остановок'])
                    ->withInput();
            }

            // Позиция должна быть уникальной в рамках линии
            $posExists = Station::where('line_id', $request->line_id)
                ->where('position_station', $request->position_station)
                ->exists();
            if ($posExists) {
                return back()
                    ->withErrors(['position_station' => 'Эта позиция уже занята на данной линии'])
                    ->withInput();
            }
        }

        Station::create([
            'name'             => $request->input('Station.name'),
            'position_station' => $request->position_station,
            'line_id'          => $request->line_id ?: null,
        ]);

        return redirect()->route('station')->with('success', 'Остановочный пункт успешно добавлен');
    }

    public function edit(Station $station)
    {
        $lines    = Line::all();
        $stations = Station::with('line')->get();
        return view('station', compact('station', 'lines', 'stations'));
    }

    public function update(Request $request, Station $station)
    {
        $request->validate([
            'Station.name'     => [
                'required',
                'max:80',
                'regex:/^[A-Za-zА-Яа-яЁё0-9\s\-\.]+$/u',
            ],
            'position_station' => [
                'required',
                'integer',
                'min:1',
                'max:7',
            ],
            'line_id'          => 'nullable|exists:lines,id',
        ], [
            'Station.name.regex'       => 'Название содержит недопустимые символы',
            'position_station.integer' => 'Позиция должна быть числом',
            'position_station.min'     => 'Позиция не может быть меньше 1',
            'position_station.max'     => 'Позиция не может быть больше 7',
        ]);

        // Максимум 7 остановок на линию (исключая текущую)
        if ($request->line_id) {
            $count = Station::where('line_id', $request->line_id)
                ->where('id', '!=', $station->id)
                ->count();
            if ($count >= 7) {
                return back()
                    ->withErrors(['line_id' => 'У линии может быть максимум 7 остановок'])
                    ->withInput();
            }

            // Позиция уникальна в рамках линии (исключая текущую)
            $posExists = Station::where('line_id', $request->line_id)
                ->where('position_station', $request->position_station)
                ->where('id', '!=', $station->id)
                ->exists();
            if ($posExists) {
                return back()
                    ->withErrors(['position_station' => 'Эта позиция уже занята на данной линии'])
                    ->withInput();
            }
        }

        $station->update([
            'name'             => $request->input('Station.name'),
            'position_station' => $request->position_station,
            'line_id'          => $request->line_id ?: null,
        ]);

        return redirect()->route('station')->with('success', 'Остановочный пункт обновлён');
    }

    public function destroy(Station $station)
    {
        $station->delete();
        return redirect()->route('station')->with('success', 'Остановочный пункт удалён');
    }

    public function list()
    {
        $stations = Station::all();
        return view('station-list', compact('stations'));
    }

    public function show(Station $station) {}
}