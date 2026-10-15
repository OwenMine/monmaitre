@extends('layouts.app')

@section('title', 'MON MAÎTRE – Le bon répétiteur, pour un meilleur avenir')

@section('content')

    {{-- ============ HERO PLEIN ÉCRAN AVEC CAROUSEL EN FOND ============ --}}
    <section class="relative overflow-hidden" x-data="carousel()" x-init="init()" style="min-height: 580px;">

        {{-- CAROUSEL EN ARRIÈRE-PLAN --}}
        <div class="absolute inset-0">
            @php
                $slides = [
                    ['image' => asset('images/carousel/slide-1.webp'), 'fallback' => 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=1920&q=80'],
                    ['image' => asset('images/carousel/slide-2.webp'), 'fallback' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=1920&q=80'],
                    ['image' => asset('images/carousel/slide-3.webp'), 'fallback' => 'https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?w=1920&q=80'],
                ];
            @endphp

            @foreach ($slides as $i => $slide)
                <div x-show="currentSlide === {{ $i }}"
                     x-transition:enter="transition ease-out duration-1000"
                     x-transition:enter-start="opacity-0 scale-110"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-700"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-105"
                     class="absolute inset-0">
                    <img src="{{ $slide['image'] }}" alt="" 
                    class="w-full h-full object-cover"
                    loading="{{ $i === 0 ? 'eager' : 'lazy' }}"
                    onerror="this.onerror=null; this.src='{{ $slide['fallback'] }}';">
                </div>
            @endforeach

            <div class="absolute inset-0"
                 style="background: linear-gradient(to right, rgba(6, 43, 92, 0.95) 0%, rgba(6, 43, 92, 0.85) 40%, rgba(6, 43, 92, 0.4) 70%, rgba(6, 43, 92, 0.2) 100%), linear-gradient(to top, rgba(6, 43, 92, 0.8) 0%, transparent 40%);">
            </div>
        </div>

        {{-- CONTENU HERO --}}
        <div class="relative z-10 max-w-6xl mx-auto px-6 md:px-12 py-8 md:py-14">
            <div class="max-w-2xl animate-slide-in-left">
                <span class="inline-flex items-center gap-2 bg-mm-accent/20 backdrop-blur-sm text-mm-accent text-xs font-semibold px-3 py-1.5 rounded-full mb-6 border border-mm-accent/30">
                    <span class="w-1.5 h-1.5 rounded-full bg-mm-accent animate-pulse"></span>
                    Service de placement à domicile
                </span>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-[1.1] tracking-tight mb-6">
                    Vous cherchez<br>un répétiteur ?<br>
                    <span class="text-mm-accent">MON MAÎTRE s'en charge.</span>
                </h1>
                <p class="text-lg text-gray-100 mb-8 leading-relaxed max-w-lg drop-shadow-lg">
                    Confiez-nous le besoin scolaire de votre enfant.
                    Nous recherchons, sélectionnons et vous proposons un répétiteur adapté.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 mb-10">
                    <a href="{{ route('pre-inscription.parent') }}"
                       class="inline-flex items-center justify-center gap-2 bg-mm-accent text-mm-navy px-7 py-3.5 rounded-lg font-semibold hover:bg-mm-accent-hover hover:shadow-lg hover:shadow-mm-accent/40 hover:-translate-y-0.5 transition-all duration-300">
                        Je cherche un répétiteur
                        <x-icons.arrow-right class="w-4 h-4" />
                    </a>
                    <a href="{{ route('pre-inscription.repetiteur') }}"
                       class="inline-flex items-center justify-center gap-2 bg-white/10 backdrop-blur-sm border border-white/30 text-white px-7 py-3.5 rounded-lg font-semibold hover:bg-white/20 hover:-translate-y-0.5 transition-all duration-300">
                        Devenir répétiteur
                    </a>
                </div>
                <div class="flex flex-wrap items-center gap-6">
                    <div class="flex items-center gap-2 text-white text-sm drop-shadow">
                        <x-icons.check-badge class="w-5 h-5 text-mm-accent" />
                        Sélection rigoureuse
                    </div>
                    <div class="flex items-center gap-2 text-white text-sm drop-shadow">
                        <x-icons.check-badge class="w-5 h-5 text-mm-accent" />
                        Suivi personnalisé
                    </div>
                    <div class="flex items-center gap-2 text-white text-sm drop-shadow">
                        <x-icons.check-badge class="w-5 h-5 text-mm-accent" />
                        Côte d'Ivoire
                    </div>
                </div>
            </div>
        </div>

        {{-- NAVIGATION CAROUSEL --}}
        <button type="button" @click="prev()"
            class="hidden md:flex absolute left-6 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/10 backdrop-blur-sm text-white items-center justify-center hover:bg-white/20 hover:scale-110 transition-all duration-300 z-20">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <button type="button" @click="next()"
            class="hidden md:flex absolute right-6 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/10 backdrop-blur-sm text-white items-center justify-center hover:bg-white/20 hover:scale-110 transition-all duration-300 z-20">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>

        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex items-center gap-2 z-20">
            @foreach ($slides as $i => $slide)
                <button type="button" @click="goTo({{ $i }})"
                    :class="currentSlide === {{ $i }} ? 'w-10 bg-mm-accent' : 'w-2.5 bg-white/50 hover:bg-white/80'"
                    class="h-2.5 rounded-full transition-all duration-300"></button>
            @endforeach
        </div>
    </section>

    <script>
        function carousel() {
            return {
                currentSlide: 0,
                totalSlides: 3,
                autoplayInterval: null,
                init() { this.startAutoplay(); },
                next() { this.currentSlide = (this.currentSlide + 1) % this.totalSlides; this.restartAutoplay(); },
                prev() { this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides; this.restartAutoplay(); },
                goTo(index) { this.currentSlide = index; this.restartAutoplay(); },
                startAutoplay() { this.autoplayInterval = setInterval(() => { this.currentSlide = (this.currentSlide + 1) % this.totalSlides; }, 6000); },
                restartAutoplay() { clearInterval(this.autoplayInterval); this.startAutoplay(); }
            }
        }
    </script>
{{-- ============ COMMENT ÇA MARCHE (avec vidéo verticale) ============ --}}
<section id="fonctionnement" class="py-16 bg-white">
    <div class="max-w-6xl mx-auto px-6 md:px-12">
        <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">

            {{-- ====== COLONNE GAUCHE : Titre + 4 étapes ====== --}}
            <div class="animate-on-scroll">
                <p class="text-mm-secondary text-sm font-semibold uppercase tracking-wider mb-2">Fonctionnement</p>
                <h2 class="text-3xl md:text-4xl font-bold text-mm-navy tracking-tight mb-4">Comment ça marche ?</h2>
                <p class="text-gray-500 mb-8">Un processus simple, contrôlé et personnalisé à chaque étape par MON MAÎTRE.</p>

                @php
                    $steps = [
                        ['num' => '01', 'title' => 'Vous décrivez le besoin', 'desc' => 'Classe, matières, objectifs, disponibilités, adresse.'],
                        ['num' => '02', 'title' => 'Nous analysons', 'desc' => 'Nous examinons votre demande et recherchons dans notre base.'],
                        ['num' => '03', 'title' => 'Nous sélectionnons', 'desc' => 'Nous choisissons le répétiteur le plus adapté et le contactons.'],
                        ['num' => '04', 'title' => 'Vous validez', 'desc' => 'Nous vous proposons le profil. Vous acceptez et le placement démarre.'],
                    ];
                @endphp

                <div class="space-y-4">
                    @foreach ($steps as $i => $step)
                        <div class="animate-on-scroll delay-{{ ($i + 1) * 100 }} flex items-start gap-4 bg-gray-50 rounded-2xl p-5 border border-gray-100 hover:border-mm-accent hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-mm-accent flex items-center justify-center">
                                <span class="text-lg font-bold text-mm-navy">{{ $step['num'] }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-mm-navy mb-1">{{ $step['title'] }}</h3>
                                <p class="text-sm text-gray-600 leading-relaxed">{{ $step['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ====== COLONNE DROITE : Vidéo verticale 1080x1920 ====== --}}
            <div class="animate-on-scroll delay-200 flex justify-center lg:justify-end">
                <div class="relative w-full max-w-[320px] md:max-w-[360px]">

                    {{-- Décor arrière (cercle doré) --}}
                    <div class="absolute -top-6 -right-6 w-32 h-32 rounded-full bg-mm-accent/20 blur-2xl"></div>
                    <div class="absolute -bottom-6 -left-6 w-40 h-40 rounded-full bg-mm-secondary/15 blur-2xl"></div>

                    {{-- Cadre vidéo (ratio 9:16) --}}
                    <div class="relative rounded-3xl overflow-hidden shadow-2xl shadow-mm-navy/30 border-4 border-white bg-mm-navy"
                         style="aspect-ratio: 9 / 16;">

                        <video
                            class="w-full h-full object-cover"
                            autoplay
                            muted
                            loop
                            playsinline
                            preload="metadata"
                            poster="{{ asset('images/video/fonctionnement-poster.jpg') }}">
                            <source src="{{ asset('images/video/fonctionnement.mp4') }}" type="video/mp4">
                            <source src="{{ asset('images/video/fonctionnement.webm') }}" type="video/webm">
                            Votre navigateur ne supporte pas la lecture vidéo.
                        </video>

                        {{-- Overlay dégradé en bas (pour lisibilité si on ajoute du texte) --}}
                        <div class="absolute inset-x-0 bottom-0 h-32 pointer-events-none"
                             style="background: linear-gradient(to top, rgba(6, 43, 92, 0.6) 0%, transparent 100%);">
                        </div>

                        {{-- Badge "Watch" ou "Comment ça marche" --}}
                        <div class="absolute top-4 left-4 inline-flex items-center gap-2 bg-mm-navy/80 backdrop-blur-sm text-mm-accent text-[11px] font-bold px-3 py-1.5 rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-mm-accent animate-pulse"></span>
                            Comment ça marche
                        </div>
                    </div>

                    {{-- Légende sous la vidéo --}}
                    <p class="text-center text-xs text-gray-500 mt-4 leading-relaxed">
                        🎬 Découvrez notre processus en 60 secondes
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>
       {{-- ============ SERVICES (avec PARALLAX optimisé) ============ --}}
        <section id="services" class="relative py-20 overflow-hidden">
            <div class="absolute inset-0 will-change-transform"
                style="
                    background-image: url('{{ asset('images/carousel/slide-1.jpg') }}');
                    background-size: cover;
                    background-position: center;
                    background-attachment: fixed;
                    transform: translate3d(0, 0, 0);
                    backface-visibility: hidden;
                ">
            </div>

        <div class="absolute inset-0"
             style="background: linear-gradient(135deg, rgba(6, 43, 92, 0.92) 0%, rgba(20, 59, 143, 0.88) 50%, rgba(11, 61, 145, 0.92) 100%);">
        </div>

        <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-mm-accent/10 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 rounded-full bg-mm-secondary/30 blur-3xl"></div>

                <div class="relative z-10 max-w-6xl mx-auto px-6 md:px-12 py-8">
            <div class="text-center mb-12 animate-on-scroll">
                <p class="text-mm-accent text-sm font-semibold uppercase tracking-wider mb-2">Nos services</p>
                <h2 class="text-3xl md:text-4xl font-bold text-white tracking-tight mb-4">Ce que MON MAÎTRE fait pour vous</h2>
                <p class="text-gray-200 max-w-2xl mx-auto">Un service complet, du premier contact jusqu'au suivi de la prestation.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                @php
                    $services = [
                        ['icon' => 'search', 'title' => 'Recherche ciblée', 'desc' => 'Nous recherchons le répétiteur qui correspond précisément à votre besoin.'],
                        ['icon' => 'check-badge', 'title' => 'Sélection rigoureuse', 'desc' => 'Chaque candidat est vérifié : diplômes, expérience, références.'],
                        ['icon' => 'target', 'title' => 'Suivi personnalisé', 'desc' => 'Nous restons en contact avec le parent et le répétiteur pendant la mission.'],
                        ['icon' => 'document', 'title' => 'Base de données', 'desc' => 'Une base centralisée de profils qualifiés à travers la Côte d\'Ivoire.'],
                        ['icon' => 'users', 'title' => 'Remplacement rapide', 'desc' => 'En cas de problème, nous trouvons rapidement un nouveau répétiteur.'],
                        ['icon' => 'bell', 'title' => 'Communication claire', 'desc' => 'Un interlocuteur unique : MON MAÎTRE, entre le parent et le répétiteur.'],
                    ];
                @endphp
                @foreach ($services as $i => $service)
                    <div class="animate-on-scroll delay-{{ min(($i % 3 + 1) * 100, 300) }}
                                bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20
                                hover:bg-white/15 hover:border-mm-accent/50 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 group">
                        <div class="w-12 h-12 rounded-xl bg-mm-accent flex items-center justify-center mb-4 transition-transform duration-300 group-hover:scale-110">
                            <x-dynamic-component :component="'icons.' . $service['icon']" class="w-6 h-6 text-mm-navy" />
                        </div>
                        <h3 class="font-bold text-white mb-2">{{ $service['title'] }}</h3>
                        <p class="text-sm text-gray-200 leading-relaxed">{{ $service['desc'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-10 animate-on-scroll">
                <a href="{{ route('pre-inscription.parent') }}"
                   class="inline-flex items-center justify-center gap-2 bg-mm-accent text-mm-navy px-7 py-3.5 rounded-lg font-semibold hover:bg-mm-accent-hover hover:shadow-lg hover:shadow-mm-accent/40 hover:-translate-y-0.5 transition-all duration-300">
                    Je cherche un répétiteur
                    <x-icons.arrow-right class="w-4 h-4" />
                </a>
            </div>
        </div>
    </section>

    {{-- ============ BLOG ============ --}}
    <section id="blog" class="py-16 bg-mm-light">
        <div class="max-w-6xl mx-auto px-6 md:px-12">

            {{-- En-tête blog --}}
            <div class="rounded-2xl p-8 md:p-10 mb-10 text-center animate-on-scroll"
                 style="background: linear-gradient(135deg, #062B5C 0%, #143B8F 100%);">
                <p class="text-mm-accent text-sm font-semibold uppercase tracking-wider mb-3">Blog MON MAÎTRE</p>
                <h2 class="text-2xl md:text-3xl font-bold text-white tracking-tight mb-3">
                    Guides et conseils pour la réussite scolaire
                </h2>
                <p class="text-gray-200 max-w-2xl mx-auto text-sm md:text-base">
                    Méthodes de révision, préparation aux examens, conseils pour les parents
                    et décryptage du système éducatif en Côte d'Ivoire.
                </p>
            </div>

            {{-- Article à la une --}}
            <div class="grid lg:grid-cols-2 gap-8 mb-10 animate-on-scroll">
                <div class="rounded-2xl overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=800&q=80"
                         alt="Calendrier des examens 2027"
                         class="w-full h-full object-cover min-h-[280px]">
                </div>
                <div class="flex flex-col justify-center">
                    <span class="inline-flex self-start items-center gap-1 bg-mm-accent/20 text-mm-navy text-[11px] font-bold px-2.5 py-1 rounded-md mb-4">
                        📌 À LA UNE
                    </span>
                    <h3 class="text-2xl md:text-3xl font-bold text-mm-navy mb-4 leading-tight">
                        Calendrier des examens scolaires 2027 en Côte d'Ivoire — CEPE, BEPC, BAC
                    </h3>
                    <p class="text-sm text-gray-500 mb-4">
                        12 mars 2027 • 8 min de lecture
                    </p>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        Toutes les dates officielles publiées par la DECO : CEPE en mai, BEPC en mai-juin,
                        BAC en juin. Calendrier complet, seuils d'admission, dates de résultats et conseils de préparation.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <a href="#" class="text-mm-accent font-bold text-sm hover:underline inline-flex items-center gap-1 group">
                            Lire l'article
                            <x-icons.arrow-right class="w-4 h-4 transition-transform group-hover:translate-x-1" />
                        </a>
                        <a href="https://www.men-deco.org" target="_blank" rel="noopener noreferrer"
                           class="text-mm-navy font-bold text-sm hover:underline inline-flex items-center gap-1 group">
                            <x-icons.document class="w-4 h-4" />
                            Consulter les résultats officiels (DECO)
                            <x-icons.arrow-right class="w-4 h-4 transition-transform group-hover:translate-x-1" />
                        </a>
                    </div>
                </div>
            </div>

            {{-- Cartes examens --}}
            @php
                $examens = [
                    [
                        'header_bg'    => 'bg-mm-secondary',
                        'header_text'  => 'text-white',
                        'date_bg'      => 'bg-white/20',
                        'cta_color'    => 'text-mm-secondary',
                        'border'       => 'hover:border-mm-secondary',
                        'badge'        => 'CEPE 2027',
                        'date'         => 'Mai 2027, dates à confirmer',
                        'desc'         => 'Préparez l\'entrée en 6ème de votre enfant avec un accompagnement ciblé en français, mathématiques et éveil. Seuil d\'admission : 85 points.',
                        'cta'          => 'Préparer le CEPE',
                    ],
                    [
                        'header_bg'    => 'bg-mm-accent',
                        'header_text'  => 'text-mm-navy',
                        'date_bg'      => 'bg-mm-navy/10',
                        'cta_color'    => 'text-mm-accent',
                        'border'       => 'hover:border-mm-accent',
                        'badge'        => 'BEPC 2027',
                        'date'         => 'Mai 2027, dates à confirmer',
                        'desc'         => 'Révision complète des épreuves écrites et orales. Annales corrigées, simulations d\'examen et séances de révision intensives.',
                        'cta'          => 'Préparer le BEPC',
                    ],
                    [
                        'header_bg'    => 'bg-mm-navy',
                        'header_text'  => 'text-white',
                        'date_bg'      => 'bg-white/20',
                        'cta_color'    => 'text-mm-navy',
                        'border'       => 'hover:border-mm-navy',
                        'badge'        => 'BAC 2027',
                        'date'         => 'Juin 2027, dates à confirmer',
                        'desc'         => 'Préparation intensive toutes séries (A, B, C, D, E, H) et programme français. Par matière, par coefficient, avec un plan de révision personnalisé.',
                        'cta'          => 'Préparer le BAC',
                    ],
                ];
            @endphp

            <div class="grid md:grid-cols-3 gap-6">
                @foreach($examens as $i => $examen)
                    <div class="bg-white rounded-2xl overflow-hidden border-2 border-gray-100 {{ $examen['border'] }} hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 animate-on-scroll delay-{{ ($i + 1) * 100 }}">

                        <div class="{{ $examen['header_bg'] }} p-6 text-center">
                            <h3 class="{{ $examen['header_text'] }} font-bold text-xl mb-3">
                                {{ $examen['badge'] }}
                            </h3>
                            <span class="inline-flex items-center gap-1 {{ $examen['date_bg'] }} {{ $examen['header_text'] }} text-[11px] font-semibold px-2.5 py-1 rounded-md">
                                 {{ $examen['date'] }}
                            </span>
                        </div>

                        <div class="p-6">
                            <p class="text-sm text-gray-600 leading-relaxed mb-5">
                                {{ $examen['desc'] }}
                            </p>
                            <a href="#" class="{{ $examen['cta_color'] }} font-bold text-sm hover:underline inline-flex items-center gap-1 group">
                                {{ $examen['cta'] }}
                                <x-icons.arrow-right class="w-4 h-4 transition-transform group-hover:translate-x-1" />
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Bandeau DECO — Consulter les résultats officiels --}}
            <div class="mt-10 rounded-2xl p-6 md:p-8 border-2 border-mm-accent/40 bg-gradient-to-r from-mm-accent/10 to-mm-secondary/10 animate-on-scroll">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-mm-navy flex items-center justify-center">
                            <x-icons.document class="w-6 h-6 text-mm-accent" />
                        </div>
                        <div>
                            <h3 class="font-bold text-mm-navy text-lg mb-1">
                                Consultez vos résultats d'examens officiels
                            </h3>
                            <p class="text-sm text-gray-600 leading-relaxed">
                                Résultats CEPE, BEPC, BAC disponibles directement sur le site de la
                                <strong class="text-mm-navy">Direction des Examens et Concours (DECO)</strong>.
                            </p>
                        </div>
                    </div>
                    <a href="https://www.men-deco.org" target="_blank" rel="noopener noreferrer"
                       class="flex-shrink-0 inline-flex items-center justify-center gap-2 bg-mm-navy text-white px-6 py-3 rounded-lg font-semibold text-sm hover:bg-mm-navy-hover hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                        Accéder à la DECO
                        <x-icons.arrow-right class="w-4 h-4" />
                    </a>
                </div>
            </div>

        </div>
    </section>

           {{-- ============ NOS RÉPÉTITEURS (fond beige + flou) ============ --}}
    @if($repetiteurs->count() > 0)
        <section id="repetiteurs" class="relative py-16 overflow-hidden">

            {{-- Image de fond (subtile) --}}
            <div class="absolute inset-0"
                 style="background-image: url('{{ asset('images/carousel/slide-2.jpg') }}'); background-size: cover; background-position: center; filter: blur(8px); transform: scale(1.05);">
            </div>

            {{-- Voile beige translucide par-dessus (crée l'effet flou pastel) --}}
            <div class="absolute inset-0" style="background-color: rgba(253, 246, 233, 0.85);"></div>

            {{-- Décor (cercles flous dorés) --}}
            <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-mm-accent/15 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 rounded-full bg-mm-accent/10 blur-3xl"></div>

            <div class="relative z-10 max-w-6xl mx-auto px-6 md:px-12">

                {{-- En-tête --}}
                <div class="text-center mb-12 animate-on-scroll">
                    <p class="text-mm-accent text-sm font-semibold uppercase tracking-wider mb-2">Nos répétiteurs</p>
                    <h2 class="text-3xl md:text-4xl font-bold text-mm-navy tracking-tight mb-4">
                        Trouvez l'enseignant adapté à votre enfant
                    </h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">
                        {{ $repetiteurs->count() }} profil(s) publié(s) — Sélectionnés et validés par MON MAÎTRE.
                    </p>
                </div>

                {{-- Grille des profils (cartes glassmorphism renforcées) --}}
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 justify-center">
                    @foreach($repetiteurs as $i => $rep)
                        <div class="bg-white/85 backdrop-blur-md rounded-2xl overflow-hidden border border-white/60 shadow-lg hover:shadow-2xl hover:-translate-y-2 hover:bg-white/95 transition-all duration-300 animate-on-scroll delay-{{ min(($i + 1) * 100, 400) }} group">

                            {{-- Photo de profil --}}
                            <div class="relative aspect-[4/3] overflow-hidden bg-gray-100">
                                <img src="{{ $rep->photo_url }}"
                                     alt="{{ $rep->user->name }}"
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">

                                {{-- Badge "Profil certifié" --}}
                                <span class="absolute top-3 left-3 inline-flex items-center gap-1 bg-mm-navy text-mm-accent text-[10px] font-bold px-2.5 py-1 rounded-full shadow-lg">
                                    ✓ Profil certifié
                                </span>
                            </div>

                            {{-- Infos --}}
                            <div class="p-5">
                                <h3 class="font-bold text-mm-navy mb-2 text-base">{{ $rep->user->name }}</h3>

                                {{-- Matières --}}
                                @php $matieres = array_slice($rep->matieres ?? [], 0, 3); @endphp
                                <p class="text-xs text-gray-600 mb-2 flex items-start gap-1.5">
                                    <x-icons.academic class="w-3.5 h-3.5 mt-0.5 flex-shrink-0 text-mm-accent" />
                                    <span>
                                        {{ implode(' • ', $matieres) }}
                                        @if(count($rep->matieres ?? []) > 3)
                                            <span class="text-gray-400">+{{ count($rep->matieres) - 3 }}</span>
                                        @endif
                                    </span>
                                </p>

                                {{-- Note --}}
                                @if($rep->note_moyenne > 0)
                                    <p class="text-xs mb-2 flex items-center gap-1">
                                        <span class="text-mm-accent">★★★★★</span>
                                        <span class="font-bold text-mm-navy">{{ $rep->note_moyenne }}</span>
                                        <span class="text-gray-500">({{ $rep->nb_avis }} avis)</span>
                                    </p>
                                @else
                                    <p class="text-xs text-gray-400 italic mb-2">Pas encore d'avis</p>
                                @endif

                                {{-- Localisation --}}
                                <p class="text-xs text-gray-500 mb-1 flex items-center gap-1.5">
                                    <x-icons.target class="w-3.5 h-3.5 text-mm-accent flex-shrink-0" />
                                    <span>
                                        {{ $rep->user->commune ?? 'Abidjan' }}
                                        @if($rep->user->quartier)
                                            , {{ $rep->user->quartier }}
                                        @endif
                                    </span>
                                </p>

                                {{-- Niveau --}}
                                <p class="text-xs text-gray-500 mb-4 flex items-center gap-1.5">
                                    <x-icons.check-badge class="w-3.5 h-3.5 text-mm-accent flex-shrink-0" />
                                    <span>
                                        {{ $rep->niveau_min ?? 'Tous niveaux' }}
                                        @if($rep->niveau_max)
                                            → {{ $rep->niveau_max }}
                                        @endif
                                    </span>
                                </p>

                                {{-- CTA --}}
                                <a href="{{ route('pre-inscription.parent') }}"
                                   class="block text-center bg-mm-navy text-white text-sm font-bold py-2.5 rounded-lg hover:bg-mm-navy-hover hover:-translate-y-0.5 transition-all duration-300">
                                    Demander ce profil
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- CTA sous la section --}}
                <div class="text-center mt-12 animate-on-scroll">
                    <a href="{{ route('pre-inscription.parent') }}"
                       class="inline-flex items-center gap-2 bg-mm-navy text-white px-7 py-3.5 rounded-lg font-semibold hover:bg-mm-navy-hover hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                        Créer ma demande
                        <x-icons.arrow-right class="w-4 h-4" />
                    </a>
                    <p class="text-xs text-gray-500 mt-4">
                        Vous êtes enseignant ?
                        <a href="{{ route('pre-inscription.repetiteur') }}" class="text-mm-accent hover:text-mm-navy font-semibold transition">
                            Inscrivez-vous
                        </a>
                    </p>
                </div>

            </div>
        </section>
    @endif

    {{-- ============ NOS VALEURS ============ --}}
    <section class="py-16 bg-white">
        <div class="max-w-6xl mx-auto px-6 md:px-12">
            <div class="text-center mb-12 animate-on-scroll">
                <p class="text-mm-secondary text-sm font-semibold uppercase tracking-wider mb-2">Nos valeurs</p>
                <h2 class="text-3xl md:text-4xl font-bold text-mm-navy tracking-tight mb-4">Ce qui anime notre équipe</h2>
                <p class="text-gray-500 max-w-2xl mx-auto">Au quotidien, pour transformer le système éducatif en Côte d'Ivoire.</p>
            </div>

            @php
                $valeurs = [
                    ['icon' => 'academic',    'title' => 'Excellence académique',       'desc' => 'Nous visons les plus hauts standards pour garantir la réussite durable des élèves à chaque étape.'],
                    ['icon' => 'check-badge', 'title' => 'Transparence totale',         'desc' => 'Des informations claires et vérifiées sur chaque enseignant — sans opacité ni surprise.'],
                    ['icon' => 'target',      'title' => 'Confiance absolue',           'desc' => 'Un environnement sécurisé où chaque profil est sélectionné et validé avec rigueur.'],
                    ['icon' => 'cog',         'title' => 'Innovation constante',        'desc' => 'Des outils digitaux modernes pour simplifier et enrichir chaque parcours éducatif.'],
                    ['icon' => 'users',       'title' => 'Accessibilité pour tous',     'desc' => 'Rendre une éducation de qualité disponible partout en Côte d\'Ivoire, sans frontières.'],
                    ['icon' => 'user-circle', 'title' => 'Accompagnement bienveillant', 'desc' => 'Une équipe à l\'écoute pour aider chaque enfant à révéler son potentiel unique.'],
                ];
            @endphp

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($valeurs as $i => $valeur)
                    <div class="animate-on-scroll delay-{{ min(($i % 3 + 1) * 100, 300) }}
                                bg-white rounded-2xl p-6 border border-gray-100
                                hover:border-mm-accent hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                        <div class="w-12 h-12 rounded-xl bg-mm-navy flex items-center justify-center mb-4 transition-transform duration-300 group-hover:scale-110">
                            <x-dynamic-component :component="'icons.' . $valeur['icon']" class="w-6 h-6 text-mm-accent" />
                        </div>
                        <h3 class="font-bold text-mm-navy mb-2">{{ $valeur['title'] }}</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $valeur['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ FAQ ============ --}}
    <section id="faq" class="py-16 bg-mm-light">
        <div class="max-w-4xl mx-auto px-6 md:px-12">
            <div class="text-center mb-12 animate-on-scroll">
                <p class="text-mm-secondary text-sm font-semibold uppercase tracking-wider mb-2">FAQ</p>
                <h2 class="text-3xl md:text-4xl font-bold text-mm-navy tracking-tight mb-4">Questions fréquentes</h2>
                <p class="text-gray-500 max-w-2xl mx-auto">Vous avez une question ? Voici les réponses les plus courantes.</p>
            </div>

            @php
                $faqs = [
                    ['q' => 'Comment fonctionne MON MAÎTRE exactement ?', 'r' => 'Vous décrivez votre besoin (élève, matières, disponibilités). Nous analysons votre demande, sélectionnons dans notre base le répétiteur le plus adapté, vous proposons son profil, et une fois validé, le placement démarre. Nous assurons le suivi pendant toute la durée de la mission.'],
                    ['q' => 'Comment sont sélectionnés vos répétiteurs ?', 'r' => 'Chaque candidat est soumis à une vérification rigoureuse : diplômes, expérience, références, entretien. Seuls les profils validés par notre équipe peuvent recevoir des propositions.'],
                    ['q' => 'Que se passe-t-il si le répétiteur ne convient pas ?', 'r' => 'Vous pouvez demander un remplacement à tout moment. MON MAÎTRE recherche alors un nouveau profil adapté à votre besoin.'],
                    ['q' => 'Les cours se passent-ils uniquement à domicile ?', 'r' => 'Les cours se déroulent principalement à votre domicile ou dans un lieu convenu ensemble. Selon les besoins, certains répétiteurs peuvent également proposer des séances en ligne.'],
                    ['q' => 'Combien de temps pour trouver un répétiteur ?', 'r' => 'En moyenne, nous proposons un profil sous 48 à 72 heures après réception de votre demande complète.'],
                ];
            @endphp

            <div class="space-y-3 animate-on-scroll">
                @foreach ($faqs as $i => $faq)
                    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden transition-all duration-300 hover:border-mm-accent"
                         x-data="{ open: false }">
                        <button type="button" @click="open = !open"
                                class="w-full px-6 py-5 flex items-center justify-between gap-4 text-left hover:bg-gray-50 transition">
                            <span class="font-semibold text-mm-navy text-base pr-4">{{ $faq['q'] }}</span>
                            <span class="flex-shrink-0 w-8 h-8 rounded-lg bg-mm-accent/20 flex items-center justify-center transition-transform duration-300"
                                  :class="{ 'rotate-45': open }">
                                <x-icons.plus class="w-4 h-4 text-mm-navy" />
                            </span>
                        </button>
                        <div x-show="open" x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-2">
                            <div class="px-6 pb-5 pt-0">
                                <p class="text-gray-600 leading-relaxed">{{ $faq['r'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-10 animate-on-scroll">
                <p class="text-gray-500 mb-4">Vous ne trouvez pas votre réponse ?</p>
                <a href="#contact"
                   class="inline-flex items-center gap-2 bg-mm-navy text-white px-7 py-3.5 rounded-lg font-semibold hover:bg-mm-navy-hover hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                    Contactez-nous
                    <x-icons.arrow-right class="w-4 h-4" />
                </a>
            </div>
        </div>
    </section>

        {{-- ============ CONTACT (fond clair, version premium) ============ --}}
    <section id="contact" class="relative py-20 overflow-hidden bg-white">

        {{-- Décor --}}
        <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-mm-accent/10 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 rounded-full bg-mm-secondary/10 blur-3xl"></div>

        <div class="relative z-10 max-w-6xl mx-auto px-6 md:px-12">

            {{-- Bandeau d'urgence --}}
            <div class="text-center mb-8 animate-on-scroll">
                <span class="inline-flex items-center gap-2 bg-mm-accent/15 text-mm-navy text-xs font-bold uppercase tracking-wider px-4 py-2 rounded-full border border-mm-accent/30">
                    <span class="w-2 h-2 rounded-full bg-mm-accent animate-pulse"></span>
                    Réponse en moins de 2h
                </span>
            </div>

            {{-- En-tête --}}
            <div class="text-center mb-14 animate-on-scroll">
                <h2 class="text-3xl md:text-5xl font-bold text-mm-navy tracking-tight mb-4">
                    Parlons de votre <span class="text-mm-accent">besoin</span>
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">
                    Une question ? Un projet ? Un enfant qui a besoin d'un coup de pouce ?<br>
                    <strong class="text-mm-navy">Notre équipe vous répond personnellement.</strong>
                </p>
            </div>

            {{-- CTA principal --}}
            <div class="text-center mb-14 animate-on-scroll">
                <a href="tel:+2250719167688"
                   class="inline-flex items-center justify-center gap-3 bg-mm-navy text-white px-8 py-4 rounded-xl font-bold text-lg hover:bg-mm-navy-hover hover:shadow-2xl hover:shadow-mm-navy/30 hover:-translate-y-1 transition-all duration-300">
                    <x-icons.phone class="w-6 h-6 text-mm-accent" />
                    Appeler MON MAÎTRE maintenant
                </a>
                <p class="text-xs text-gray-400 mt-3">
                    Ou continuez à lire pour choisir votre canal préféré ↓
                </p>
            </div>

            {{-- 3 cartes --}}
            <div class="grid md:grid-cols-3 gap-6">

                {{-- Téléphone --}}
                <a href="tel:+2250719167688"
                   class="bg-white rounded-2xl p-8 text-center border-2 border-gray-100 hover:border-mm-accent hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 group">
                    <div class="w-16 h-16 rounded-2xl bg-mm-navy flex items-center justify-center mx-auto mb-5 transition-transform duration-300 group-hover:scale-110">
                        <x-icons.phone class="w-8 h-8 text-mm-accent" />
                    </div>
                    <h3 class="font-bold text-mm-navy text-lg mb-2">Par téléphone</h3>
                    <p class="text-gray-500 text-sm mb-4">Du lundi au samedi<br>8h – 20h</p>
                    <p class="font-bold text-mm-navy text-xl">07 19 16 76 88</p>
                    <span class="inline-flex items-center gap-1 mt-4 text-xs text-mm-accent font-semibold group-hover:text-mm-navy transition">
                        Appeler maintenant
                        <x-icons.arrow-right class="w-3.5 h-3.5 transition-transform group-hover:translate-x-1" />
                    </span>
                </a>

                {{-- WhatsApp --}}
                <a href="https://wa.me/2250719167688" target="_blank"
                   class="bg-white rounded-2xl p-8 text-center border-2 border-gray-100 hover:border-green-400 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 group">
                    <div class="w-16 h-16 rounded-2xl bg-green-500 flex items-center justify-center mx-auto mb-5 transition-transform duration-300 group-hover:scale-110">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-mm-navy text-lg mb-2">Par WhatsApp</h3>
                    <p class="text-gray-500 text-sm mb-4">Réponse ultra rapide<br>en journée</p>
                    <p class="font-bold text-green-600 text-xl">Discuter →</p>
                    <span class="inline-flex items-center gap-1 mt-4 text-xs text-green-600 font-semibold group-hover:text-green-700 transition">
                        Ouvrir WhatsApp
                        <x-icons.arrow-right class="w-3.5 h-3.5 transition-transform group-hover:translate-x-1" />
                    </span>
                </a>

                {{-- Email --}}
                <a href="mailto:contact@monmaitre.ci"
                   class="bg-white rounded-2xl p-8 text-center border-2 border-gray-100 hover:border-mm-secondary hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 group">
                    <div class="w-16 h-16 rounded-2xl bg-mm-secondary flex items-center justify-center mx-auto mb-5 transition-transform duration-300 group-hover:scale-110">
                        <x-icons.envelope class="w-8 h-8 text-white" />
                    </div>
                    <h3 class="font-bold text-mm-navy text-lg mb-2">Par email</h3>
                    <p class="text-gray-500 text-sm mb-4">Réponse sous<br>24h ouvrées</p>
                    <p class="font-bold text-mm-navy text-lg">contact@<br>monmaitre.ci</p>
                    <span class="inline-flex items-center gap-1 mt-4 text-xs text-mm-secondary font-semibold group-hover:text-mm-navy transition">
                        Envoyer un email
                        <x-icons.arrow-right class="w-3.5 h-3.5 transition-transform group-hover:translate-x-1" />
                    </span>
                </a>
            </div>

            {{-- Mention de confiance --}}
            <div class="text-center mt-12 animate-on-scroll">
                <div class="flex flex-wrap items-center justify-center gap-6 text-xs text-gray-500">
                    <span class="inline-flex items-center gap-1.5">
                        <x-icons.check-badge class="w-4 h-4 text-mm-accent" />
                        Basé en Côte d'Ivoire
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <x-icons.check-badge class="w-4 h-4 text-mm-accent" />
                        Équipe à l'écoute
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <x-icons.check-badge class="w-4 h-4 text-mm-accent" />
                        Réponse rapide garantie
                    </span>
                </div>
            </div>

        </div>
    </section>

    {{-- ============ CTA FINAL ============ --}}
    <section class="py-16" style="background: linear-gradient(135deg, #062B5C 0%, #143B8F 100%);">
        <div class="max-w-4xl mx-auto px-6 md:px-12 text-center animate-on-scroll">
            <h2 class="text-3xl md:text-4xl font-bold text-white tracking-tight mb-4">Prêt à trouver le bon répétiteur ?</h2>
            <p class="text-gray-200 mb-8 max-w-2xl mx-auto">Créez votre demande en quelques minutes et laissez MON MAÎTRE s'occuper du reste.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('pre-inscription.parent') }}"
                   class="inline-flex items-center justify-center gap-2 bg-mm-accent text-mm-navy px-7 py-3.5 rounded-lg font-semibold hover:bg-mm-accent-hover hover:shadow-lg hover:shadow-mm-accent/40 hover:-translate-y-0.5 transition-all duration-300">
                    Je cherche un répétiteur
                    <x-icons.arrow-right class="w-4 h-4" />
                </a>
                <a href="{{ route('pre-inscription.repetiteur') }}"
                   class="inline-flex items-center justify-center gap-2 bg-white/5 border border-white/20 text-white px-7 py-3.5 rounded-lg font-semibold hover:bg-white/10 hover:-translate-y-0.5 transition-all duration-300">
                    Devenir répétiteur
                </a>
            </div>
        </div>
    </section>

@endsection