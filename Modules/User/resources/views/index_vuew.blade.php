<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">        
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        {{-- @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'))) --}}
            {{-- @vite(['Modules/User/resources/assets/js/app.js','Modules/User/resources/assets/css/app.css']) --}}
        {{-- @endif --}}
            
    </head>
    <body>
        {{-- <div id="app"></div> --}}
    </body>

</html>
