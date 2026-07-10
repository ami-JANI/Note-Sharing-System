<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'UniNotes') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=public-sans:400,500,600,700|source-serif-4:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { background: #FBF8F3; }
            body, .font-sans { font-family: "Public Sans", system-ui, sans-serif; }
            h1, h2, h3, .font-serif { font-family: "Source Serif 4", serif; }
        </style>
    </head>
    <body class="antialiased" style="background: #FBF8F3; color: rgb(27, 42, 74);">
        <div class="min-h-screen" style="background: #FBF8F3;">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header style="background: rgba(27, 42, 74, 0.04); border-bottom: 1px solid rgba(27, 42, 74, 0.08);">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
