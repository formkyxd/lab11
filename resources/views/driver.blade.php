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
        <a href="#">Водители</a>
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
                        + Добавить водителя
                    </button>
                </div>

                {{-- Форма скрыта по умолчанию --}}
                <div id="driver-form-block" style="display:none;">

                    <h1 id="form-title">Добавить водителя</h1>

                    <div class="form">

                        <form id="driver-form"
                              enctype="multipart/form-data"
                              action="{{ route('driver.store') }}"
                              method="post">

                            @csrf

                            {{-- Скрытое поле для PUT при редактировании --}}
                            <input type="hidden" name="_method" id="form-method" value="POST">
                            {{-- id редактируемой записи --}}
                            <input type="hidden" name="driver_id" id="form-driver-id" value="">

                            <p class="note">
                                Поля <span class="required">*</span> обязательны к заполнению.
                            </p>

                            <div class="row">
                                <label for="Driver_name" class="required">
                                    ФИО <span class="required">*</span>
                                </label>

                                <input
                                    size="45"
                                    maxlength="45"
                                    name="Driver[name]"
                                    id="Driver_name"
                                    type="text"
                                    value="{{ old('Driver.name') }}"
                                >
                            </div>

                            <div class="row">
                                <label for="Driver_birth_date" class="required">
                                    Дата рождения <span class="required">*</span>
                                </label>

                                <input
                                    id="Driver_birth_date"
                                    name="Driver[birth_date]"
                                    type="date"
                                    value="{{ old('Driver.birth_date') }}"
                                >
                            </div>

                            <div class="row">
                                <label for="Driver_email" class="required">
                                    Email <span class="required">*</span>
                                </label>

                                <input
                                    size="50"
                                    maxlength="50"
                                    name="Driver[email]"
                                    id="Driver_email"
                                    type="text"
                                    value="{{ old('Driver.email') }}"
                                >
                            </div>

                            <div class="row">
                                <label for="Driver_phone" class="required">
                                    Телефон <span class="required">*</span>
                                </label>

                                <input
                                    size="40"
                                    maxlength="40"
                                    name="Driver[phone]"
                                    id="Driver_phone"
                                    type="text"
                                    value="{{ old('Driver.phone') }}"
                                >
                            </div>

                            <div class="row">
                                <label for="Driver_avatar">
                                    Фото
                                </label>

                                <input type="hidden" value="" name="Driver[avatar]">
                                <input
                                    name="Driver[avatar]"
                                    id="Driver_avatar"
                                    type="file"
                                >
                            </div>

                            <div class="row">
                                <label for="vehicle_id">
                                    Транспортное средство
                                </label>

                                <select name="vehicle_id" id="vehicle_id">

                                    <option value="">Не назначен</option>

                                    @foreach($vehicles as $vehicle)
                                        <option
                                            value="{{ $vehicle->id }}"
                                            {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                            {{ $vehicle->number ?? $vehicle->id }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            <div class="row buttons">
                                <input type="submit" id="form-submit-btn" value="Сохранить">
                            </div>

                        </form>

                    </div>

                </div>{{-- #driver-form-block --}}

            @endif
            @endauth

            <hr style="margin-top:30px;">

            <h2 id="list">Список водителей</h2>

            <table width="100%" border="1" cellpadding="5" cellspacing="0">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ФИО</th>
                        <th>Дата рождения</th>
                        <th>Email</th>
                        <th>Телефон</th>
                        <th>Транспорт</th>
                        @auth
                        @if(auth()->user()->isAdmin())
                            <th>Действия</th>
                        @endif
                        @endauth
                    </tr>
                </thead>

                <tbody>

                @forelse($drivers as $driver)

                    <tr>

                        <td>{{ $driver->id }}</td>

                        <td>{{ $driver->name }}</td>

                        <td>{{ $driver->birth_date }}</td>

                        <td>{{ $driver->email }}</td>

                        <td>{{ $driver->phone }}</td>

                        <td>{{ $driver->vehicle?->name ?? 'Не назначен' }}</td>

                        <td>
                            @auth
                            @if(auth()->user()->isAdmin())

                                <button
                                    type="button"
                                    onclick="openEditForm(
                                        {{ $driver->id }},
                                        '{{ addslashes($driver->name) }}',
                                        '{{ $driver->birth_date }}',
                                        '{{ addslashes($driver->email) }}',
                                        '{{ addslashes($driver->phone) }}',
                                        '{{ $driver->vehicle_id ?? '' }}'
                                    )">
                                    Изменить
                                </button>

                                |

                                <form
                                    action="{{ route('drivers.destroy', $driver) }}"
                                    method="POST"
                                    style="display:inline">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        onclick="return confirm('Удалить водителя?')">
                                        Удалить
                                    </button>

                                </form>

                            @endif
                            @endauth
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7">Водители отсутствуют</td>
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
    var storeUrl  = '{{ route('driver.store') }}';
    var updateUrl = '{{ url('drivers') }}'; // будет дополнен /{id}

    function resetForm() {
        document.getElementById('form-title').textContent  = 'Добавить водителя';
        document.getElementById('driver-form').action      = storeUrl;
        document.getElementById('form-method').value       = 'POST';
        document.getElementById('form-driver-id').value    = '';
        document.getElementById('Driver_name').value       = '';
        document.getElementById('Driver_birth_date').value = '';
        document.getElementById('Driver_email').value      = '';
        document.getElementById('Driver_phone').value      = '';
        document.getElementById('vehicle_id').value        = '';
        document.getElementById('form-submit-btn').value   = 'Сохранить';
    }

    function toggleForm() {
        var block = document.getElementById('driver-form-block');
        var btn   = document.getElementById('toggle-form-btn');
        if (block.style.display === 'none') {
            resetForm();
            block.style.display = 'block';
            btn.textContent = 'Отмена';
            block.scrollIntoView({ behavior: 'smooth' });
        } else {
            block.style.display = 'none';
            btn.textContent = '+ Добавить водителя';
        }
    }

    function openEditForm(id, name, birthDate, email, phone, vehicleId) {
        var block = document.getElementById('driver-form-block');
        var btn   = document.getElementById('toggle-form-btn');

        document.getElementById('form-title').textContent  = 'Редактировать водителя';
        document.getElementById('driver-form').action      = updateUrl + '/' + id;
        document.getElementById('form-method').value       = 'PUT';
        document.getElementById('form-driver-id').value    = id;
        document.getElementById('Driver_name').value       = name;
        document.getElementById('Driver_birth_date').value = birthDate;
        document.getElementById('Driver_email').value      = email;
        document.getElementById('Driver_phone').value      = phone;
        document.getElementById('vehicle_id').value        = vehicleId;
        document.getElementById('form-submit-btn').value   = 'Обновить';

        block.style.display = 'block';
        if (btn) { btn.textContent = 'Отмена'; }
        block.scrollIntoView({ behavior: 'smooth' });
    }

    // Если вернулись ошибки валидации — держим форму открытой
    @if($errors->any())
    document.addEventListener('DOMContentLoaded', function () {
        var block = document.getElementById('driver-form-block');
        var btn   = document.getElementById('toggle-form-btn');
        if (block) { block.style.display = 'block'; }
        if (btn)   { btn.textContent = 'Отмена'; }
    });
    @endif
</script>

</body>
</html>
