<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('description', trans('meta.description'))">
    <meta name="theme-color" content="#0c0d0c">

    <title>@yield('title', trans('meta.title'))</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/images/favicon.svg') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300;1,400&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>
    @include('site.partials.preloader')

    <div class="page-noise" aria-hidden="true"></div>
    <div class="scroll-progress" aria-hidden="true"><span id="scrollProgress"></span></div>
    <div class="cursor-dot" id="cursorDot" aria-hidden="true"></div>
    <div class="cursor-ring" id="cursorRing" aria-hidden="true"></div>

    @include('site.partials.header')

    <main id="main">
        @yield('content')
    </main>

    @include('site.partials.footer')
    @include('site.partials.task-modal')
    @include('site.partials.toasts')

    <script src="{{ asset('assets/js/main.js') }}" defer></script>
</body>
</html>
