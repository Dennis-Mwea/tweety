<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Tweety') }}</title>

    <!-- Scripts -->
    <script type="module" src="{{ mix('js/app.js') }}"></script>

    <script src="https://unpkg.com/turbolinks"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

@if (isset($script))
    {{ $script }}
@endif
    
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

    {{ $slot }}
</div>
</body>
</html>
