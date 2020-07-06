<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>

    <?php
    FanCourier::generateAwb(['fisier' => [
        [
            'tip_serviciu' => 'standard',
            'banca' => '',
            'iban' =>  '',
            'nr_plicuri' => 1,
            'nr_colete' => 0,
            'greutate' => 1,
            'plata_expeditie' => 'ramburs',
            'ramburs_bani' => 100,
            'plata_ramburs_la' => 'destinatar',
            'valoare_declarata' => 400,
            'persoana_contact_expeditor' => 'Test User',
            'observatii' => 'Lorem ipsum',
            'continut' => '',
            'nume_destinar' => 'Test',
            'persoana_contact' => 'Test',
            'telefon' => '123456789',
            'fax' => '123456789',
            'email' => 'example@example.com',
            'judet' => 'Galati',
            'localitate' => 'Tecuci',
            'strada' => 'Lorem',
            'nr' => '2',
            'cod_postal' => '123456',
            'bl' => '',
            'scara' => '',
            'etaj'  => '',
            'apartament' => '',
            'inaltime_pachet' => '',
            'lungime_pachet' => '',
            'restituire' => '',
            'centru_cost' => '',
            'optiuni' => '',
            'packing' => '',
            'date_personale' => ''
        ],
        [
            'tip_serviciu' => 'Cont colector',
            'banca' => 'Test',
            'iban' =>  'XXXXXX',
            'nr_plicuri' => 0,
            'nr_colete' => 1,
            'greutate' => 1,
            'plata_expeditie' => 'ramburs',
            'ramburs_bani' => 400,
            'plata_ramburs_la' => 'destinatar',
            'valoare_declarata' => 400,
            'persoana_contact_expeditor' => 'Test User',
            'observatii' => 'Lorem ipsum',
            'continut' => 'Fragil',
            'nume_destinar' => 'Test',
            'persoana_contact' => 'Test',
            'telefon' => '123456789',
            'fax' => '123456789',
            'email' => 'example@example.com',
            'judet' => 'Galati',
            'localitate' => 'Tecuci',
            'strada' => 'Lorem',
            'nr' => '2',
            'cod_postal' => '123456',
            'bl' => '',
            'scara' => '',
            'etaj'  => '',
            'apartament' => '',
            'inaltime_pachet' => '',
            'lungime_pachet' => '',
            'restituire' => '',
            'centru_cost' => '',
            'optiuni' => '',
            'packing' => '',
            'date_personale' => ''
        ]
    ]]);

    ?>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    Laravel
                </div>

                <div class="links">
                    <a href="https://laravel.com/docs">Docs</a>
                    <a href="https://laracasts.com">Laracasts</a>
                    <a href="https://laravel-news.com">News</a>
                    <a href="https://blog.laravel.com">Blog</a>
                    <a href="https://nova.laravel.com">Nova</a>
                    <a href="https://forge.laravel.com">Forge</a>
                    <a href="https://vapor.laravel.com">Vapor</a>
                    <a href="https://github.com/laravel/laravel">GitHub</a>
                </div>
            </div>
        </div>
    </body>
</html>
