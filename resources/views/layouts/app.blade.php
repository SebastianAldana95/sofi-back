<!doctype html>
<html lang="{{ str_replace('-', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--  CSRF TOKEN -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    @livewireStyles
</head>
<body>

    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
        </nav>

    </div>

    @livewireScripts
    <!-- Scripts -->
    <script src="{{mix('js/app.js')}}"></script>
</body>
</html>




