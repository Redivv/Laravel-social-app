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
        <script>
            window.Laravel = {!! json_encode([
                'user' => auth()->check() ? auth()->user()->id : null,
            ]) !!};
        </script>

        @yield('titleTag')

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        @stack('styles')
    </head>
    <body>
        @include('partials.navigation')
        @yield('floatingPopUps')
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
        
        <!-- Scripts -->
        @stack('scriptsBefore')
        <script src="{{ asset('js/app.js') }}"></script>
        
        @stack('scripts')

    </body>
</html>
