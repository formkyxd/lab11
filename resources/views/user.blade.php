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
        <a href="#">Пользователи</a>
    </div>

    <div class="span-19">

        <div id="content">

            @auth
            @if(auth()->user()->isAdmin())

                {{-- Кнопка "Добавить" справа --}}
                <div style="text-align: right; margin-bottom: 10px;">
                    <button
                        type="button"
                        id="toggle-form-btn"
                        onclick="toggleForm()">
                        + Добавить пользователя
                    </button>
                </div>

                {{-- Форма скрыта по умолчанию --}}
                <div id="user-form-block" style="display:none;">

                    <h1 id="form-title">Добавить пользователя</h1>

                    <div class="form">

                        <form id="user-form"
                              action="{{ route('user.store') }}"
                              method="post">

                            @csrf

                            {{-- Скрытое поле для PUT при редактировании --}}
                            <input type="hidden" name="_method" id="form-method" value="POST">
                            {{-- id редактируемой записи --}}
                            <input type="hidden" name="user_id" id="form-user-id" value="">

                            <p class="note">
                                Поля <span class="required">*</span> обязательны к заполнению.
                            </p>

                            @if($errors->any())
                                <div style="color:red; margin-bottom:15px;">
                                    <ul>
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="row">
                                <label for="User_name" class="required">
                                    Имя <span class="required">*</span>
                                </label>

                                <input
                                    size="50"
                                    maxlength="50"
                                    name="User[name]"
                                    id="User_name"
                                    type="text"
                                    value="{{ old('User.name') }}"
                                >
                            </div>

                            <div class="row">
                                <label for="User_gender">
                                    Пол
                                </label>

                                <select name="User[gender]" id="User_gender">
                                    <option value=""  {{ old('User.gender') == ''  ? 'selected' : '' }}>Не указан</option>
                                    <option value="M" {{ old('User.gender') == 'M' ? 'selected' : '' }}>Мужской</option>
                                    <option value="F" {{ old('User.gender') == 'F' ? 'selected' : '' }}>Женский</option>
                                </select>
                            </div>

                            <div class="row">
                                <label for="User_birth_date">
                                    Дата рождения
                                </label>

                                <input
                                    name="User[birth_date]"
                                    id="User_birth_date"
                                    type="date"
                                    value="{{ old('User.birth_date') }}"
                                >
                            </div>

                            <div class="row">
                                <label for="User_email" class="required">
                                    Email <span class="required">*</span>
                                </label>

                                <input
                                    size="50"
                                    maxlength="50"
                                    name="User[email]"
                                    id="User_email"
                                    type="email"
                                    value="{{ old('User.email') }}"
                                >
                            </div>

                            <div class="row">
                                <label for="User_login" class="required">
                                    Логин <span class="required">*</span>
                                </label>

                                <input
                                    size="40"
                                    maxlength="40"
                                    name="User[login]"
                                    id="User_login"
                                    type="text"
                                    value="{{ old('User.login') }}"
                                >
                            </div>

                            <div class="row">
                                <label for="User_password" class="required">
                                    Пароль <span class="required">*</span>
                                </label>

                                <input
                                    size="50"
                                    maxlength="50"
                                    name="User[password]"
                                    id="User_password"
                                    type="password"
                                >
                            </div>

                            <div class="row">
                                <label for="User_role" class="required">
                                    Роль <span class="required">*</span>
                                </label>

                                <select name="User[role]" id="User_role">
                                    <option value="user"  {{ old('User.role') == 'user'  ? 'selected' : '' }}>user</option>
                                    <option value="admin" {{ old('User.role') == 'admin' ? 'selected' : '' }}>admin</option>
                                </select>
                            </div>

                            <div class="row buttons">
                                <input type="submit" id="form-submit-btn" value="Сохранить">
                            </div>

                        </form>

                    </div>

                </div>{{-- #user-form-block --}}

            @endif
            @endauth

            {{-- Сообщение об успехе — снаружи формы --}}
            @if(session('success'))
                <div style="color:green; margin-bottom:15px; margin-top:15px;">
                    {{ session('success') }}
                </div>
            @endif

            <hr style="margin-top:30px;">

            <h2 id="list">Список пользователей</h2>

            <table width="100%" border="1" cellpadding="5" cellspacing="0">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Пол</th>
                        <th>Дата рождения</th>
                        <th>Email</th>
                        <th>Логин</th>
                        <th>Роль</th>
                        @auth
                        @if(auth()->user()->isAdmin())
                            <th>Действия</th>
                        @endif
                        @endauth
                    </tr>
                </thead>

                <tbody>

                @forelse($users as $user)

                    <tr>

                        <td>{{ $user->id }}</td>

                        <td>{{ $user->name }}</td>

                        <td>
                            @php $genders = ['M' => 'Мужской', 'F' => 'Женский']; @endphp
                            {{ $genders[$user->gender] ?? 'Не указан' }}
                        </td>

                        <td>{{ $user->birth_date ?? '—' }}</td>

                        <td>{{ $user->email }}</td>

                        <td>{{ $user->login }}</td>

                        <td>{{ $user->role === 'admin' ? 'admin' : 'user' }}</td>

                        <td>
                            @auth
                            @if(auth()->user()->isAdmin())

                                <button
                                    type="button"
                                    onclick="openEditForm(
                                        {{ $user->id }},
                                        '{{ addslashes($user->name) }}',
                                        '{{ $user->gender ?? '' }}',
                                        '{{ $user->birth_date ?? '' }}',
                                        '{{ addslashes($user->email) }}',
                                        '{{ addslashes($user->login) }}',
                                        '{{ $user->role }}'
                                    )">
                                    Изменить
                                </button>

                                |

                                <form
                                    action="{{ route('users.destroy', $user) }}"
                                    method="POST"
                                    style="display:inline">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        onclick="return confirm('Удалить пользователя?')">
                                        Удалить
                                    </button>

                                </form>

                            @endif
                            @endauth
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="8">Пользователи отсутствуют</td>
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
    var storeUrl  = '{{ route('user.store') }}';
    var updateUrl = '{{ url('users') }}';

    function resetForm() {
        document.getElementById('form-title').textContent  = 'Добавить пользователя';
        document.getElementById('user-form').action        = storeUrl;
        document.getElementById('form-method').value       = 'POST';
        document.getElementById('form-user-id').value      = '';
        document.getElementById('User_name').value         = '';
        document.getElementById('User_gender').value       = '';
        document.getElementById('User_birth_date').value   = '';
        document.getElementById('User_email').value        = '';
        document.getElementById('User_login').value        = '';
        document.getElementById('User_password').value     = '';
        document.getElementById('User_role').value         = 'user';
        document.getElementById('form-submit-btn').value   = 'Сохранить';
    }

    function toggleForm() {
        var block = document.getElementById('user-form-block');
        var btn   = document.getElementById('toggle-form-btn');
        if (block.style.display === 'none') {
            resetForm();
            block.style.display = 'block';
            btn.textContent = 'Отмена';
            block.scrollIntoView({ behavior: 'smooth' });
        } else {
            block.style.display = 'none';
            btn.textContent = '+ Добавить пользователя';
        }
    }

    function openEditForm(id, name, gender, birthDate, email, login, role) {
        var block = document.getElementById('user-form-block');
        var btn   = document.getElementById('toggle-form-btn');

        document.getElementById('form-title').textContent  = 'Редактировать пользователя';
        document.getElementById('user-form').action        = updateUrl + '/' + id;
        document.getElementById('form-method').value       = 'PUT';
        document.getElementById('form-user-id').value      = id;
        document.getElementById('User_name').value         = name;
        document.getElementById('User_gender').value       = gender;
        document.getElementById('User_birth_date').value   = birthDate;
        document.getElementById('User_email').value        = email;
        document.getElementById('User_login').value        = login;
        document.getElementById('User_password').value     = '';
        document.getElementById('User_role').value         = role;
        document.getElementById('form-submit-btn').value   = 'Обновить';

        block.style.display = 'block';
        if (btn) { btn.textContent = 'Отмена'; }
        block.scrollIntoView({ behavior: 'smooth' });
    }

    // Если вернулись ошибки валидации — держим форму открытой
    @if($errors->any())
    document.addEventListener('DOMContentLoaded', function () {
        var block = document.getElementById('user-form-block');
        var btn   = document.getElementById('toggle-form-btn');
        if (block) { block.style.display = 'block'; }
        if (btn)   { btn.textContent = 'Отмена'; }
    });
    @endif
</script>

</body>
</html>