<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Yobbal') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-100 text-gray-800">

    <!-- Navigation (Fixe sur toutes les pages) -->
    <nav class="bg-blue-600 p-4 text-white shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">📦 Yobbal</h1>
            <ul class="flex space-x-4">
                <li><a href="{{ route('home') }}" class="hover:underline">Accueil</a></li>
                <li><a href="{{ route('colis.index') }}" class="hover:underline">Mes Colis</a></li>
                <li><a href="{{ route('about') }}" class="hover:underline">A propos</a></li>

            </ul>
        </div>
    </nav>

    <!-- C'est ici que le contenu spécifique sera injecté -->
    <div class="container mx-auto mt-8 p-4">
        @yield('content')
    </div>

    <!-- Pied de page (Fixe sur toutes les pages) -->
    <footer class="text-center p-4 mt-8 text-gray-500 text-sm">
        © 2025 Yobbal Logistics - Livraison partout au Cameroun.
    </footer>

</body>
</html>
