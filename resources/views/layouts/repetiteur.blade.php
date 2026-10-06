<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Espace Répétiteur') – MON MAÎTRE</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-icon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
<body class="bg-mm-light min-h-screen font-sans antialiased flex flex-col" x-data="{ mobileMenuOpen: false }">

    {{-- ============ HEADER RÉPÉTITEUR (signature secondary) ============ --}}
    <header
        data-scroll-header
        class="bg-white/95 backdrop-blur-md border-b border-gray-100 sticky top-0 z-50 transition-all duration-300">

        <div class="max-w-5xl mx-auto px-6 py-2 flex justify-between items-center">

            {{-- Logo icône (56px) + Titre --}}
            <a href="{{ route('repetiteur.dashboard') }}" class="flex items-center gap-3 group">
                <img
                    src="{{ asset('images/logo-icon.png') }}"
                    alt="MON MAÎTRE"
                    class="h-14 w-auto transition-transform duration-300 group-hover:scale-110">
                <div>
                    <h1 class="text-lg font-bold text-mm-secondary leading-tight">MON MAÎTRE</h1>
                    <p class="text-xs text-mm-secondary font-semibold">Espace Répétiteur</p>
                </div>
            </a>

            {{-- Navigation desktop --}}
            <nav class="hidden md:flex items-center gap-6 text-sm">
                <a href="{{ route('repetiteur.dashboard') }}"
                   class="relative text-gray-600 hover:text-mm-secondary transition group {{ request()->routeIs('repetiteur.dashboard') ? 'text-mm-secondary font-semibold' : '' }}">
                    Tableau de bord
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-mm-accent transition-all duration-300 group-hover:w-full {{ request()->routeIs('repetiteur.dashboard') ? 'w-full' : '' }}"></span>
                </a>
                <a href="{{ route('repetiteur.profil.edit') }}"
                   class="relative text-gray-600 hover:text-mm-secondary transition group {{ request()->routeIs('repetiteur.profil.*') ? 'text-mm-secondary font-semibold' : '' }}">
                    Mon profil
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-mm-accent transition-all duration-300 group-hover:w-full {{ request()->routeIs('repetiteur.profil.*') ? 'w-full' : '' }}"></span>
                </a>
                <a href="{{ route('repetiteur.placements.index') }}"
                   class="relative text-gray-600 hover:text-mm-secondary transition group {{ request()->routeIs('repetiteur.placements.*') ? 'text-mm-secondary font-semibold' : '' }}">
                    Mes missions
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-mm-accent transition-all duration-300 group-hover:w-full {{ request()->routeIs('repetiteur.placements.*') ? 'w-full' : '' }}"></span>
                </a>
            </nav>

            {{-- Actions --}}
            <div class="flex items-center gap-3">
                {{-- Push toggle --}}
                <x-push-toggle theme="light" />

                {{-- Notifications --}}
                <x-notifications-dropdown theme="light" />

                {{-- Déconnexion --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="hidden md:flex items-center gap-2 text-sm text-gray-500 hover:text-red-600 transition">
                        <x-icons.logout class="w-4 h-4" />
                        <span>Déconnexion</span>
                    </button>
                </form>

                {{-- Burger mobile --}}
                <button
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    class="md:hidden w-10 h-10 rounded-lg flex items-center justify-center text-mm-secondary hover:bg-gray-100 transition">
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Menu mobile --}}
        <div
            x-show="mobileMenuOpen"
            x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-4"
            class="md:hidden border-t border-gray-100 bg-white">
            <nav class="max-w-5xl mx-auto px-6 py-4 flex flex-col gap-1">
                <a href="{{ route('repetiteur.dashboard') }}" class="px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-mm-secondary transition {{ request()->routeIs('repetiteur.dashboard') ? 'bg-gray-50 text-mm-secondary font-semibold' : '' }}">Tableau de bord</a>
                <a href="{{ route('repetiteur.profil.edit') }}" class="px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-mm-secondary transition {{ request()->routeIs('repetiteur.profil.*') ? 'bg-gray-50 text-mm-secondary font-semibold' : '' }}">Mon profil</a>
                <a href="{{ route('repetiteur.placements.index') }}" class="px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-mm-secondary transition {{ request()->routeIs('repetiteur.placements.*') ? 'bg-gray-50 text-mm-secondary font-semibold' : '' }}">Mes missions</a>
                <div class="border-t border-gray-100 mt-2 pt-3">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2.5 rounded-lg text-sm font-medium text-red-600 hover:bg-red-50 transition">
                            Déconnexion
                        </button>
                    </form>
                </div>
            </nav>
        </div>
    </header>

    {{-- ============ CONTENU ============ --}}
    <main class="flex-1">
        <div class="max-w-5xl mx-auto px-6 py-10">
            @yield('content')
        </div>
    </main>

    {{-- ============ FOOTER RÉPÉTITEUR ============ --}}
    <footer class="bg-white border-t border-gray-100">
        <div class="max-w-5xl mx-auto px-6 py-6 flex flex-col md:flex-row justify-between items-center gap-3">
            <p class="text-sm text-gray-500">
                © {{ date('Y') }} <span class="font-semibold text-mm-secondary">MON MAÎTRE</span> — Espace Répétiteur
            </p>
            <p class="text-xs text-gray-400">
                <a href="tel:+2250719167688" class="hover:text-mm-secondary transition">07 19 16 76 88</a> • contact@monmaitre.ci
            </p>
        </div>
    </footer>

    {{-- Bandeau d'activation des push --}}
    @auth
        <x-push-banner />
    @endauth

    {{-- Scripts additionnels --}}
    @stack('scripts')

</body>
</html>