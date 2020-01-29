<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    @include('partials.favicon')
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body class="pt-3 pb-5">
        <main>
            @yield('content')
        </main>
        <footer id="footer" class="pt-2 pb-2">
            <span>Copyright 2020 &copy Safo</span>
            <span class="localeOptions">
                <a href="{{route('setLocale',['locale' => 'pl'])}}">
                    <img src="{{asset('img/locales/pl.png')}}">
                </a>
                <a href="{{route('setLocale',['locale' => 'en'])}}">
                    <img src="{{asset('img/locales/eng.png')}}">
                </a>
            </span>
        </footer>
        @stack('scripts')
</body>
</html>
