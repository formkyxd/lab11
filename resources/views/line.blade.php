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
                    |
                    <a href="{{ route('register') }}">Регистрация</a>
                </div>
            @endauth

        </div>

    </div>

    <div class="breadcrumbs">
        <a href="{{ route('home') }}">Главная</a> &raquo;
        <a href="#">Маршруты</a>
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
                        + Добавить маршрут
                    </button>
                </div>

                {{-- Форма скрыта по умолчанию --}}
                <div id="line-form-block" style="display:none;">

                    <h1 id="form-title">Добавить маршрут</h1>

                    <div class="form">

                        <form id="line-form"
                              enctype="multipart/form-data"
                              action="{{ route('line.store') }}"
                              method="post">

                            @csrf

                            {{-- Скрытое поле для PUT при редактировании --}}
                            <input type="hidden" name="_method" id="form-method" value="POST">
                            {{-- id редактируемой записи --}}
                            <input type="hidden" name="line_id" id="form-line-id" value="">

                            <p class="note">
                                Поля <span class="required">*</span> обязательны к заполнению.
                            </p>

                            <div class="row">
                                <label for="Line_code" class="required">
                                    Код маршрута <span class="required">*</span>
                                </label>

                                <input
                                    size="50"
                                    maxlength="50"
                                    name="Line[code]"
                                    id="Line_code"
                                    type="text"
                                    value="{{ old('Line.code') }}"
                                >
                            </div>

                            <div class="row">
                                <label for="start_time_operation" class="required">
                                    Начало работы <span class="required">*</span>
                                </label>

                                <select name="Line[start_time_operation]" id="start_time_operation">
                                    @for($h = 0; $h < 24; $h++)
                                        @php $val = sprintf('%02d:00:00', $h); @endphp
                                        <option
                                            value="{{ $val }}"
                                            {{ old('Line.start_time_operation') == $val ? 'selected' : '' }}>
                                            {{ $val }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div class="row">
                                <label for="end_time_operation" class="required">
                                    Окончание работы <span class="required">*</span>
                                </label>

                                <select name="Line[end_time_operation]" id="end_time_operation">
                                    @for($h = 0; $h < 24; $h++)
                                        @php $val = sprintf('%02d:00:00', $h); @endphp
                                        <option
                                            value="{{ $val }}"
                                            {{ old('Line.end_time_operation') == $val ? 'selected' : '' }}>
                                            {{ $val }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div class="row">
                                <label for="Line_type" class="required">
                                    Тип транспорта <span class="required">*</span>
                                </label>

                                <select name="Line[type]" id="Line_type">
                                    <option value="Tram"      {{ old('Line.type') == 'Tram'      ? 'selected' : '' }}>Трамвай</option>
                                    <option value="Bus"       {{ old('Line.type') == 'Bus'       ? 'selected' : '' }}>Автобус</option>
                                    <option value="Nightliner"{{ old('Line.type') == 'Nightliner'? 'selected' : '' }}>Маршрутное такси</option>
                                </select>
                            </div>

                            <div class="row">
                                <label for="Line_map">
                                    Карта маршрута
                                </label>

                                <input type="hidden" value="" name="Line[map]">
                                <input
                                    name="Line[map]"
                                    id="Line_map"
                                    type="file"
                                >
                            </div>

                            <div class="row buttons">
                                <input type="submit" id="form-submit-btn" value="Сохранить">
                            </div>

                        </form>

                    </div>

                </div>{{-- #line-form-block --}}

            @endif
            @endauth

            <hr style="margin-top:30px;">

            <h2 id="list">Список маршрутов</h2>

            <table width="100%" border="1" cellpadding="5" cellspacing="0">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Код</th>
                        <th>Начало работы</th>
                        <th>Окончание работы</th>
                        <th>Тип транспорта</th>
                        @auth
                        @if(auth()->user()->isAdmin())
                            <th>Действия</th>
                        @endif
                        @endauth
                    </tr>
                </thead>

                <tbody>

                @forelse($lines as $line)

                    <tr>

                        <td>{{ $line->id }}</td>

                        <td>{{ $line->code }}</td>

                        <td>{{ $line->start_time_operation }}</td>

                        <td>{{ $line->end_time_operation }}</td>

                        <td>
                            @php
                                $types = [
                                    'Tram'       => 'Трамвай',
                                    'Bus'        => 'Автобус',
                                    'Nightliner' => 'Маршрутное такси',
                                ];
                            @endphp
                            {{ $types[$line->type] ?? $line->type }}
                        </td>

                        <td>
                            @auth
                            @if(auth()->user()->isAdmin())

                                <button
                                    type="button"
                                    onclick="openEditForm(
                                        {{ $line->id }},
                                        '{{ addslashes($line->code) }}',
                                        '{{ $line->start_time_operation }}',
                                        '{{ $line->end_time_operation }}',
                                        '{{ $line->type }}'
                                    )">
                                    Изменить
                                </button>

                                |

                                <form
                                    action="{{ route('lines.destroy', $line) }}"
                                    method="POST"
                                    style="display:inline">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        onclick="return confirm('Удалить маршрут?')">
                                        Удалить
                                    </button>

                                </form>

                            @endif
                            @endauth
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6">Маршруты отсутствуют</td>
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
    var storeUrl  = '{{ route('line.store') }}';
    var updateUrl = '{{ url('lines') }}'; // будет дополнен /{id}

    function resetForm() {
        document.getElementById('form-title').textContent       = 'Добавить маршрут';
        document.getElementById('line-form').action             = storeUrl;
        document.getElementById('form-method').value            = 'POST';
        document.getElementById('form-line-id').value           = '';
        document.getElementById('Line_code').value              = '';
        document.getElementById('start_time_operation').value   = '00:00:00';
        document.getElementById('end_time_operation').value     = '00:00:00';
        document.getElementById('Line_type').value              = 'Tram';
        document.getElementById('form-submit-btn').value        = 'Сохранить';
    }

    function toggleForm() {
        var block = document.getElementById('line-form-block');
        var btn   = document.getElementById('toggle-form-btn');
        if (block.style.display === 'none') {
            resetForm();
            block.style.display = 'block';
            btn.textContent = 'Отмена';
            block.scrollIntoView({ behavior: 'smooth' });
        } else {
            block.style.display = 'none';
            btn.textContent = '+ Добавить маршрут';
        }
    }

    function openEditForm(id, code, startTime, endTime, type) {
        var block = document.getElementById('line-form-block');
        var btn   = document.getElementById('toggle-form-btn');

        document.getElementById('form-title').textContent       = 'Редактировать маршрут';
        document.getElementById('line-form').action             = updateUrl + '/' + id;
        document.getElementById('form-method').value            = 'PUT';
        document.getElementById('form-line-id').value           = id;
        document.getElementById('Line_code').value              = code;
        document.getElementById('start_time_operation').value   = startTime;
        document.getElementById('end_time_operation').value     = endTime;
        document.getElementById('Line_type').value              = type;
        document.getElementById('form-submit-btn').value        = 'Обновить';

        block.style.display = 'block';
        if (btn) { btn.textContent = 'Отмена'; }
        block.scrollIntoView({ behavior: 'smooth' });
    }

    // Если вернулись ошибки валидации — держим форму открытой
    @if($errors->any())
    document.addEventListener('DOMContentLoaded', function () {
        var block = document.getElementById('line-form-block');
        var btn   = document.getElementById('toggle-form-btn');
        if (block) { block.style.display = 'block'; }
        if (btn)   { btn.textContent = 'Отмена'; }
    });
    @endif
</script>

</body>
</html>