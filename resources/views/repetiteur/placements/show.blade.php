@extends('layouts.repetiteur')

@section('title', 'Mission ' . $placement->reference)

@section('content')

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm animate-on-scroll">
            {{ session('success') }}
        </div>
    @endif

    {{-- Fil d'Ariane --}}
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6 animate-on-scroll">
        <a href="{{ route('repetiteur.placements.index') }}" class="hover:text-[#132D39] transition">Mes missions</a>
        <span>/</span>
        <span class="text-[#132D39] font-medium font-mono">{{ $placement->reference }}</span>
    </div>

    {{-- En-tête --}}
    <div class="mb-8 flex justify-between items-start flex-wrap gap-4 animate-on-scroll">
        <div>
            <p class="text-sm text-[#55766F] font-semibold uppercase tracking-wider mb-1">Mission</p>
            <h2 class="text-3xl font-bold text-[#132D39] font-mono">{{ $placement->reference }}</h2>
        </div>
        <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold {{ $placement->statutCouleur() }}">
            {{ $placement->statutLabel() }}
        </span>
    </div>

    <div class="space-y-6">

        {{-- Élève --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6 animate-on-scroll">
            <h3 class="font-bold text-[#132D39] mb-5 flex items-center gap-2">
                <x-icons.academic class="w-5 h-5 text-[#55766F]" />
                Élève
            </h3>
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full bg-[#55766F] flex items-center justify-center text-white font-bold text-xl flex-shrink-0">
                    {{ strtoupper(substr($placement->eleve->prenom, 0, 1)) }}
                </div>
                <div>
                    <p class="font-bold text-[#132D39] text-lg">{{ $placement->eleve->prenom }} {{ $placement->eleve->nom }}</p>
                    <p class="text-sm text-gray-500">{{ $placement->eleve->classe }}</p>
                    @if($placement->eleve->etablissement)
                        <p class="text-xs text-gray-500 mt-1">{{ $placement->eleve->etablissement }}</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Détails de la mission --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6 animate-on-scroll delay-100">
            <h3 class="font-bold text-[#132D39] mb-5 flex items-center gap-2">
                <x-icons.document class="w-5 h-5 text-[#55766F]" />
                Détails de la mission
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-sm">
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Matière</p>
                    <p class="font-semibold text-[#132D39]">{{ $placement->matiere }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Date de début</p>
                    <p class="font-semibold text-[#132D39]">{{ $placement->date_debut?->format('d/m/Y') ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Fréquence</p>
                    <p class="font-semibold text-[#132D39]">{{ $placement->frequence ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Horaires</p>
                    <p class="font-semibold text-[#132D39]">{{ $placement->horaires ?? '—' }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Adresse</p>
                    <p class="font-semibold text-[#132D39]">{{ $placement->adresse }}</p>
                </div>
                @if ($placement->tarif_convenu)
                    <div class="md:col-span-2">
                        <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Tarif convenu</p>
                        <p class="font-semibold text-[#132D39] text-lg">{{ number_format($placement->tarif_convenu, 0, ',', ' ') }} FCFA</p>
                    </div>
                @endif
            </div>
        </div>

                {{-- Suivi des séances --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6 animate-on-scroll delay-300">
            <h3 class="font-bold text-mm-navy mb-3 flex items-center gap-2">
                <x-icons.clock class="w-5 h-5 text-mm-secondary" />
                Suivi des séances
            </h3>
            <p class="text-sm text-gray-500 mb-4">
                Enregistrez chaque séance effectuée pour assurer un suivi rigoureux.
            </p>
            <a href="{{ route('repetiteur.seances.index', $placement) }}"
               class="w-full inline-flex items-center justify-center gap-2 bg-mm-secondary text-white text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-mm-secondary-hover hover:-translate-y-0.5 transition-all duration-300">
                Voir les séances
                <x-icons.arrow-right class="w-4 h-4" />
            </a>
        </div>

        {{-- Contact MON MAÎTRE --}}
        <div class="bg-[#E7C273]/20 border border-[#E7C273] rounded-2xl p-6 animate-on-scroll delay-200">
            <h3 class="font-bold text-[#132D39] mb-2">Question sur cette mission ?</h3>
            <p class="text-sm text-[#132D39] mb-3">
                Contactez MON MAÎTRE pour toute question ou information complémentaire.
            </p>
            <a href="mailto:contact@monmaitre.ci" class="inline-flex items-center gap-2 text-sm font-semibold text-[#132D39] hover:text-[#55766F] transition">
                <x-icons.envelope class="w-4 h-4" />
                contact@monmaitre.ci
            </a>
        </div>

    </div>
            {{-- Évaluation reçue (si elle existe) --}}
        @if ($placement->evaluation)
            <div class="bg-white rounded-2xl border border-mm-accent/40 p-6 animate-on-scroll delay-200">
                <h3 class="font-bold text-mm-navy mb-4 flex items-center gap-2">
                    <x-icons.check-badge class="w-5 h-5 text-mm-accent" />
                    Évaluation reçue
                </h3>
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 text-center">
                        <div class="text-5xl font-bold {{ $placement->evaluation->noteCouleur() }}">
                            {{ $placement->evaluation->note }}
                        </div>
                        <p class="text-xs text-gray-500 mt-1">sur 10</p>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold {{ $placement->evaluation->noteCouleur() }} mb-1">
                            {{ $placement->evaluation->noteLabel() }}
                        </p>
                        @if ($placement->evaluation->commentaire)
                            <p class="text-sm text-gray-700 italic">"{{ $placement->evaluation->commentaire }}"</p>
                        @endif
                    </div>
                </div>
            </div>
        @endif

@endsection