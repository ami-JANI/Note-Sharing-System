<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'UniNotes') }}</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

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
        <div class="min-h-screen flex flex-col" style="background: #FBF8F3;">
            {{-- Header --}}
            <header style="background: rgba(251, 248, 243, 0.85); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(27, 42, 74, 0.1);">
                <nav style="max-width: 1200px; margin: 0 auto; padding: 18px 40px; display: flex; align-items: center; justify-content: space-between;">
                    <a href="{{ route('welcome') }}" style="display: flex; align-items: center; gap: 11px; color: rgb(27, 42, 74);">
                        <span style="display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; background: rgb(138, 28, 36); color: rgb(251, 248, 243); border-radius: 7px; font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 19px;">U</span>
                        <span style="font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 21px; letter-spacing: -0.01em;">UniNotes</span>
                    </a>
                    <div style="display: flex; align-items: center; gap: 28px;">
                        <a href="{{ route('login') }}" style="color: rgb(58, 71, 98); font-size: 15px; font-weight: 500;">Log in</a>
                        <a href="{{ route('register') }}" style="background: rgb(138, 28, 36); color: rgb(251, 248, 243); padding: 10px 20px; border-radius: 8px; font-size: 15px; font-weight: 600;">Sign up</a>
                    </div>
                </nav>
            </header>

            {{-- Main Content --}}
            <main class="flex-1" style="padding: 48px 40px;">
                <div style="max-width: 1200px; margin: 0 auto;">
                    {{ $slot }}
                </div>
            </main>

            {{-- Footer --}}
            <footer style="border-top: 1px solid rgba(27, 42, 74, 0.1); padding: 24px 40px; text-align: center;">
                <div style="display: flex; align-items: center; justify-content: center; gap: 11px; margin-bottom: 8px;">
                    <span style="display: inline-flex; align-items: center; justify-content: center; width: 24px; height: 24px; background: rgb(138, 28, 36); color: rgb(251, 248, 243); border-radius: 5px; font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 13px;">U</span>
                    <span style="font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 15px;">UniNotes</span>
                </div>
                <p style="font-size: 13px; color: rgb(138, 150, 174);">A note-sharing platform for university students</p>
            </footer>
        </div>
    </body>
</html>
