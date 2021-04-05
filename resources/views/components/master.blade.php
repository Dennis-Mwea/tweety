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
    <section class="w-full bg-white items-center flex-wrap m-auto top-0 animated fixed z-20">
        <header class="container mx-auto flex justify-between bg-white p-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="130" height="34" viewBox="0 0 130 34">
                <g fill="none" fill-rule="evenodd">
                    <path fill="#47D5FE"
                          d="M31.273 13C16.487 13 4.326 24.078 3.037 38.157c2.457-3.374 5.466-6.621 9.223-10.354a.738.738 0 011.029-.01c.286.273.29.722.01 1.001a169.806 169.806 0 00-2.688 2.732l-.175.184C5.793 36.552 2.474 40.767.064 46.001a.702.702 0 00.365.937.74.74 0 00.963-.356 38.585 38.585 0 012.974-5.273c10.159-.253 19.406-5.757 24.252-14.515a.696.696 0 00-.016-.7.737.737 0 00-.625-.344h-2.694l4.83-2.689a.714.714 0 00.328-.384A26.88 26.88 0 0032 13.708a.718.718 0 00-.727-.708z"
                          transform="translate(0 -13)"/>
                    <text fill="#222" font-size="26" font-weight="700" class="font-sans italic ml-2">
                        <tspan x="40" y="25">Tweety</tspan>
                    </text>
                </g>
            </svg>

            @if(isset($search))
                {{ $search }}
            @endif
        </header>
    </section>

    <div class="mt-32">
        {{ $slot }}
    </div>
</div>
</body>
</html>
