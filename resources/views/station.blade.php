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
        <a href="#">Остановочный пункт</a>
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

                {{-- Кнопка "Добавить" справа над списком --}}
                <div style="text-align: right; margin-bottom: 10px;">
                    @if(!isset($station))
                        <button
                            type="button"
                            id="toggle-form-btn"
                            onclick="toggleForm()">
                            + Добавить остановку
                        </button>
                    @endif
                </div>

                {{-- Форма скрыта по умолчанию --}}
                <div id="station-form-block" style="display:none;">

                <h1 id="form-title">Добавить остановочный пункт</h1>

                <div class="form">

                    <form id="station-form"
                        action="{{ route('station.store') }}"
                        method="post">

                        @csrf

                        {{-- Скрытое поле для PUT при редактировании --}}
                        <input type="hidden" name="_method" id="form-method" value="POST">
                        {{-- id редактируемой записи --}}
                        <input type="hidden" name="station_id" id="form-station-id" value="">

                        <p class="note">
                            Поля
                            <span class="required">*</span>
                            обязательны к заполнению.
                        </p>

                        <div class="row">
                            <label for="Station_name" class="required">
                                Название
                                <span class="required">*</span>
                            </label>

                            <input
                                size="60"
                                maxlength="80"
                                name="Station[name]"
                                id="Station_name"
                                type="text"
                                value="{{ old('Station.name', $station->name ?? '') }}"
                            >
                        </div>

                        <div class="row">
                            <label for="position_station" class="required">
                                Позиция на маршруте
                                <span class="required">*</span>
                            </label>

                            {{-- ИСПРАВЛЕНО: добавлен закрывающий > у input --}}
                            <input
                                type="text"
                                name="position_station"
                                id="position_station"
                                value="{{ old('position_station', $station->position_station ?? '') }}"
                            >
                        </div>

                        <div class="row">
                            <label for="line_id">
                                Маршрут
                            </label>

                            <select name="line_id" id="line_id">

                                <option value="">
                                    Не выбран
                                </option>

                                @foreach($lines as $line)

                                    <option
                                        value="{{ $line->id }}"
                                        {{
                                            old(
                                                'line_id',
                                                $station->line_id ?? ''
                                            ) == $line->id
                                                ? 'selected'
                                                : ''
                                        }}>

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

                </div>{{-- #station-form-block --}}

            @endif
            @endauth

            <hr style="margin-top:30px;">

            <h2 id="list">
                Список остановочных пунктов
            </h2>

            <table width="100%" border="1" cellpadding="5" cellspacing="0">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Позиция</th>
                        <th>Маршрут</th>
                        @auth
                        @if(auth()->user()->isAdmin())
                            <th>Действия</th>
                        @endif
                        @endauth
                    </tr>
                </thead>

                <tbody>

                @forelse($stations as $station)

                    <tr>

                        <td>{{ $station->id }}</td>

                        <td>{{ $station->name }}</td>

                        <td>{{ $station->position_station }}</td>

                        <td>
                            {{ $station->line?->code ?? 'Не назначен' }}
                        </td>

                        <td>
                            @auth
                            @if(auth()->user()->isAdmin())

                                <button
                                    type="button"
                                    onclick="openEditForm(
                                        {{ $station->id }},
                                        '{{ addslashes($station->name) }}',
                                        '{{ $station->position_station }}',
                                        '{{ $station->line_id ?? '' }}'
                                    )">
                                    Изменить
                                </button>

                                |

                                <form
                                    action="{{ route('stations.destroy', $station) }}"
                                    method="POST"
                                    style="display:inline">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        onclick="return confirm('Удалить остановку?')">
                                        Удалить
                                    </button>

                                </form>

                            @endif
                            @endauth
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5">
                            Остановочные пункты отсутствуют
                        </td>
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
    var storeUrl  = '{{ route('station.store') }}';
    var updateUrl = '{{ url('stations') }}'; // будет дополнен /{id}

    function resetForm() {
        document.getElementById('form-title').textContent    = 'Добавить остановочный пункт';
        document.getElementById('station-form').action       = storeUrl;
        document.getElementById('form-method').value         = 'POST';
        document.getElementById('form-station-id').value     = '';
        document.getElementById('Station_name').value        = '';
        document.getElementById('position_station').value    = '';
        document.getElementById('line_id').value             = '';
        document.getElementById('form-submit-btn').value     = 'Сохранить';
    }

    function toggleForm() {
        var block = document.getElementById('station-form-block');
        var btn   = document.getElementById('toggle-form-btn');
        if (block.style.display === 'none') {
            resetForm();
            block.style.display = 'block';
            btn.textContent = 'Отмена';
            block.scrollIntoView({ behavior: 'smooth' });
        } else {
            block.style.display = 'none';
            btn.textContent = '+ Добавить остановку';
        }
    }

    function openEditForm(id, name, position, lineId) {
        var block = document.getElementById('station-form-block');
        var btn   = document.getElementById('toggle-form-btn');

        document.getElementById('form-title').textContent    = 'Редактировать остановочный пункт';
        document.getElementById('station-form').action       = updateUrl + '/' + id;
        document.getElementById('form-method').value         = 'PUT';
        document.getElementById('form-station-id').value     = id;
        document.getElementById('Station_name').value        = name;
        document.getElementById('position_station').value    = position;
        document.getElementById('line_id').value             = lineId;
        document.getElementById('form-submit-btn').value     = 'Обновить';

        block.style.display = 'block';
        if (btn) { btn.textContent = 'Отмена'; }
        block.scrollIntoView({ behavior: 'smooth' });
    }

    // Если вернулись ошибки валидации — держим форму открытой
    @if($errors->any())
    document.addEventListener('DOMContentLoaded', function () {
        var block = document.getElementById('station-form-block');
        var btn   = document.getElementById('toggle-form-btn');
        if (block) { block.style.display = 'block'; }
        if (btn)   { btn.textContent = 'Отмена'; }
    });
    @endif
</script>

</body>
</html>