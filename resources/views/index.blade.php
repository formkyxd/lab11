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
            <div id="logo"><!--Городская транспортная сеть--></div>
        </div>
    </a>

    <div id="mainmenu">

        <ul>

            <li>
                <a href="{{ route('line') }}" title="Line">
                    <span style="background-image: url('{{ asset('src/images/line.png') }}')"></span>
                    <!--Маршрут-->
                </a>
            </li>

            <li>
                <a href="{{ route('station') }}" title="Station">
                    <span style="background-image: url('{{ asset('src/images/station.png') }}')"></span>
                    <!--Остановка-->
                </a>
            </li>

            <li>
                <a href="{{ route('vehicle') }}" title="Vehicle">
                    <span style="background-image: url('{{ asset('src/images/vehicle.png') }}')"></span>
                    <!--Транспорт-->
                </a>
            </li>

            <li>
                <a href="{{ route('driver') }}" title="Driver">
                    <span style="background-image: url('{{ asset('src/images/driver.png') }}')"></span>
                    <!--Водитель-->
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

        <!-- Login / Logout -->
        <div id="access">

    @auth

        <div>
            {{ Auth::user()->name }}

            <form method="POST"
                  action="{{ route('logout') }}"
                  style="display:inline;">
                @csrf

                <button type="submit"
                        style="border:none;background:none;cursor:pointer;">
                    Выход
                </button>
            </form>
        </div>

    @else

        <div>
            <a href="{{ route('login') }}">Вход</a>
            |
            <a href="{{ route('register') }}">Регистрация</a>
        </div>

    @endauth

</div>

    </div>

    <!-- mainmenu -->
    <!-- breadcrumbs -->

    <div id="content">

        <h1> Добро пожаловать в <i>Городскую транспортную сеть</i></h1>

        <p> Крупнейшая транспортная компания «Городская транспортная сеть», управляет услугами трамвайных и автобусных перевозок. </p>

        <p>
            Маршрутная сеть является частью региональной ассоциации общественного транспорта и была сформирована путем
            слияния в январе 1947 года.
        </p>

        <p>
            13 трамвайных линий обслуживают транспортную зону около 152 километров, дополненную более чем 30
            автобусными линиями, которые находятся на маршруте в пригородной зоне.
        </p>

        <img width="670"
             src="{{ asset('src/images/Routes.svg') }}"
             alt="Avatar">

    </div>

    <!-- content -->

    <div class="clear"></div>

    <div id="footer">
        <ul>
            <li class="sitemap"><a href="#">Карта сайта</a></li>
            <li class="copyr"><a href="#">Copyright &copy; 2018 ГТС</a></li>
            <li class="allright"><a href="#">Все права сохранены <br>ГТС</a></li>
        </ul>
    </div>

    <!-- footer -->

</div>

<!-- page -->

</body>
</html>