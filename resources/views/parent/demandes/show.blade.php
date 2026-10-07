@extends('layouts.parent')

@section('title', 'Ma demande #MM-' . str_pad($demande->id, 4, '0', STR_PAD_LEFT) . ' — ' . $demande->eleve->prenom)

@section('content')

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm animate-on-scroll">
            {{ session('success') }}
        </div>
    @endif

    {{-- Fil d'Ariane --}}
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6 animate-on-scroll">
        <a href="{{ route('parent.demandes.index') }}" class="hover:text-[#132D39] transition">Mes demandes</a>
        <span>/</span>
        <span class="text-[#132D39] font-medium font-mono">#MM-{{ str_pad($demande->id, 4, '0', STR_PAD_LEFT) }}</span>
    </div>

    {{-- En-tête --}}
    <div class="mb-8 flex justify-between items-start flex-wrap gap-4 animate-on-scroll">
        <div>
            <h2 class="text-3xl font-bold text-[#132D39] font-mono">
                #MM-{{ str_pad($demande->id, 4, '0', STR_PAD_LEFT) }}
            </h2>
            <p class="text-gray-500 mt-1">
                Créée le {{ $demande->created_at->format('d/m/Y à H:i') }}
            </p>
        </div>
        <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold
            @if($demande->statut === 'demande_envoyee') bg-[#E7C273] text-[#132D39]
            @elseif($demande->statut === 'termine') bg-gray-100 text-gray-700
            @elseif($demande->statut === 'annule') bg-red-100 text-red-700
            @elseif(in_array($demande->statut, ['placement_confirme', 'en_cours'])) bg-green-100 text-green-700
            @else bg-[#55766F] text-white
            @endif">
            {{ $demande->statutLabel() }}
        </span>
    </div>

    <div class="space-y-6">

        {{-- Élève --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6 animate-on-scroll">
            <h3 class="font-bold text-[#132D39] mb-5 flex items-center gap-2">
                <x-icons.academic class="w-5 h-5 text-[#55766F]" />
                Élève
            </h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Nom complet</p>
                    <p class="font-semibold text-[#132D39]">{{ $demande->eleve->prenom }} {{ $demande->eleve->nom }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Âge</p>
                    <p class="font-semibold text-[#132D39]">{{ $demande->eleve->age ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Classe</p>
                    <p class="font-semibold text-[#132D39]">{{ $demande->eleve->classe }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Établissement</p>
                    <p class="font-semibold text-[#132D39]">{{ $demande->eleve->etablissement ?? '—' }}</p>
                </div>
            </div>
        </div>

        {{-- Besoin pédagogique --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6 animate-on-scroll delay-100">
            <h3 class="font-bold text-[#132D39] mb-5 flex items-center gap-2">
                <x-icons.document class="w-5 h-5 text-[#55766F]" />
                Besoin pédagogique
            </h3>
            <div class="space-y-4 text-sm">
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wide mb-2">Matières</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($demande->matieres ?? [] as $matiere)
                            <span class="bg-[#55766F] bg-opacity-10 text-[#132D39] text-xs font-semibold px-2.5 py-1 rounded-md">{{ $matiere }}</span>
                        @endforeach
                    </div>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Objectif</p>
                    <p class="text-gray-800">{{ $demande->objectif }}</p>
                </div>
                @if($demande->difficultes)
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Difficultés</p>
                        <p class="text-gray-800">{{ $demande->difficultes }}</p>
                    </div>
                @endif
                @if($demande->preparation_examen)
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Préparation examen</p>
                        <p class="text-gray-800">{{ $demande->preparation_examen }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Organisation --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6 animate-on-scroll delay-200">
            <h3 class="font-bold text-[#132D39] mb-5 flex items-center gap-2">
                <x-icons.clock class="w-5 h-5 text-[#55766F]" />
                Organisation
            </h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Séances / semaine</p>
                    <p class="font-semibold text-[#132D39]">{{ $demande->seances_semaine }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Durée séance</p>
                    <p class="font-semibold text-[#132D39]">{{ $demande->duree_seance }} min</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Jours</p>
                    <p class="font-semibold text-[#132D39]">{{ implode(', ', $demande->jours_disponibles ?? []) }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Horaires</p>
                    <p class="font-semibold text-[#132D39]">{{ $demande->horaires_disponibles }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Date de début</p>
                    <p class="font-semibold text-[#132D39]">{{ $demande->date_debut_souhaitee?->format('d/m/Y') ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Adresse</p>
                    <p class="font-semibold text-[#132D39]">{{ $demande->adresse_cours }}</p>
                </div>
            </div>
        </div>

        @if($demande->informations_complementaires)
            <div class="bg-white rounded-2xl border border-gray-100 p-6 animate-on-scroll delay-300">
                <h3 class="font-bold text-[#132D39] mb-3">Informations complémentaires</h3>
                <p class="text-gray-700 text-sm leading-relaxed">{{ $demande->informations_complementaires }}</p>
            </div>
        @endif

        {{-- Suivi du statut --}}
        <div class="bg-[#E7C273]/20 border border-[#E7C273]/40 rounded-2xl p-6 animate-on-scroll delay-400">
            <h3 class="font-bold text-[#132D39] mb-3">Suivi de votre demande</h3>
            <p class="text-sm text-[#132D39] leading-relaxed">
                @switch($demande->statut)
                    @case('demande_envoyee')
                        Votre demande a bien été reçue. MON MAÎTRE va l'analyser prochainement.
                        @break
                    @case('en_analyse')
                        MON MAÎTRE analyse actuellement votre demande.
                        @break
                    @case('recherche_repetiteur')
                        MON MAÎTRE recherche un répétiteur correspondant à votre besoin.
                        @break
                    @case('attente_confirmation_parent')
                        Un répétiteur vous a été proposé. Consultez vos propositions pour accepter ou refuser.
                        @break
                    @case('placement_confirme')
                        Votre placement est confirmé. MON MAÎTRE va organiser les séances.
                        @break
                    @case('en_cours')
                        Les séances ont commencé. Bon apprentissage !
                        @break
                    @case('termine')
                        La mission est terminée. Merci d'avoir utilisé MON MAÎTRE.
                        @break
                    @case('annule')
                        Cette demande a été annulée.
                        @break
                    @default
                        Votre demande est en cours de traitement.
                @endswitch
            </p>
            <a href="{{ route('parent.propositions.index') }}"
               class="inline-flex items-center gap-2 mt-4 text-sm font-semibold text-[#132D39] hover:text-[#55766F] transition group">
                Voir mes propositions
                <x-icons.arrow-right class="w-4 h-4 transition-transform group-hover:translate-x-1" />
            </a>
        </div>

    </div>

@endsection