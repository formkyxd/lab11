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

    <title>Список остановок — ГТС</title>
</head>

<body>

<div class="container" id="page">

    <a href="{{ route('home') }}">
        <div id="header">
            <div id="logo"></div>
        </div>
    </a>

    <div id="content">
        <h1>Список остановочных пунктов</h1>

        <p><a href="{{ route('station') }}">← Вернуться к управлению остановками</a></p>

        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Позиция</th>
                    <th>Линия</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($stations as $station)
                    <tr>
                        <td>{{ $station->id }}</td>
                        <td>{{ $station->name }}</td>
                        <td>{{ $station->position_station }}</td>
                        <td>{{ $station->line_id ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">Остановки не найдены</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
