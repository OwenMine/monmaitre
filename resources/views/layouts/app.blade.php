<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MON MAÎTRE – Le bon répétiteur, pour un meilleur avenir')</title>
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
<body class="bg-white min-h-screen font-sans antialiased flex flex-col" x-data="{ mobileMenuOpen: false }">

    {{-- ============ HEADER PUBLIC ============ --}}
    <header
        data-scroll-header
        class="bg-white/95 backdrop-blur-md border-b border-gray-100 sticky top-0 z-40 transition-all duration-300">

        <div class="max-w-6xl mx-auto px-6 md:px-12 py-2 flex justify-between items-center gap-4">

            {{-- Logo complet (80px) --}}
            <a href="{{ route('accueil') }}" class="flex items-center group flex-shrink-0">
                <img
                    src="{{ asset('images/logo-full.png') }}"
                    alt="MON MAÎTRE – Le bon répétiteur, pour un meilleur avenir"
                    class="h-20 w-auto transition-transform duration-300 group-hover:scale-105">
            </a>

            {{-- Navigation desktop --}}
            <nav class="hidden md:flex items-center gap-8">
                <a href="{{ route('accueil') }}#fonctionnement"
                   class="relative text-sm font-medium text-gray-600 hover:text-mm-navy transition group">
                    Fonctionnement
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-mm-accent transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="{{ route('accueil') }}#services"
                   class="relative text-sm font-medium text-gray-600 hover:text-mm-navy transition group">
                    Services
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-mm-accent transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="{{ route('accueil') }}#faq"
                   class="relative text-sm font-medium text-gray-600 hover:text-mm-navy transition group">
                    FAQ
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-mm-accent transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="{{ route('accueil') }}#contact"
                   class="relative text-sm font-medium text-gray-600 hover:text-mm-navy transition group">
                    Contact
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-mm-accent transition-all duration-300 group-hover:w-full"></span>
                </a>
            </nav>

            {{-- Actions --}}
            <div class="flex items-center gap-3 flex-shrink-0">
                @auth
                    <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : (auth()->user()->role === 'repetiteur' ? route('repetiteur.dashboard') : route('parent.dashboard')) }}"
                       class="hidden sm:inline-flex items-center gap-2 bg-mm-navy text-white text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-mm-navy-hover hover:shadow-lg hover:shadow-mm-navy/30 hover:-translate-y-0.5 transition-all duration-300">
                        Mon espace
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="hidden sm:inline-block text-sm font-medium text-gray-600 hover:text-mm-navy transition">
                        Connexion
                    </a>
                    <a href="{{ route('pre-inscription.parent') }}"
                       class="hidden sm:inline-flex items-center gap-2 bg-mm-navy text-white text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-mm-navy-hover hover:shadow-lg hover:shadow-mm-navy/30 hover:-translate-y-0.5 transition-all duration-300">
                        Commencer
                    </a>
                @endauth

                {{-- Burger mobile --}}
                <button
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    class="md:hidden w-10 h-10 rounded-lg flex items-center justify-center text-mm-navy hover:bg-gray-100 transition">
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
            <nav class="max-w-6xl mx-auto px-6 md:px-12 py-4 flex flex-col gap-1">
                <a href="{{ route('accueil') }}#fonctionnement" class="px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-mm-navy transition">Fonctionnement</a>
                <a href="{{ route('accueil') }}#services" class="px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-mm-navy transition">Services</a>
                <a href="{{ route('accueil') }}#faq" class="px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-mm-navy transition">FAQ</a>
                <a href="{{ route('accueil') }}#contact" class="px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-mm-navy transition">Contact</a>
                <div class="border-t border-gray-100 mt-2 pt-3">
                    @auth
                        <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : (auth()->user()->role === 'repetiteur' ? route('repetiteur.dashboard') : route('parent.dashboard')) }}"
                           class="block bg-mm-navy text-white text-sm font-semibold px-5 py-3 rounded-lg text-center">
                            Mon espace
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="block text-sm font-medium text-gray-600 px-3 py-2.5">Connexion</a>
                        <a href="{{ route('pre-inscription.parent') }}"
                           class="block bg-mm-navy text-white text-sm font-semibold px-5 py-3 rounded-lg text-center mt-2">
                            Commencer
                        </a>
                    @endauth
                </div>
            </nav>
        </div>
    </header>

    {{-- ============ CONTENU ============ --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- ============ FOOTER PUBLIC ============ --}}
    <footer class="relative bg-mm-navy text-gray-400 py-12 pb-24 overflow-hidden">
        {{-- Pattern décoratif --}}
        <div class="absolute inset-0 opacity-[0.03]">
            <svg width="100%" height="100%">
                <defs>
                    <pattern id="footer-grid" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="0.5"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#footer-grid)"/>
            </svg>
        </div>

        <div class="relative max-w-6xl mx-auto px-6 md:px-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 md:gap-10 mb-8">
                <div class="sm:col-span-2 lg:col-span-1">
                    <img
                        src="{{ asset('images/logo-full.png') }}"
                        alt="MON MAÎTRE"
                        class="h-16 w-auto mb-4 brightness-0 invert">
                    <p class="text-sm max-w-md leading-relaxed">
                        Plateforme de placement et de suivi de répétiteurs à domicile en Côte d'Ivoire.
                        Le bon répétiteur, pour un meilleur avenir.
                    </p>
                </div>

                <div>
                    <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Navigation</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('accueil') }}#fonctionnement" class="hover:text-mm-accent transition inline-block hover:translate-x-1 duration-300">Fonctionnement</a></li>
                        <li><a href="{{ route('accueil') }}#services" class="hover:text-mm-accent transition inline-block hover:translate-x-1 duration-300">Services</a></li>
                        <li><a href="{{ route('accueil') }}#faq" class="hover:text-mm-accent transition inline-block hover:translate-x-1 duration-300">FAQ</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-mm-accent transition inline-block hover:translate-x-1 duration-300">Connexion</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Légal</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('legal.cgu') }}" class="hover:text-mm-accent transition inline-block hover:translate-x-1 duration-300">Conditions d'utilisation</a></li>
                        <li><a href="{{ route('legal.confidentialite') }}" class="hover:text-mm-accent transition inline-block hover:translate-x-1 duration-300">Confidentialité</a></li>
                        <li><a href="{{ route('legal.cookies') }}" class="hover:text-mm-accent transition inline-block hover:translate-x-1 duration-300">Cookies</a></li>
                        <li><a href="{{ route('legal.mentions') }}" class="hover:text-mm-accent transition inline-block hover:translate-x-1 duration-300">Mentions légales</a></li>
                    </ul>
                </div>

                <div>
    <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Contact</h4>
    <ul class="space-y-2 text-sm">
        <li class="flex items-center gap-2">
            <x-icons.envelope class="w-4 h-4 text-mm-accent flex-shrink-0" />
            <a href="mailto:contact@monmaitre.ci" class="hover:text-mm-accent transition">contact@monmaitre.ci</a>
        </li>
        <li class="flex items-center gap-2">
            <x-icons.phone class="w-4 h-4 text-mm-accent flex-shrink-0" />
            <a href="tel:+2250719167688" class="hover:text-mm-accent transition">07 19 16 76 88</a>
        </li>
        <li class="flex items-center gap-2">
            <x-icons.user-circle class="w-4 h-4 text-mm-accent flex-shrink-0" />
            Côte d'Ivoire
        </li>
    </ul>

    {{-- Réseaux sociaux --}}
    <div class="mt-6">
        <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-3">Suivez-nous</p>
        <div class="flex items-center gap-2">

            {{-- Facebook --}}
            <a href="https://facebook.com/monmaitre.ci" target="_blank" rel="noopener noreferrer"
               class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center text-white transition-all duration-300 hover:bg-[#1877F2] hover:scale-110 hover:-translate-y-0.5"
               title="Facebook MON MAÎTRE">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
            </a>

            {{-- Instagram --}}
            <a href="https://instagram.com/monmaitre.ci" target="_blank" rel="noopener noreferrer"
               class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center text-white transition-all duration-300 hover:bg-gradient-to-br hover:from-[#833AB4] hover:via-[#FD1D1D] hover:to-[#F77737] hover:scale-110 hover:-translate-y-0.5"
               title="Instagram MON MAÎTRE">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                </svg>
            </a>

            {{-- WhatsApp --}}
            <a href="https://wa.me/2250719167688" target="_blank" rel="noopener noreferrer"
               class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center text-white transition-all duration-300 hover:bg-[#25D366] hover:scale-110 hover:-translate-y-0.5"
               title="WhatsApp MON MAÎTRE">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/>
                </svg>
            </a>

            {{-- TikTok --}}
            <a href="https://tiktok.com/@monmaitre.ci" target="_blank" rel="noopener noreferrer"
               class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center text-white transition-all duration-300 hover:bg-black hover:scale-110 hover:-translate-y-0.5"
               title="TikTok MON MAÎTRE">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/>
                </svg>
            </a>

        </div>
    </div>
    </div>
        </div>
            <div class="pt-6 border-t border-white/10 flex flex-col md:flex-row justify-between items-center gap-3 text-xs">
                <p>© {{ date('Y') }} MON MAÎTRE — Tous droits réservés.</p>
                <p class="text-mm-accent font-semibold tracking-wider">CONFIANCE • SOUTIEN • RÉUSSITE</p>
            </div>
        </div>
        </footer>

    {{-- ============ BOUTON FLOTTANT D'APPEL ============ --}}
    <a
        href="tel:+2250719167688"
        class="group flex items-center gap-3 rounded-full transition-all duration-300 hover:-translate-y-1 hover:scale-105"
        style="position: fixed; bottom: 24px; right: 24px; z-index: 9999; background-color: #062B5C; color: #FFFFFF; box-shadow: 0 20px 40px rgba(6, 43, 92, 0.4); padding: 12px 20px 12px 16px;">

        {{-- Icône avec animation pulse --}}
        <span class="relative flex items-center justify-center flex-shrink-0">
            <span class="absolute inline-flex rounded-full"
                  style="width: 32px; height: 32px; background-color: #F5A623; opacity: 0.75; animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;"></span>
            <span class="relative inline-flex rounded-full items-center justify-center"
                  style="width: 32px; height: 32px; background-color: #F5A623; color: #062B5C;">
                <x-icons.phone class="w-4 h-4" />
            </span>
        </span>

        {{-- Label --}}
        <span class="hidden sm:inline text-sm font-semibold whitespace-nowrap">
            Appeler MON MAÎTRE
        </span>
    </a>

</body>
</html>