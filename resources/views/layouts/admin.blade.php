<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Administration') – MON MAÎTRE</title>
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

    {{-- ============ HEADER ADMIN (fond navy) ============ --}}
    <header
        data-scroll-header
        class="bg-mm-navy/95 backdrop-blur-md text-white sticky top-0 z-50 transition-all duration-300 border-b border-white/5">

        <div class="max-w-6xl mx-auto px-6 md:px-12 py-2 flex justify-between items-center">

            {{-- Logo icône seule (56px) + Titre --}}
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                <img
                    src="{{ asset('images/logo-icon.png') }}"
                    alt="MON MAÎTRE"
                    class="h-14 w-auto transition-transform duration-300 group-hover:scale-110">
                <div>
                    <h1 class="text-lg font-bold leading-tight text-white">MON MAÎTRE</h1>
                    <p class="text-xs text-mm-accent font-semibold">Administration</p>
                </div>
            </a>

            {{-- Navigation desktop --}}
            <nav class="hidden md:flex items-center gap-8 text-sm">
                <a href="{{ route('admin.dashboard') }}"
                   class="relative text-white/90 hover:text-mm-accent transition group {{ request()->routeIs('admin.dashboard') ? 'text-mm-accent font-semibold' : '' }}">
                    Tableau de bord
                    <span class="absolute -bottom-1 left-0 h-0.5 bg-mm-accent transition-all duration-300 group-hover:w-full {{ request()->routeIs('admin.dashboard') ? 'w-full' : 'w-0' }}"></span>
                </a>
                <a href="{{ route('admin.demandes.index') }}"
                   class="relative text-white/90 hover:text-mm-accent transition group {{ request()->routeIs('admin.demandes.*') ? 'text-mm-accent font-semibold' : '' }}">
                    Demandes
                    <span class="absolute -bottom-1 left-0 h-0.5 bg-mm-accent transition-all duration-300 group-hover:w-full {{ request()->routeIs('admin.demandes.*') ? 'w-full' : 'w-0' }}"></span>
                </a>
                <a href="{{ route('admin.repetiteurs.index') }}"
                   class="relative text-white/90 hover:text-mm-accent transition group {{ request()->routeIs('admin.repetiteurs.*') ? 'text-mm-accent font-semibold' : '' }}">
                    Répétiteurs
                    <span class="absolute -bottom-1 left-0 h-0.5 bg-mm-accent transition-all duration-300 group-hover:w-full {{ request()->routeIs('admin.repetiteurs.*') ? 'w-full' : 'w-0' }}"></span>
                </a>
                <a href="{{ route('admin.placements.index') }}"
                   class="relative text-white/90 hover:text-mm-accent transition group {{ request()->routeIs('admin.placements.*') ? 'text-mm-accent font-semibold' : '' }}">
                    Placements
                    <span class="absolute -bottom-1 left-0 h-0.5 bg-mm-accent transition-all duration-300 group-hover:w-full {{ request()->routeIs('admin.placements.*') ? 'w-full' : 'w-0' }}"></span>
                </a>
                                <a href="{{ route('admin.suivi.index') }}"
                   class="relative text-white/90 hover:text-mm-accent transition group {{ request()->routeIs('admin.suivi.*') ? 'text-mm-accent font-semibold' : '' }}">
                    Suivi
                    <span class="absolute -bottom-1 left-0 h-0.5 bg-mm-accent transition-all duration-300 group-hover:w-full {{ request()->routeIs('admin.suivi.*') ? 'w-full' : 'w-0' }}"></span>
                </a>
            </nav>

            {{-- Déconnexion --}}
            <div class="flex items-center gap-3">
                {{-- Push toggle --}}
                <x-push-toggle theme="dark" />
                 {{-- Notifications --}}
                <x-notifications-dropdown theme="dark" />

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="hidden md:flex items-center gap-2 text-sm text-white/70 hover:text-mm-accent transition group">
                        <x-icons.logout class="w-4 h-4 transition-transform group-hover:translate-x-1" />
                        <span>Déconnexion</span>
                    </button>
                </form>

                {{-- Burger mobile --}}
                <button
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    class="md:hidden w-10 h-10 rounded-lg flex items-center justify-center text-white hover:bg-white/10 transition">
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
            class="md:hidden border-t border-white/10">
            <nav class="max-w-6xl mx-auto px-6 md:px-12 py-4 flex flex-col gap-1">
                <a href="{{ route('admin.dashboard') }}" class="px-3 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-mm-accent transition {{ request()->routeIs('admin.dashboard') ? 'bg-white/5 text-mm-accent' : '' }}">Tableau de bord</a>
                <a href="{{ route('admin.demandes.index') }}" class="px-3 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-mm-accent transition {{ request()->routeIs('admin.demandes.*') ? 'bg-white/5 text-mm-accent' : '' }}">Demandes</a>
                <a href="{{ route('admin.repetiteurs.index') }}" class="px-3 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-mm-accent transition {{ request()->routeIs('admin.repetiteurs.*') ? 'bg-white/5 text-mm-accent' : '' }}">Répétiteurs</a>
                <a href="{{ route('admin.placements.index') }}" class="px-3 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-mm-accent transition {{ request()->routeIs('admin.placements.*') ? 'bg-white/5 text-mm-accent' : '' }}">Placements</a>
                <a href="{{ route('admin.suivi.index') }}" class="px-3 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-mm-accent transition {{ request()->routeIs('admin.suivi.*') ? 'bg-white/5 text-mm-accent' : '' }}">Suivi</a>
                

                {{-- Déconnexion (mobile) --}}
                <div class="border-t border-white/10 mt-2 pt-3">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2.5 rounded-lg text-sm font-medium text-red-400 hover:bg-red-500/10 hover:text-red-300 transition flex items-center gap-2">
                            <x-icons.logout class="w-4 h-4" />
                            Déconnexion
                        </button>
                    </form>
                </div>
            </nav>
        </div>
    </header>

    {{-- ============ CONTENU ============ --}}
    <main class="flex-1">
        <div class="max-w-6xl mx-auto px-6 md:px-12 py-10 md:py-14">
            @yield('content')
        </div>
    </main>

    {{-- ============ FOOTER ADMIN ============ --}}
    <footer class="bg-white border-t border-gray-100">
        <div class="max-w-6xl mx-auto px-6 md:px-12 py-6 flex flex-col md:flex-row justify-between items-center gap-3">
            <p class="text-sm text-gray-500">
                © {{ date('Y') }} <span class="font-semibold text-mm-navy">MON MAÎTRE</span> — Administration
            </p>
            <p class="text-xs text-gray-400">
                <a href="tel:+2250719167688" class="hover:text-mm-navy transition">07 19 16 76 88</a> • contact@monmaitre.ci
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