<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .register-box {
            width: 350px;
            margin: 60px auto;
            background: #fff;
            padding: 25px;
            border: 1px solid #ddd;
        }
        h2 { text-align: center; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="date"],
        select {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
        }
        button { width: 100%; padding: 10px; cursor: pointer; }
        .error { color: red; font-size: 14px; margin-top: 5px; }
        .login-link { text-align: center; margin-top: 15px; }
        .login-link a { text-decoration: none; }
    </style>
</head>
<body>
<div class="register-box">
    <h2>Регистрация</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label>Имя *</label>
            <input type="text" name="name" value="{{ old('name') }}" required autofocus>
            @error('name') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Пол</label>
            <select name="gender">
                <option value=""  {{ old('gender') == ''  ? 'selected' : '' }}>Не указан</option>
                <option value="M" {{ old('gender') == 'M' ? 'selected' : '' }}>Мужской</option>
                <option value="F" {{ old('gender') == 'F' ? 'selected' : '' }}>Женский</option>
            </select>
            @error('gender') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Дата рождения</label>
            <input type="date" name="birth_date" value="{{ old('birth_date') }}">
            @error('birth_date') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Email *</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
            @error('email') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Логин *</label>
            <input type="text" name="login" value="{{ old('login') }}" maxlength="40" required>
            @error('login') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Пароль *</label>
            <input type="password" name="password" required>
            @error('password') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Подтвердите пароль *</label>
            <input type="password" name="password_confirmation" required>
        </div>

        <button type="submit">Зарегистрироваться</button>

        <div class="login-link">
            <a href="{{ route('login') }}">Уже зарегистрированы? Войти</a>
        </div>
    </form>
</div>
</body>
</html>