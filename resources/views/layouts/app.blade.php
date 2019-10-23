<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        @include('partials.favicon')
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Safo') }}</title>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        @stack('styles')
    </head>
    <body class="pb-5">
        @include('partials.navigation')
        <main>
            @yield('content')
        </main>
        <footer id="footer" class="pt-2 pb-2">
            <span>Copyright 2019 &copy Wipaka</span>
        </footer>
        
        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}"></script>
        
        @stack('scripts')

    </body>
</html>
