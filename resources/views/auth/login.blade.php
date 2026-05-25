<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .login-box {
            width: 350px;
            margin: 100px auto;
            background: #fff;
            padding: 25px;
            border: 1px solid #ddd;
        }
        h2 { text-align: center; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
        }
        button { width: 100%; padding: 10px; cursor: pointer; }
        .error { color: red; font-size: 14px; margin-top: 5px; }
    </style>
</head>
<body>
<div class="login-box">
    <h2>Авторизация</h2>
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label>Логин</label>
            <input
                type="text"
                name="login"
                value="{{ old('login') }}"
                required
                autofocus
            >
            @error('login')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Пароль</label>
            <input
                type="password"
                name="password"
                required
            >
            @error('password')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit">Войти</button>
    </form>
</div>
</body>
</html>