<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Livewire</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles -->
    <style>

    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#fff] flex items-center lg:justify-flex-start min-h-screen flex-col"
    x-data x-on:click="$dispatch('search:clear-results')">

    <div class="relative w-full">
        <nav class="bg-gray-900">
            <div class="max-w-screen-xl flex items-center justify-between mx-auto p-4">
                <div class="w-full block" id="navbar-default">
                    <ul class="flex font-medium">
                        <li>
                            <a href="/" class="block py-2 px-3 text-blue-500">Home</a>
                        </li>
                        <li>
                            <a href="/dashboard" class="block py-2 px-3 text-blue-500">Admin Dashboard</a>
                        </li>
                    </ul>
                </div>
                <livewire:search placeholder="type something to search">
            </div>
        </nav>
    </div>

    <main class="mt-6 mx-12">
        {{ $slot }}
    </main>

    <script data-navigate-once>
        console.log('page loaded');
    </script>

</body>

</html>
