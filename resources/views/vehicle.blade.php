<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="language" content="en" />

    <link rel="stylesheet" type="text/css" href="{{ asset('src/css/core.css') }}" media="screen, projection" />
    <link rel="stylesheet" type="text/css" href="{{ asset('src/css/principal.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('src/css/nav.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('src/css/content.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('src/css/form.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('src/css/footer.css') }}" />

    <title>Городская транспортная сеть</title>
</head>

<body>

<div class="container" id="page">

    <a href="{{ route('home') }}">
        <div id="header">
            <div id="logo"></div>
        </div>
    </a>

    <div id="mainmenu">

        <ul>

            <li>
                <a href="{{ route('line') }}" title="Line">
                    <span style="background-image: url('{{ asset('src/images/line.png') }}')"></span>
                </a>
            </li>

            <li>
                <a href="{{ route('station') }}" title="Station">
                    <span style="background-image: url('{{ asset('src/images/station.png') }}')"></span>
                </a>
            </li>

            <li>
                <a href="{{ route('vehicle') }}" title="Vehicle">
                    <span style="background-image: url('{{ asset('src/images/vehicle.png') }}')"></span>
                </a>
            </li>

            <li>
                <a href="{{ route('driver') }}" title="Driver">
                    <span style="background-image: url('{{ asset('src/images/driver.png') }}')"></span>
                </a>
            </li>

            @auth
            @if(auth()->user()->isAdmin())
            <li>
                <a href="{{ route('user') }}" title="User">
                    <span style="background-image: url('{{ asset('src/images/user.png') }}')"></span>
                </a>
            </li>
            @endif
            @endauth

        </ul>

        <div id="access">

            @auth
                <div>
                    {{ auth()->user()->name }}
                    (<a href="#"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                        Выход
                    </a>)
                </div>

                <form id="logout-form"
                      action="{{ route('logout') }}"
                      method="POST"
                      style="display:none;">
                    @csrf
                </form>
            @else
                <div>
                    <a href="{{ route('login') }}">Вход</a>
                </div>
            @endauth

        </div>

    </div>

    <div class="breadcrumbs">
        <a href="{{ route('home') }}">Главная</a> &raquo;
        <a href="#">Транспортные средства</a>
    </div>

    <div class="span-19">

        <div id="content">

            @if(session('success'))
                <div style="color:green; margin-bottom:15px;">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div style="color:red; margin-bottom:15px;">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @auth
            @if(auth()->user()->isAdmin())

                {{-- Кнопка "Добавить" справа --}}
                <div style="text-align: right; margin-bottom: 10px;">
                    <button
                        type="button"
                        id="toggle-form-btn"
                        onclick="toggleForm()">
                        + Добавить транспорт
                    </button>
                </div>

                {{-- Форма скрыта по умолчанию --}}
                <div id="vehicle-form-block" style="display:none;">

                    <h1 id="form-title">Добавить транспортное средство</h1>

                    <div class="form">

                        <form id="vehicle-form"
                              action="{{ route('vehicle.store') }}"
                              method="post">

                            @csrf

                            {{-- Скрытое поле для PUT при редактировании --}}
                            <input type="hidden" name="_method" id="form-method" value="POST">
                            {{-- id редактируемой записи --}}
                            <input type="hidden" name="vehicle_id" id="form-vehicle-id" value="">

                            <p class="note">
                                Поля <span class="required">*</span> обязательны к заполнению.
                            </p>

                            <div class="row">
                                <label for="Vehicle_name" class="required">
                                    Название <span class="required">*</span>
                                </label>

                                <input
                                    size="30"
                                    maxlength="30"
                                    name="Vehicle[name]"
                                    id="Vehicle_name"
                                    type="text"
                                    value="{{ old('Vehicle.name') }}"
                                >
                            </div>

                            <div class="row">
                                <label for="Vehicle_capacity" class="required">
                                    Вместимость <span class="required">*</span>
                                </label>

                                <input
                                    name="Vehicle[capacity]"
                                    id="Vehicle_capacity"
                                    type="number"
                                    min="1"
                                    value="{{ old('Vehicle.capacity') }}"
                                >
                            </div>

                            <div class="row">
                                <label for="Vehicle_type" class="required">
                                    Тип <span class="required">*</span>
                                </label>

                                <select name="Vehicle[type]" id="Vehicle_type">
                                    <option value="Tram"       {{ old('Vehicle.type') == 'Tram'       ? 'selected' : '' }}>Трамвай</option>
                                    <option value="Bus"        {{ old('Vehicle.type') == 'Bus'        ? 'selected' : '' }}>Автобус</option>
                                    <option value="Nightliner" {{ old('Vehicle.type') == 'Nightliner' ? 'selected' : '' }}>Маршрутное такси</option>
                                </select>
                            </div>

                            <div class="row">
                                <label for="line_id">
                                    Маршрут
                                </label>

                                <select name="line_id" id="line_id">
                                    <option value="">Не назначен</option>
                                    @foreach($lines as $line)
                                        <option
                                            value="{{ $line->id }}"
                                            {{ old('line_id') == $line->id ? 'selected' : '' }}>
                                            {{ $line->code }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="row buttons">
                                <input type="submit" id="form-submit-btn" value="Сохранить">
                            </div>

                        </form>

                    </div>

                </div>{{-- #vehicle-form-block --}}

            @endif
            @endauth

            <hr style="margin-top:30px;">

            <h2 id="list">Список транспортных средств</h2>

            <table width="100%" border="1" cellpadding="5" cellspacing="0">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Вместимость</th>
                        <th>Тип</th>
                        <th>Маршрут</th>
                        @auth
                        @if(auth()->user()->isAdmin())
                            <th>Действия</th>
                        @endif
                        @endauth
                    </tr>
                </thead>

                <tbody>

                @forelse($vehicles as $vehicle)

                    <tr>

                        <td>{{ $vehicle->id }}</td>

                        <td>{{ $vehicle->name }}</td>

                        <td>{{ $vehicle->capacity }}</td>

                        <td>
                            @php
                                $types = [
                                    'Tram'       => 'Трамвай',
                                    'Bus'        => 'Автобус',
                                    'Nightliner' => 'Маршрутное такси',
                                ];
                            @endphp
                            {{ $types[$vehicle->type] ?? $vehicle->type }}
                        </td>

                        <td>{{ $vehicle->line?->code ?? 'Не назначен' }}</td>

                        <td>
                            @auth
                            @if(auth()->user()->isAdmin())

                                <button
                                    type="button"
                                    onclick="openEditForm(
                                        {{ $vehicle->id }},
                                        '{{ addslashes($vehicle->name) }}',
                                        '{{ $vehicle->capacity }}',
                                        '{{ $vehicle->type }}',
                                        '{{ $vehicle->line_id ?? '' }}'
                                    )">
                                    Изменить
                                </button>

                                |

                                <form
                                    action="{{ route('vehicles.destroy', $vehicle) }}"
                                    method="POST"
                                    style="display:inline">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        onclick="return confirm('Удалить транспортное средство?')">
                                        Удалить
                                    </button>

                                </form>

                            @endif
                            @endauth
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6">Транспортные средства отсутствуют</td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>{{-- #content --}}

    </div>{{-- .span-19 --}}

    <div class="clear"></div>

    <div id="footer">
        <ul>
            <li class="sitemap">
                <a href="#">Карта сайта</a>
            </li>

            <li class="copyr">
                <a href="#">
                    Copyright &copy; 2018 ГТС
                </a>
            </li>

            <li class="allright">
                <a href="#">
                    Все права сохранены <br>
                    ГТС
                </a>
            </li>
        </ul>
    </div>

</div>

<script>
    var storeUrl  = '{{ route('vehicle.store') }}';
    var updateUrl = '{{ url('vehicles') }}';

    function resetForm() {
        document.getElementById('form-title').textContent   = 'Добавить транспортное средство';
        document.getElementById('vehicle-form').action      = storeUrl;
        document.getElementById('form-method').value        = 'POST';
        document.getElementById('form-vehicle-id').value    = '';
        document.getElementById('Vehicle_name').value       = '';
        document.getElementById('Vehicle_capacity').value   = '';
        document.getElementById('Vehicle_type').value       = 'Tram';
        document.getElementById('line_id').value            = '';
        document.getElementById('form-submit-btn').value    = 'Сохранить';
    }

    function toggleForm() {
        var block = document.getElementById('vehicle-form-block');
        var btn   = document.getElementById('toggle-form-btn');
        if (block.style.display === 'none') {
            resetForm();
            block.style.display = 'block';
            btn.textContent = 'Отмена';
            block.scrollIntoView({ behavior: 'smooth' });
        } else {
            block.style.display = 'none';
            btn.textContent = '+ Добавить транспорт';
        }
    }

    function openEditForm(id, name, capacity, type, lineId) {
        var block = document.getElementById('vehicle-form-block');
        var btn   = document.getElementById('toggle-form-btn');

        document.getElementById('form-title').textContent   = 'Редактировать транспортное средство';
        document.getElementById('vehicle-form').action      = updateUrl + '/' + id;
        document.getElementById('form-method').value        = 'PUT';
        document.getElementById('form-vehicle-id').value    = id;
        document.getElementById('Vehicle_name').value       = name;
        document.getElementById('Vehicle_capacity').value   = capacity;
        document.getElementById('Vehicle_type').value       = type;
        document.getElementById('line_id').value            = lineId;
        document.getElementById('form-submit-btn').value    = 'Обновить';

        block.style.display = 'block';
        if (btn) { btn.textContent = 'Отмена'; }
        block.scrollIntoView({ behavior: 'smooth' });
    }

    // var linesData = {!! $lines_json ?? '[]' !!};

    // function syncTypeWithLine(lineId) {
    //     if (!lineId) return;
    //     var line = linesData.find(function(l) { return l.id == lineId; });
    //     if (line) {
    //         document.getElementById('Vehicle_type').value = line.type;
    //     }
    // }

    @if($errors->any())
    document.addEventListener('DOMContentLoaded', function () {
        var block = document.getElementById('vehicle-form-block');
        var btn   = document.getElementById('toggle-form-btn');
        if (block) { block.style.display = 'block'; }
        if (btn)   { btn.textContent = 'Отмена'; }
    });
    @endif
</script>

</body>
</html>