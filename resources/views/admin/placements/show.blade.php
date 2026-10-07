@extends('layouts.admin')

@section('title', 'Placement ' . $placement->reference . ' — ' . $placement->eleve->prenom . ' ' . $placement->eleve->nom)

@section('content')

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm animate-on-scroll">
            {{ session('success') }}
        </div>
    @endif

    {{-- Fil d'Ariane --}}
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6 animate-on-scroll">
        <a href="{{ route('admin.placements.index') }}" class="hover:text-mm-navy transition">Placements</a>
        <span>/</span>
        <span class="text-mm-navy font-medium font-mono">{{ $placement->reference }}</span>
    </div>

    {{-- En-tête --}}
    <div class="mb-8 flex justify-between items-start flex-wrap gap-4 animate-on-scroll">
        <div>
            <p class="text-sm text-mm-secondary font-semibold uppercase tracking-wider mb-1">Placement</p>
            <h2 class="text-3xl font-bold text-mm-navy font-mono">{{ $placement->reference }}</h2>
            <p class="text-gray-500 mt-1">Créé le {{ $placement->created_at->format('d/m/Y à H:i') }}</p>
        </div>
        <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold {{ $placement->statutCouleur() }}">
            {{ $placement->statutLabel() }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Colonne principale --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Parties concernées --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 animate-on-scroll">
                <h3 class="font-bold text-mm-navy mb-5">Parties concernées</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Parent --}}
                    <div class="p-4 rounded-xl bg-gray-50">
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">Parent</p>
                        <p class="font-semibold text-mm-navy">{{ $placement->parent->name }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $placement->parent->phone }}</p>
                        @if($placement->parent->email)
                            <p class="text-xs text-gray-500">{{ $placement->parent->email }}</p>
                        @endif
                    </div>

                    {{-- Élève --}}
                    <div class="p-4 rounded-xl bg-gray-50">
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">Élève</p>
                        <p class="font-semibold text-mm-navy">{{ $placement->eleve->prenom }} {{ $placement->eleve->nom }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $placement->eleve->classe }}</p>
                    </div>

                    {{-- Répétiteur --}}
                    <div class="md:col-span-2 p-4 rounded-xl bg-mm-secondary/5 border border-mm-secondary/20">
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-3">Répétiteur</p>
                        <div class="flex items-center gap-3">

                            {{-- PHOTO RÉPÉTITEUR --}}
                            @if($placement->repetiteur->photo_url)
                                <img
                                    src="{{ $placement->repetiteur->photo_url }}"
                                    alt="{{ $placement->repetiteur->user->name }}"
                                    style="width: 44px; height: 44px; border-radius: 9999px; object-fit: cover; border: 2px solid rgba(20, 59, 143, 0.2); flex-shrink: 0;">
                            @else
                                <div
                                    class="flex items-center justify-center font-bold flex-shrink-0"
                                    style="width: 44px; height: 44px; border-radius: 9999px; background-color: #062B5C; color: #F5A623; font-size: 0.875rem;">
                                    {{ $placement->repetiteur->initiale }}
                                </div>
                            @endif

                            <div>
                                <p class="font-semibold text-mm-navy">{{ $placement->repetiteur->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $placement->repetiteur->user->phone }} • {{ $placement->repetiteur->user->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Détails du placement --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 animate-on-scroll delay-100">
                <h3 class="font-bold text-mm-navy mb-5">Détails du placement</h3>
                <div class="grid grid-cols-2 gap-5 text-sm">
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Matière</p>
                        <p class="font-semibold text-mm-navy">{{ $placement->matiere }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Date de début</p>
                        <p class="font-semibold text-mm-navy">{{ $placement->date_debut?->format('d/m/Y') ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Fréquence</p>
                        <p class="font-semibold text-mm-navy">{{ $placement->frequence ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Horaires</p>
                        <p class="font-semibold text-mm-navy">{{ $placement->horaires ?? '—' }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Adresse des cours</p>
                        <p class="font-semibold text-mm-navy">{{ $placement->adresse }}</p>
                    </div>
                    @if($placement->tarif_convenu)
                        <div class="col-span-2">
                            <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Tarif convenu</p>
                            <p class="font-semibold text-mm-navy text-lg">{{ number_format($placement->tarif_convenu, 0, ',', ' ') }} FCFA</p>
                        </div>
                    @endif
                </div>
            </div>

                        {{-- ============ SÉANCES ============ --}}
            @php
                $seances = $placement->seances()->orderByDesc('date')->get();
                $seancesEnAttente = $seances->where('statut', 'en_attente');
            @endphp

            @if ($seances->count() > 0)
                <div class="bg-white rounded-2xl border border-gray-100 p-6 animate-on-scroll delay-150">

                    {{-- En-tête --}}
                    <div class="flex items-center justify-between mb-5 flex-wrap gap-3">
                        <div>
                            <h3 class="font-bold text-mm-navy flex items-center gap-2">
                                <x-icons.clock class="w-5 h-5 text-mm-secondary" />
                                Séances ({{ $seances->count() }})
                            </h3>
                            <p class="text-xs text-gray-500 mt-1">
                                Suivi des séances déclarées par le répétiteur.
                            </p>
                        </div>

                        {{-- Stats --}}
                        <div class="flex items-center gap-4 text-xs">
                            <div class="text-center">
                                <p class="text-gray-500 uppercase tracking-wide font-semibold mb-1">Effectuées</p>
                                <p class="font-bold text-mm-navy text-base">{{ $placement->nombreSeancesEffectuees() }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-gray-500 uppercase tracking-wide font-semibold mb-1">En attente</p>
                                <p class="font-bold text-orange-500 text-base">{{ $seancesEnAttente->count() }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-gray-500 uppercase tracking-wide font-semibold mb-1">Taux présence</p>
                                <p class="font-bold text-green-600 text-base">{{ $placement->tauxPresenceRepetiteur() }}%</p>
                            </div>
                        </div>
                    </div>

                    {{-- Alerte si séances en attente --}}
                    @if ($seancesEnAttente->count() > 0)
                        <div class="bg-orange-50 border border-orange-200 text-orange-800 rounded-lg p-3 mb-4 text-sm">
                            <strong>⚠️ {{ $seancesEnAttente->count() }} séance(s) en attente de validation.</strong>
                            <p class="text-xs mt-1">
                                Validez-les au nom du parent s'il a confirmé la bonne réalisation des cours.
                            </p>
                        </div>
                    @endif

                    {{-- Liste des séances --}}
                    <div class="space-y-3">
                        @foreach ($seances as $seance)
                            <div class="flex items-center justify-between gap-4 p-4 rounded-xl border border-gray-100 {{ $seance->statut === 'en_attente' ? 'bg-orange-50/50' : 'bg-white' }}">

                                {{-- Date + Infos --}}
                                <div class="flex items-center gap-3 flex-1 min-w-0">
                                    <div class="flex-shrink-0 w-12 h-12 rounded-lg {{ $seance->statut === 'en_attente' ? 'bg-orange-100' : 'bg-mm-secondary/10' }} flex flex-col items-center justify-center">
                                        <p class="text-[10px] font-semibold {{ $seance->statut === 'en_attente' ? 'text-orange-700' : 'text-mm-secondary' }} uppercase">
                                            {{ $seance->date->translatedFormat('M') }}
                                        </p>
                                        <p class="text-sm font-bold {{ $seance->statut === 'en_attente' ? 'text-orange-700' : 'text-mm-secondary' }} leading-none">
                                            {{ $seance->date->format('d') }}
                                        </p>
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold {{ $seance->statutCouleur() }}">
                                                {{ $seance->statutLabel() }}
                                            </span>
                                            <span class="text-xs text-gray-500">{{ $seance->duree_minutes }} min</span>
                                        </div>
                                        <p class="text-sm font-medium text-mm-navy truncate">{{ $seance->contenu }}</p>
                                        @if ($seance->commentaire)
                                            <p class="text-xs text-gray-500 italic truncate">{{ $seance->commentaire }}</p>
                                        @endif
                                    </div>
                                </div>

                                {{-- Bouton de validation --}}
                                @if ($seance->statut === 'en_attente')
                                    <form method="POST"
                                          action="{{ route('admin.seances.valider-admin', $seance) }}"
                                          onsubmit="return confirm('Valider cette séance au nom du parent ?')"
                                          class="flex-shrink-0">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 bg-green-600 text-white text-xs font-semibold px-4 py-2 rounded-lg hover:bg-green-700 hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/>
                                            </svg>
                                            Valider
                                        </button>
                                    </form>
                                @else
                                    <span class="flex-shrink-0 text-xs text-gray-400">
                                        @if ($seance->valideePar)
                                            Validée par {{ $seance->valideePar->name }}
                                        @endif
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>

                </div>
            @endif

            @if ($placement->commentaires)
                <div class="bg-white rounded-2xl border border-gray-100 p-6 animate-on-scroll delay-200">
                    <h3 class="font-bold text-mm-navy mb-3">Commentaires</h3>
                    <p class="text-gray-700 text-sm leading-relaxed">{{ $placement->commentaires }}</p>
                </div>
            @endif

        </div>

        {{-- Colonne latérale --}}
        <div class="space-y-6">

            {{-- Mise à jour --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 animate-on-scroll">
                <h3 class="font-bold text-mm-navy mb-4">Mettre à jour</h3>
                <form method="POST" action="{{ route('admin.placements.update', $placement) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Statut</label>
                        <select name="statut" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">
                            @foreach([
                                'en_attente' => 'En attente',
                                'confirme' => 'Confirmé',
                                'en_cours' => 'En cours',
                                'evaluation' => 'En évaluation',
                                'termine' => 'Terminé',
                                'remplacement' => 'Remplacement demandé',
                                'annule' => 'Annulé',
                            ] as $key => $label)
                                <option value="{{ $key }}" {{ $placement->statut === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Tarif convenu (FCFA)</label>
                        <input type="number" name="tarif_convenu" value="{{ $placement->tarif_convenu }}" min="0" step="500"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition"
                            placeholder="Ex : 50000">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Commentaires</label>
                        <textarea name="commentaires" rows="3"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition"
                            placeholder="Notes internes...">{{ $placement->commentaires }}</textarea>
                    </div>

                    <button type="submit" class="w-full bg-mm-navy text-white text-sm font-semibold py-2.5 rounded-lg hover:bg-mm-navy-hover hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                        Enregistrer
                    </button>
                </form>
            </div>

            {{-- Liens --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 animate-on-scroll delay-100">
                <h3 class="font-bold text-mm-navy mb-4">Liens</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.demandes.show', $placement->demande) }}"
                       class="flex items-center gap-2 p-3 rounded-lg border border-gray-100 hover:border-mm-accent hover:bg-mm-accent/5 hover:-translate-y-0.5 transition-all duration-300 text-sm text-mm-secondary hover:text-mm-navy font-medium">
                        <x-icons.document class="w-4 h-4" />
                        Voir la demande #MM-{{ str_pad($placement->demande->id, 4, '0', STR_PAD_LEFT) }}
                    </a>
                    <a href="{{ route('admin.repetiteurs.show', $placement->repetiteur) }}"
                       class="flex items-center gap-2 p-3 rounded-lg border border-gray-100 hover:border-mm-secondary hover:bg-mm-secondary/5 hover:-translate-y-0.5 transition-all duration-300 text-sm text-mm-secondary hover:text-mm-navy font-medium">
                        <x-icons.academic class="w-4 h-4" />
                        Voir le profil du répétiteur
                    </a>
                </div>
            </div>

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