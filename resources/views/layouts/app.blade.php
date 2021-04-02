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

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
    <section class="px-8 py-8 mb-6">
        <header class="container mx-auto">
            <img src="/images/logo.svg" alt="Tweety Logo"/>
        </header>
    </section>

    <section class="px-8">
        <main class="container mx-auto">
            <div class="lg:flex lg:justify-between">

                <div class="lg:w-32">
                    @include('../tweets/_sidebar-links')
                </div>

                <div class="flex-1 lg:mx-10" style="max-width: 700px">
                    @yield('content')
                </div>

                <div class="lg:w-1/6 bg-blue-100 rounded-lg p-4">
                    @include('../tweets/_friends-list')
                </div>
            </div>
        </main>
    </section>
</div>
</body>
</html>
