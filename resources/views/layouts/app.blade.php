<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Title --}}
    <title>{{ config('app.name', 'Laravel') }} @yield('title')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    {{-- CDNS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Usando Vite -->
    @vite(['resources/js/app.js'])

    {{-- Style Loader --}}
    <style>
        body {
            display: none
        }
    </style>
</head>

<body>
    <div id="app" class="d-flex">

        {{-- Main Sidebar --}}
        @include('includes.commons.sidebar')

        <main class="app-main">
            {{-- Main Header --}}
            @include('includes.commons.header')

            {{-- Main Content --}}
            @yield('content')
        </main>

        {{-- Modal --}}
        @include('includes.commons.modal')

        {{-- Messagges --}}
        @include('includes.commons.messages')

    </div>

    {{-- Scripts --}}
    @vite(['resources/js/commons/modal.js', 'resources/js/commons/sidebar-toggler.js'])
    @yield('scripts')
</body>

</html>
