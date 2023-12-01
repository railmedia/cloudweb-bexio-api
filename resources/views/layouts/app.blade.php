<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'cloudWEB Bexio API') }}</title>
    <!-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"> -->
    <!-- Fonts -->
    <!-- <link rel="preconnect" href="https://fonts.bunny.net"> -->
    <!-- <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" /> -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" /> -->
    <!-- Scripts -->
    @vite(['resources/css/app.scss', 'resources/js/app.js', 'resources/js/bexio.jsx'])
</head>
<body class="bg-gray-800 font-sans leading-normal tracking-normal mt-12 antialiased">
    @include('includes.header')
    <!-- <div class="min-h-screen bg-gray-100 dark:bg-gray-900"> -->
    {{--@include('layouts.navigation')--}}
        <!-- Page Heading -->
        @if (isset($header))
            <!-- <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header> -->
        @endif
        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    <!-- </div> -->
    @stack('bottom-scripts')
</body>
</html>
