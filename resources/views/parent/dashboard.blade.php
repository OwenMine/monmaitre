@extends('layouts.parent')

@section('title', 'Tableau de bord')

@section('content')

    {{-- Message d'accueil --}}
    <div class="mb-8 animate-on-scroll">
        <h2 class="text-3xl font-bold text-mm-navy">Bonjour {{ auth()->user()->name }} 👋</h2>
        <p class="text-gray-500 mt-1">Bienvenue dans votre espace parent.</p>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm animate-on-scroll">
            {{ session('success') }}
        </div>
    @endif

    @php $demande = auth()->user()->derniereDemande; @endphp

    {{-- Dernière demande --}}
    <div class="card-hover mb-6 animate-on-scroll">
        @if ($demande)
            <div class="flex items-start justify-between flex-wrap gap-4">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-2">
                        Demande #MM-{{ str_pad($demande->id, 4, '0', STR_PAD_LEFT) }}
                    </p>
                    <p class="text-lg font-bold text-mm-navy">{{ $demande->statutLabel() }}</p>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ $demande->eleve->prenom }} {{ $demande->eleve->nom }} • {{ $demande->eleve->classe }}
                    </p>
                </div>
                <a href="{{ route('parent.demandes.show', $demande) }}"
                   class="inline-flex items-center gap-2 text-sm font-semibold text-mm-secondary hover:text-mm-navy transition group">
                    Voir ma demande
                    <x-icons.arrow-right class="w-4 h-4 transition-transform group-hover:translate-x-1" />
                </a>
            </div>
        @else
            <p class="text-gray-700 mb-4">
                Vous n'avez pas encore de demande en cours.
                Créez votre première demande pour que MON MAÎTRE recherche un répétiteur.
            </p>
            <a href="{{ route('parent.demandes.create') }}"
               class="inline-flex items-center gap-2 bg-mm-navy text-white px-6 py-3 rounded-lg font-semibold hover:bg-mm-navy-hover hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                Créer ma demande
                <x-icons.arrow-right class="w-4 h-4" />
            </a>
        @endif
    </div>

    {{-- Mes propositions --}}
    <div class="card-hover mb-6 animate-on-scroll delay-100">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h3 class="font-bold text-mm-navy">Mes propositions</h3>
                <p class="text-sm text-gray-500 mt-1">Consultez les profils proposés par MON MAÎTRE.</p>
            </div>
            <a href="{{ route('parent.propositions.index') }}"
               class="inline-flex items-center gap-2 bg-mm-secondary text-white text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-mm-secondary-hover hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                Voir mes propositions
                <x-icons.arrow-right class="w-4 h-4" />
            </a>
        </div>
    </div>

    {{-- Mes placements --}}
    <div class="card-hover mb-6 animate-on-scroll delay-200">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h3 class="font-bold text-mm-navy">Mes placements</h3>
                <p class="text-sm text-gray-500 mt-1">Suivez vos répétiteurs à domicile.</p>
            </div>
            <a href="{{ route('parent.placements.index') }}"
               class="inline-flex items-center gap-2 bg-mm-navy text-white text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-mm-navy-hover hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                Voir mes placements
                <x-icons.arrow-right class="w-4 h-4" />
            </a>
        </div>
    </div>

    {{-- ============ BLOC AIDE / APPEL ============ --}}
    <div class="bg-gradient-to-r from-mm-navy to-mm-secondary rounded-2xl p-6 md:p-8 text-white animate-on-scroll delay-300 relative overflow-hidden">

        {{-- Décor --}}
        <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-mm-accent/20 blur-3xl"></div>

        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-12 h-12 rounded-xl bg-mm-accent flex items-center justify-center">
                        <x-icons.phone class="w-6 h-6 text-mm-navy" />
                    </div>
                    <h3 class="text-xl font-bold">Besoin d'aide ?</h3>
                </div>
                <p class="text-gray-200 leading-relaxed">
                    Notre équipe MON MAÎTRE est disponible pour vous accompagner
                    dans vos démarches : créer une demande, comprendre une proposition,
                    ou toute autre question.
                </p>
            </div>

            <div class="flex-shrink-0">
                <a href="tel:+2250719167688"
                   class="inline-flex items-center gap-3 bg-mm-accent text-mm-navy px-6 py-3.5 rounded-lg font-bold text-sm hover:bg-mm-accent-hover hover:shadow-xl hover:shadow-mm-accent/30 hover:-translate-y-0.5 transition-all duration-300">
                    <x-icons.phone class="w-5 h-5" />
                    <span>07 19 16 76 88</span>
                </a>
                <p class="text-xs text-gray-300 mt-2 text-center">Appel direct • 7j/7</p>
            </div>
        </div>
    </div>

@endsection