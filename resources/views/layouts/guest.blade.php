<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'MON MAÎTRE')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-icon.png') }}">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
   <x-assets-loader />
    
    {{-- PWA --}}
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#062B5C">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="MON MAÎTRE">
    <link rel="apple-touch-icon" href="/images/pwa/icon-152.png">
    
</head>
<body class="font-sans text-gray-900 antialiased bg-mm-light">
    <div class="min-h-screen flex flex-col sm:justify-center items-center py-8 px-6">

        {{-- Logo --}}
        <div class="mb-6">
            <a href="/">
                <img src="{{ asset('images/logo-full.png') }}" alt="MON MAÎTRE" class="h-20 w-auto">
            </a>
        </div>

        {{-- Carte formulaire --}}
        <div class="w-full sm:max-w-md px-6 py-8 bg-white shadow-lg rounded-2xl border border-gray-100">
            @yield('content')
        </div>

        {{-- Lien retour --}}
        <p class="mt-6 text-sm text-gray-500">
            <a href="/" class="hover:text-mm-navy transition">
                ← Retour à l'accueil
            </a>
        </p>
    </div>
</body>
</html>