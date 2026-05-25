<?php
namespace App\Http\Controllers;

use App\Models\Line;
use Illuminate\Http\Request;

class LineController extends Controller
{
    public function index()
    {
        return view('line', [
            'lines' => Line::all(),
        ]);
    }

    public function create()
    {
        return view('line', [
            'lines' => Line::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'Line.code'                 => [
                'required',
                'max:50',
                'unique:lines,code',
                'regex:/^[A-Za-zА-Яа-яЁё0-9\s\-]+$/u',
            ],
            'Line.start_time_operation' => 'required',
            'Line.end_time_operation'   => [
                'required',
                'different:Line.start_time_operation',
            ],
            'Line.type' => 'required|in:Tram,Bus,Nightliner',
        ], [
            'Line.code.required'        => 'Код маршрута обязателен',
            'Line.code.unique'          => 'Маршрут с таким кодом уже существует',
            'Line.code.regex'           => 'Код маршрута содержит недопустимые символы',
            'Line.end_time_operation.different' => 'Время окончания должно отличаться от времени начала',
            'Line.type.in'              => 'Недопустимый тип транспорта',
        ]);

        $map = null;
        if ($request->hasFile('Line.map')) {
            $map = $request->file('Line.map')->store('maps', 'public');
        }

        Line::create([
            'code'                 => $request->input('Line.code'),
            'start_time_operation' => $request->input('Line.start_time_operation'),
            'end_time_operation'   => $request->input('Line.end_time_operation'),
            'type'                 => $request->input('Line.type'),
            'map'                  => $map,
        ]);

        return redirect()->route('line')->with('success', 'Маршрут успешно добавлен');
    }

    public function edit(Line $line)
    {
        return view('line', [
            'line'  => $line,
            'lines' => Line::all(),
        ]);
    }

    public function update(Request $request, Line $line)
    {
        $request->validate([
            'Line.code' => [
                'required',
                'max:50',
                'unique:lines,code,' . $line->id,
                'regex:/^[A-Za-zА-Яа-яЁё0-9\s\-]+$/u',
            ],
            'Line.start_time_operation' => 'required',
            'Line.end_time_operation'   => [
                'required',
                'different:Line.start_time_operation',
            ],
            'Line.type' => 'required|in:Tram,Bus,Nightliner',
        ], [
            'Line.code.unique'          => 'Маршрут с таким кодом уже существует',
            'Line.code.regex'           => 'Код маршрута содержит недопустимые символы',
            'Line.end_time_operation.different' => 'Время окончания должно отличаться от времени начала',
        ]);

        $data = [
            'code'                 => $request->input('Line.code'),
            'start_time_operation' => $request->input('Line.start_time_operation'),
            'end_time_operation'   => $request->input('Line.end_time_operation'),
            'type'                 => $request->input('Line.type'),
        ];

        if ($request->hasFile('Line.map')) {
            $data['map'] = $request->file('Line.map')->store('maps', 'public');
        }

        $line->update($data);

        return redirect()->route('line')->with('success', 'Маршрут обновлён');
    }

    public function destroy(Line $line)
    {
        // При удалении линии — отвязываем станции и транспорт (не удаляем)
        $line->stations()->update(['line_id' => null]);
        $line->vehicles()->update(['line_id' => null]);

        $line->delete();

        return redirect()->route('line')->with('success', 'Маршрут удалён');
    }

    public function show(Line $line) {}
}