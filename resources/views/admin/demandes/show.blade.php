@extends('layouts.admin')

@section('title', 'Demande #MM-' . str_pad($demande->id, 4, '0', STR_PAD_LEFT) . ' — ' . $demande->eleve->prenom . ' ' . $demande->eleve->nom)

@section('content')

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm animate-on-scroll">
            {{ session('success') }}
        </div>
    @endif

    {{-- Fil d'Ariane --}}
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6 animate-on-scroll">
        <a href="{{ route('admin.demandes.index') }}" class="hover:text-[#132D39] transition">Demandes</a>
        <span>/</span>
        <span class="text-[#132D39] font-medium">#MM-{{ str_pad($demande->id, 4, '0', STR_PAD_LEFT) }}</span>
    </div>

    {{-- En-tête --}}
    <div class="mb-8 flex justify-between items-start flex-wrap gap-4 animate-on-scroll">
        <div>
            <h2 class="text-3xl font-bold text-[#132D39]">
                Demande #MM-{{ str_pad($demande->id, 4, '0', STR_PAD_LEFT) }}
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Colonne principale --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Infos parent --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 animate-on-scroll">
                <h3 class="font-bold text-[#132D39] mb-4 flex items-center gap-2">
                    <x-icons.users class="w-5 h-5 text-[#55766F]" />
                    Parent
                </h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Nom</p>
                        <p class="font-semibold text-[#132D39]">{{ $demande->parent->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Téléphone</p>
                        <p class="font-semibold text-[#132D39]">{{ $demande->parent->phone }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Email</p>
                        <p class="font-semibold text-[#132D39]">{{ $demande->parent->email }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Commune</p>
                        <p class="font-semibold text-[#132D39]">{{ $demande->parent->commune }}</p>
                    </div>
                </div>
            </div>

            {{-- Infos élève --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 animate-on-scroll delay-100">
                <h3 class="font-bold text-[#132D39] mb-4 flex items-center gap-2">
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
            <div class="bg-white rounded-2xl border border-gray-100 p-6 animate-on-scroll delay-200">
                <h3 class="font-bold text-[#132D39] mb-4 flex items-center gap-2">
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
            <div class="bg-white rounded-2xl border border-gray-100 p-6 animate-on-scroll delay-300">
                <h3 class="font-bold text-[#132D39] mb-4 flex items-center gap-2">
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
                <div class="bg-white rounded-2xl border border-gray-100 p-6 animate-on-scroll delay-400">
                    <h3 class="font-bold text-[#132D39] mb-3">Informations complémentaires</h3>
                    <p class="text-gray-700 text-sm leading-relaxed">{{ $demande->informations_complementaires }}</p>
                </div>
            @endif

        </div>

        {{-- Colonne latérale --}}
        <div class="space-y-6">

            {{-- Changement de statut --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 animate-on-scroll">
                <h3 class="font-bold text-[#132D39] mb-4">Changer le statut</h3>
                <form method="POST" action="{{ route('admin.demandes.statut', $demande) }}">
                    @csrf
                    <select name="statut" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#55766F] focus:ring-2 focus:ring-[#55766F]/20 transition mb-3">
                        @php
                            $statuts = [
                                'dossier_incomplet' => 'Dossier incomplet',
                                'dossier_complet' => 'Dossier complet',
                                'demande_envoyee' => 'Demande envoyée',
                                'en_analyse' => 'En analyse',
                                'recherche_repetiteur' => 'Recherche de répétiteur',
                                'repetiteur_identifie' => 'Répétiteur identifié',
                                'repetiteur_contacte' => 'Répétiteur contacté',
                                'attente_confirmation_parent' => 'Attente de confirmation',
                                'placement_confirme' => 'Placement confirmé',
                                'en_cours' => 'En cours',
                                'termine' => 'Terminé',
                                'annule' => 'Annulé',
                                'remplacement_demande' => 'Remplacement demandé',
                            ];
                        @endphp
                        @foreach($statuts as $key => $label)
                            <option value="{{ $key }}" {{ $demande->statut === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="w-full bg-[#132D39] text-white text-sm font-semibold py-2.5 rounded-lg hover:bg-[#0d1f27] hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                        Mettre à jour
                    </button>
                </form>
            </div>

            {{-- Actions rapides --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 animate-on-scroll delay-100">
                <h3 class="font-bold text-[#132D39] mb-4">Actions rapides</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.matching.rechercher', $demande) }}"
                       class="flex items-center gap-3 p-3 rounded-lg border border-gray-100 hover:border-[#E7C273] hover:bg-[#E7C273]/5 hover:-translate-y-0.5 transition-all duration-300 text-sm font-medium text-[#132D39]">
                        <div class="w-9 h-9 rounded-lg bg-[#E7C273]/20 flex items-center justify-center">
                            <x-icons.search class="w-4 h-4 text-[#132D39]" />
                        </div>
                        Rechercher un répétiteur
                    </a>
                    <a href="#" class="flex items-center gap-3 p-3 rounded-lg border border-gray-100 hover:border-[#55766F] hover:bg-[#55766F]/5 hover:-translate-y-0.5 transition-all duration-300 text-sm font-medium text-[#132D39]">
                        <div class="w-9 h-9 rounded-lg bg-[#55766F]/10 flex items-center justify-center">
                            <x-icons.bell class="w-4 h-4 text-[#55766F]" />
                        </div>
                        Contacter le parent
                    </a>
                </div>
            </div>

            {{-- Info --}}
            <div class="bg-[#E7C273]/20 border border-[#E7C273]/40 rounded-2xl p-5 animate-on-scroll delay-200">
                <p class="text-xs font-bold text-[#132D39] uppercase tracking-wide mb-1">Prochaine étape</p>
                <p class="text-sm text-[#132D39]">
                    Cette demande peut être connectée au module de recherche assistée pour proposer un répétiteur.
                </p>
            </div>

        </div>

    </div>

@endsection