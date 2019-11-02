<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>
    <script>
        window.auth = {!! json_encode(Auth::user(), JSON_HEX_TAG) !!};
    </script>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app" class="min-h-screen pb-24">
        <!-- navigation -->
        <navigation-bar current-route="{{ Route::currentRouteName() }}" :channels="{{ \App\Channel::all() }}"></navigation-bar>

        <main class="py-4 px-6">
            @yield('content')
        </main>
    </div>
</body>
</html>
