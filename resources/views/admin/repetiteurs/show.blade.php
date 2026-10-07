@extends('layouts.admin')

@section('title', 'Profil répétiteur — ' . $repetiteur->user->name)

@section('content')

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm animate-on-scroll">
            {{ session('success') }}
        </div>
    @endif

    {{-- Fil d'Ariane --}}
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6 animate-on-scroll">
        <a href="{{ route('admin.repetiteurs.index') }}" class="hover:text-mm-navy transition">Répétiteurs</a>
        <span>/</span>
        <span class="text-mm-navy font-medium">{{ $repetiteur->user->name }}</span>
    </div>

    {{-- En-tête profil --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-6 flex items-start gap-5 animate-on-scroll">

        {{-- PHOTO RÉPÉTITEUR --}}
        @if($repetiteur->photo_url)
            <img
                src="{{ $repetiteur->photo_url }}"
                alt="{{ $repetiteur->user->name }}"
                style="width: 80px; height: 80px; border-radius: 9999px; object-fit: cover; border: 3px solid rgba(20, 59, 143, 0.2); flex-shrink: 0;">
        @else
            <div
                class="flex items-center justify-center font-bold flex-shrink-0"
                style="width: 80px; height: 80px; border-radius: 9999px; background-color: #062B5C; color: #F5A623; font-size: 2rem;">
                {{ $repetiteur->initiale }}
            </div>
        @endif

        <div class="flex-1">
            <h2 class="text-2xl font-bold text-mm-navy">{{ $repetiteur->user->name }}</h2>
            <p class="text-gray-500 text-sm mt-1">
                {{ $repetiteur->user->email ?? '—' }} • {{ $repetiteur->user->phone }}
            </p>
            <div class="flex items-center gap-3 mt-3 flex-wrap">
                <span class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-semibold
                    @if($repetiteur->statut === 'profil_valide') bg-green-100 text-green-700
                    @elseif(in_array($repetiteur->statut, ['profil_refuse', 'profil_suspendu'])) bg-red-100 text-red-700
                    @elseif(in_array($repetiteur->statut, ['candidature_envoyee', 'a_examiner'])) bg-mm-accent/30 text-mm-navy
                    @else bg-gray-100 text-gray-700
                    @endif">
                    {{ $repetiteur->statutLabel() }}
                </span>
                @if($repetiteur->user->commune)
                    <span class="text-sm text-gray-500">{{ $repetiteur->user->commune }}</span>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Colonne principale --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Profil académique --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 animate-on-scroll">
                <h3 class="font-bold text-mm-navy mb-4 flex items-center gap-2">
                    <x-icons.academic class="w-5 h-5 text-mm-secondary" />
                    Profil académique
                </h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Niveau d'études</p>
                        <p class="font-semibold text-mm-navy">{{ $repetiteur->niveau_etudes ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Diplôme</p>
                        <p class="font-semibold text-mm-navy">{{ $repetiteur->diplome ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Établissement</p>
                        <p class="font-semibold text-mm-navy">{{ $repetiteur->etablissement ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Année d'obtention</p>
                        <p class="font-semibold text-mm-navy">{{ $repetiteur->annee_obtention ?? '—' }}</p>
                    </div>
                </div>
            </div>

            {{-- Compétences --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 animate-on-scroll delay-100">
                <h3 class="font-bold text-mm-navy mb-4 flex items-center gap-2">
                    <x-icons.check-badge class="w-5 h-5 text-mm-secondary" />
                    Compétences
                </h3>
                <div class="space-y-4 text-sm">
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide mb-2">Matières enseignées</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($repetiteur->matieres ?? [] as $matiere)
                                <span class="bg-mm-secondary/10 text-mm-navy text-xs font-semibold px-2.5 py-1 rounded-md">{{ $matiere }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide mb-2">Classes</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($repetiteur->classes ?? [] as $classe)
                                <span class="bg-gray-100 text-gray-700 text-xs font-semibold px-2.5 py-1 rounded-md">{{ $classe }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Niveau minimum</p>
                            <p class="font-semibold text-mm-navy">{{ $repetiteur->niveau_min ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Niveau maximum</p>
                            <p class="font-semibold text-mm-navy">{{ $repetiteur->niveau_max ?? '—' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Expérience --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 animate-on-scroll delay-200">
                <h3 class="font-bold text-mm-navy mb-4 flex items-center gap-2">
                    <x-icons.user-circle class="w-5 h-5 text-mm-secondary" />
                    Expérience
                </h3>
                <div class="space-y-4 text-sm">
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Années d'expérience</p>
                        <p class="font-semibold text-mm-navy">{{ $repetiteur->experience_annees }} an(s)</p>
                    </div>
                    @if($repetiteur->experience_repetition)
                        <div>
                            <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Expérience en répétition</p>
                            <p class="text-gray-800">{{ $repetiteur->experience_repetition }}</p>
                        </div>
                    @endif
                    @if($repetiteur->experiences_particulieres)
                        <div>
                            <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Expériences particulières</p>
                            <p class="text-gray-800">{{ $repetiteur->experiences_particulieres }}</p>
                        </div>
                    @endif
                    @if($repetiteur->references)
                        <div>
                            <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Références</p>
                            <p class="text-gray-800">{{ $repetiteur->references }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Disponibilités et zone --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 animate-on-scroll delay-300">
                <h3 class="font-bold text-mm-navy mb-4 flex items-center gap-2">
                    <x-icons.clock class="w-5 h-5 text-mm-secondary" />
                    Disponibilités & Zone
                </h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="col-span-2">
                        <p class="text-gray-500 text-xs uppercase tracking-wide mb-2">Jours disponibles</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($repetiteur->jours_disponibles ?? [] as $jour)
                                <span class="bg-mm-accent/30 text-mm-navy text-xs font-semibold px-2.5 py-1 rounded-md">{{ $jour }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Horaires</p>
                        <p class="font-semibold text-mm-navy">{{ $repetiteur->horaires_disponibles ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Max élèves</p>
                        <p class="font-semibold text-mm-navy">{{ $repetiteur->max_eleves ?? '—' }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-gray-500 text-xs uppercase tracking-wide mb-2">Communes</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($repetiteur->communes ?? [] as $commune)
                                <span class="bg-mm-secondary/10 text-mm-navy text-xs font-semibold px-2.5 py-1 rounded-md">{{ $commune }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Rayon de déplacement</p>
                        <p class="font-semibold text-mm-navy">{{ $repetiteur->rayon_deplacement ?? '—' }} km</p>
                    </div>
                </div>
            </div>

        </div>

        {{-- Colonne latérale --}}
        <div class="space-y-6">

            {{-- Actions --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 animate-on-scroll">
                <h3 class="font-bold text-mm-navy mb-4">Actions</h3>
                <div class="space-y-2">
                    @if($repetiteur->statut !== 'profil_valide')
                        <form method="POST" action="{{ route('admin.repetiteurs.valider', $repetiteur) }}">
                            @csrf
                            <button type="submit" class="w-full bg-green-600 text-white text-sm font-semibold py-2.5 rounded-lg hover:bg-green-700 hover:-translate-y-0.5 transition-all duration-300">
                                ✓ Valider le profil
                            </button>
                        </form>
                    @endif
                    @if($repetiteur->statut !== 'profil_refuse')
                        <form method="POST" action="{{ route('admin.repetiteurs.refuser', $repetiteur) }}">
                            @csrf
                            <button type="submit" class="w-full bg-red-600 text-white text-sm font-semibold py-2.5 rounded-lg hover:bg-red-700 hover:-translate-y-0.5 transition-all duration-300">
                                ✗ Refuser le profil
                            </button>
                        </form>
                    @endif
                    @if($repetiteur->statut !== 'profil_suspendu')
                        <form method="POST" action="{{ route('admin.repetiteurs.suspendre', $repetiteur) }}">
                            @csrf
                            <button type="submit" class="w-full bg-gray-600 text-white text-sm font-semibold py-2.5 rounded-lg hover:bg-gray-700 hover:-translate-y-0.5 transition-all duration-300">
                                ⏸ Suspendre
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Changer statut --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 animate-on-scroll delay-100">
                <h3 class="font-bold text-mm-navy mb-4">Changer le statut</h3>
                <form method="POST" action="{{ route('admin.repetiteurs.statut', $repetiteur) }}">
                    @csrf
                    <select name="statut" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition mb-3">
                        @php
                            $statuts = [
                                'pre_inscrit' => 'Pré-inscrit',
                                'dossier_incomplet' => 'Dossier incomplet',
                                'candidature_envoyee' => 'Candidature envoyée',
                                'a_examiner' => 'À examiner',
                                'entretien_a_effectuer' => 'Entretien à effectuer',
                                'documents_a_verifier' => 'Documents à vérifier',
                                'profil_valide' => 'Profil validé',
                                'profil_en_attente' => 'Profil en attente',
                                'profil_refuse' => 'Profil refusé',
                                'profil_suspendu' => 'Profil suspendu',
                            ];
                        @endphp
                        @foreach($statuts as $key => $label)
                            <option value="{{ $key }}" {{ $repetiteur->statut === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="w-full bg-mm-navy text-white text-sm font-semibold py-2.5 rounded-lg hover:bg-mm-navy-hover hover:-translate-y-0.5 transition-all duration-300">
                        Mettre à jour
                    </button>
                </form>
            </div>

            {{-- Documents (avec aperçu iframe) --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 animate-on-scroll delay-200">
                <h3 class="font-bold text-mm-navy mb-4 flex items-center gap-2">
                    <x-icons.document class="w-5 h-5 text-mm-secondary" />
                    Documents ({{ $repetiteur->documents->count() }})
                </h3>

                @if($repetiteur->documents->count() > 0)
                    <div class="space-y-5">
                        @foreach($repetiteur->documents as $doc)
                            <div class="rounded-xl border border-gray-100 overflow-hidden hover:border-mm-accent hover:shadow-lg transition-all duration-300 bg-white">

                                {{-- Aperçu --}}
                                <div class="relative bg-gray-50 border-b border-gray-100" style="height: 320px;">
                                    @if(in_array(strtolower(pathinfo($doc->nom_fichier, PATHINFO_EXTENSION)), ['pdf']))
                                        <iframe
                                            src="{{ route('admin.documents.voir', $doc) }}#toolbar=0&navpanes=0&scrollbar=0&view=FitH"
                                            class="w-full h-full"
                                            style="border: none;"
                                            title="Aperçu de {{ $doc->nom_fichier }}">
                                        </iframe>
                                    @elseif(in_array(strtolower(pathinfo($doc->nom_fichier, PATHINFO_EXTENSION)), ['jpg','jpeg','png']))
                                        <img
                                            src="{{ route('admin.documents.voir', $doc) }}"
                                            alt="{{ $doc->nom_fichier }}"
                                            class="w-full h-full object-contain p-2">
                                    @else
                                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                                            <x-icons.document class="w-16 h-16 mb-2" />
                                            <p class="text-xs font-semibold uppercase tracking-wide">Aperçu non disponible</p>
                                        </div>
                                    @endif

                                    <span class="absolute top-2 left-2 inline-flex items-center px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wide bg-mm-navy text-white shadow-md">
                                        {{ $doc->typeLabel() }}
                                    </span>
                                </div>

                                {{-- Infos + actions --}}
                                <div class="p-4">
                                    <div class="flex items-start justify-between gap-3 mb-3">
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-bold text-mm-navy truncate" title="{{ $doc->nom_fichier }}">
                                                {{ $doc->nom_fichier }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-0.5 flex items-center gap-1">
                                                <x-icons.clock class="w-3 h-3" />
                                                Importé le {{ $doc->created_at->format('d/m/Y à H:i') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.documents.voir', $doc) }}"
                                           target="_blank"
                                           class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg text-xs font-semibold bg-mm-secondary text-white hover:bg-mm-secondary-hover hover:shadow-md transition-all duration-300">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Voir
                                        </a>
                                        <a href="{{ route('admin.documents.telecharger', $doc) }}"
                                           class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg text-xs font-semibold bg-mm-accent text-mm-navy hover:bg-mm-accent-hover hover:shadow-md transition-all duration-300">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                            Télécharger
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-400 text-center py-8">
                        Aucun document uploadé par ce répétiteur.
                    </p>
                @endif
            </div>

        </div>

    </div>

@endsection