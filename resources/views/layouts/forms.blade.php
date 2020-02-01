<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.favicon')
        
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Stworzony przez kobietę dla kobiet. Poznacie tutaj nowe osoby i zobaczycie aktualności LGBT - filmy, książki czy wiadomości ze świata.">
        <meta name=”robots” content="index, nofollow">
        <meta name="author" content="Jakub Rajca">
        <meta name="copyright" content="Maja Jędrzejek">
        <meta name="language" content="Polish">
        <meta name="revisit-after" content="7 days">

        <link rel="canonical" href="{{env('APP_URL','https://www.safo.com.pl')}}" />
        
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @yield('titleTag')

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
                    <img src="{{asset('img/locales/pl.png')}}" alt="Change Locale PL">
                </a>
                <a href="{{route('setLocale',['locale' => 'en'])}}">
                    <img src="{{asset('img/locales/eng.png')}}" alt="Change Locale EN">
                </a>
            </span>
        </footer>
        @stack('scripts')
</body>
</html>
