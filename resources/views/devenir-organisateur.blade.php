<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Devenir organisateur - {{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] min-h-screen p-6 lg:p-8">
        <header class="w-full max-w-4xl mx-auto mb-8 text-sm">
            <nav class="flex items-center justify-between gap-4">
                <a
                    href="{{ url('/') }}"
                    class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal"
                >
                    Accueil
                </a>
            </nav>
        </header>

        <main class="w-full max-w-4xl mx-auto">
            <section class="bg-white dark:bg-[#161615] dark:text-[#EDEDEC] border border-[#19140035] dark:border-[#3E3E3A] rounded-sm p-6 lg:p-10">
                <p class="mb-2 text-sm font-semibold text-[#f53003] dark:text-[#FF4433]">Organisateurs</p>
                <h1 class="mb-4 text-2xl font-semibold">Devenir organisateur</h1>
                <p class="mb-6 text-[#706f6c] dark:text-[#A1A09A]">
                    Creez votre espace organisateur pour publier vos evenements, gerer vos participants et suivre vos demandes de sponsorisation.
                </p>

                <a
                    href="{{ url('/') }}"
                    class="inline-block px-5 py-2 bg-[#1b1b18] text-white dark:bg-[#eeeeec] dark:text-[#1C1C1A] rounded-sm text-sm font-medium leading-normal"
                >
                    Commencer
                </a>
            </section>
        </main>
    </body>
</html>
