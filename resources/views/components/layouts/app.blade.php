<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ config('app.name') }}</title>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    @filamentStyles
    @vite('resources/css/app.css')
</head>

<body class="antialiased">
    <div class="flex w-full" x-data>
        <x-sidebar/>
        <div class="h-[100vh] overflow-y-auto w-full">
            {{ $slot }}
        </div>
    </div>

    @filamentScripts
    @vite('resources/js/app.js')
    @livewire('notifications')
</body>

</html>
