@extends('layouts.parent')

@section('title', 'Mon placement ' . $placement->reference . ' — ' . $placement->eleve->prenom)

@section('content')

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm animate-on-scroll">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm animate-on-scroll">
            {{ session('error') }}
        </div>
    @endif

    {{-- Fil d'Ariane --}}
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6 animate-on-scroll">
        <a href="{{ route('parent.placements.index') }}" class="hover:text-mm-navy transition">Mes placements</a>
        <span>/</span>
        <span class="text-mm-navy font-medium font-mono">{{ $placement->reference }}</span>
    </div>

    {{-- En-tête --}}
    <div class="mb-8 flex justify-between items-start flex-wrap gap-4 animate-on-scroll">
        <div>
            <p class="text-sm text-mm-secondary font-semibold uppercase tracking-wider mb-1">Placement</p>
            <h2 class="text-3xl font-bold text-mm-navy font-mono">{{ $placement->reference }}</h2>
        </div>
        <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold {{ $placement->statutCouleur() }}">
            {{ $placement->statutLabel() }}
        </span>
    </div>

    <div class="space-y-6">

        {{-- Répétiteur --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6 animate-on-scroll">
            <h3 class="font-bold text-mm-navy mb-5 flex items-center gap-2">
                <x-icons.academic class="w-5 h-5 text-mm-secondary" />
                Votre répétiteur
            </h3>
            <div class="flex items-center gap-4">
                @if($placement->repetiteur->photo_url)
                    <img
                        src="{{ $placement->repetiteur->photo_url }}"
                        alt="{{ $placement->repetiteur->user->name }}"
                        style="width: 64px; height: 64px; border-radius: 9999px; object-fit: cover; border: 3px solid rgba(20, 59, 143, 0.2); flex-shrink: 0;">
                @else
                    <div class="flex items-center justify-center font-bold flex-shrink-0"
                         style="width: 64px; height: 64px; border-radius: 9999px; background-color: #062B5C; color: #F5A623; font-size: 1.5rem;">
                        {{ $placement->repetiteur->initiale }}
                    </div>
                @endif
                <div>
                    <p class="font-bold text-mm-navy text-lg">{{ $placement->repetiteur->user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $placement->repetiteur->diplome ?? '—' }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $placement->repetiteur->experience_annees }} an(s) d'expérience</p>
                </div>
            </div>
        </div>

        {{-- Détails --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6 animate-on-scroll delay-100">
            <h3 class="font-bold text-mm-navy mb-5 flex items-center gap-2">
                <x-icons.document class="w-5 h-5 text-mm-secondary" />
                Détails du placement
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-sm">
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Élève</p>
                    <p class="font-semibold text-mm-navy">{{ $placement->eleve->prenom }} {{ $placement->eleve->nom }}</p>
                    <p class="text-xs text-gray-500">{{ $placement->eleve->classe }}</p>
                </div>
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
                <div class="md:col-span-2">
                    <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Adresse des cours</p>
                    <p class="font-semibold text-mm-navy">{{ $placement->adresse }}</p>
                </div>
            </div>
        </div>

                {{-- ========== SUIVI DES SÉANCES ========== --}}
        @php
            $seancesEnAttente = $placement->seances()->where('statut', 'en_attente')->orderBy('date')->get();
            $seancesHistorique = $placement->seances()->whereIn('statut', ['effectuee', 'contested', 'absence_repetiteur', 'absence_eleve', 'annulee'])->orderByDesc('date')->limit(10)->get();
        @endphp

        {{-- Stats --}}
        @if ($placement->seances->count() > 0)
            <div class="bg-white rounded-2xl border border-gray-100 p-6 animate-on-scroll delay-150">
                <h3 class="font-bold text-mm-navy mb-4 flex items-center gap-2">
                    <x-icons.clock class="w-5 h-5 text-mm-secondary" />
                    Suivi des séances
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Séances validées</p>
                        <p class="font-bold text-mm-navy text-xl">{{ $placement->nombreSeancesEffectuees() }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Heures totales</p>
                        <p class="font-bold text-mm-navy text-xl">{{ round($placement->totalMinutesEffectuees() / 60, 1) }}h</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Taux de présence</p>
                        <p class="font-bold text-green-600 text-xl">{{ $placement->tauxPresenceRepetiteur() }}%</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- ========== SÉANCES À VALIDER ========== --}}
        @if ($seancesEnAttente->count() > 0)
            <div class="bg-mm-accent/10 border-2 border-mm-accent rounded-2xl p-6 animate-on-scroll delay-200">
                <div class="flex items-start justify-between flex-wrap gap-4 mb-5">
                    <div>
                        <h3 class="font-bold text-mm-navy flex items-center gap-2">
                            <x-icons.bell class="w-5 h-5 text-mm-accent" />
                            Séances à valider ({{ $seancesEnAttente->count() }})
                        </h3>
                        <p class="text-sm text-mm-navy mt-1">
                            Le répétiteur a enregistré ces séances. Confirmez ou contestez-les.
                        </p>
                    </div>
                    @if($seancesEnAttente->count() > 1)
                        <form method="POST" action="{{ route('parent.placements.valider-tout', $placement) }}">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center gap-2 bg-mm-accent text-mm-navy text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-mm-accent-hover transition"
                                onclick="return confirm('Valider toutes les séances en attente ?')">
                                <x-icons.check-badge class="w-4 h-4" />
                                Tout valider
                            </button>
                        </form>
                    @endif
                </div>

                <div class="space-y-3">
                    @foreach ($seancesEnAttente as $seance)
                        <div class="bg-white rounded-xl border border-gray-100 p-4">
                            <div class="flex items-start justify-between gap-4 flex-wrap">
                                <div class="flex items-start gap-4 flex-1 min-w-0">
                                    <div class="flex-shrink-0 w-14 h-14 rounded-xl bg-mm-accent/20 flex flex-col items-center justify-center">
                                        <p class="text-[10px] font-semibold text-mm-navy uppercase">{{ $seance->date->translatedFormat('M') }}</p>
                                        <p class="text-lg font-bold text-mm-navy leading-none">{{ $seance->date->format('d') }}</p>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-mm-navy font-medium">{{ $seance->contenu }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $seance->duree_minutes }} min</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <form method="POST" action="{{ route('parent.seances.valider', $seance) }}">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 bg-green-600 text-white text-xs font-semibold px-4 py-2 rounded-lg hover:bg-green-700 transition"
                                            onclick="return confirm('Valider cette séance ?')">
                                            ✓ Valider
                                        </button>
                                    </form>
                                    <button type="button"
                                        onclick="document.getElementById('contest-{{ $seance->id }}').classList.remove('hidden')"
                                        class="inline-flex items-center gap-1.5 border border-red-200 text-red-600 text-xs font-semibold px-4 py-2 rounded-lg hover:bg-red-50 transition">
                                        ✗ Contester
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Modal de contestation --}}
                        <div id="contest-{{ $seance->id }}" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
                            <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <h3 class="text-lg font-bold text-mm-navy">Contester cette séance</h3>
                                    <button type="button"
                                        onclick="document.getElementById('contest-{{ $seance->id }}').classList.add('hidden')"
                                        class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                                <form method="POST" action="{{ route('parent.seances.contester', $seance) }}">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-mm-navy mb-2">
                                            Raison de la contestation *
                                        </label>
                                        <textarea name="raison_contestation" rows="4" required
                                            placeholder="Expliquez pourquoi cette séance ne devrait pas être validée..."
                                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition"></textarea>
                                    </div>
                                    <div class="flex gap-3">
                                        <button type="button"
                                            onclick="document.getElementById('contest-{{ $seance->id }}').classList.add('hidden')"
                                            class="flex-1 border border-gray-200 text-gray-600 text-sm font-semibold py-2.5 rounded-lg hover:bg-gray-50 transition">
                                            Annuler
                                        </button>
                                        <button type="submit"
                                            class="flex-1 bg-red-600 text-white text-sm font-semibold py-2.5 rounded-lg hover:bg-red-700 transition">
                                            Confirmer la contestation
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ========== HISTORIQUE DES SÉANCES ========== --}}
        @if ($seancesHistorique->count() > 0)
            <div class="bg-white rounded-2xl border border-gray-100 p-6 animate-on-scroll delay-250">
                <h3 class="font-bold text-mm-navy mb-4 flex items-center gap-2">
                    <x-icons.document class="w-5 h-5 text-mm-secondary" />
                    Historique des séances
                </h3>
                <div class="space-y-2">
                    @foreach ($seancesHistorique as $seance)
                        <div class="flex items-center justify-between gap-4 p-3 rounded-lg border border-gray-100">
                            <div class="flex items-center gap-3 flex-1 min-w-0">
                                <span class="text-xs text-gray-500 font-mono w-20">{{ $seance->date->format('d/m/Y') }}</span>
                                <span class="text-sm text-gray-700 truncate flex-1">{{ $seance->contenu }}</span>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-semibold whitespace-nowrap {{ $seance->statutCouleur() }}">
                                {{ $seance->statutLabel() }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ========== ÉVALUATION ========== --}}
        @if ($placement->evaluation)
            {{-- Déjà évalué : affichage --}}
            <div class="bg-white rounded-2xl border border-mm-accent/40 p-6 animate-on-scroll delay-200">
                <h3 class="font-bold text-mm-navy mb-4 flex items-center gap-2">
                    <x-icons.check-badge class="w-5 h-5 text-mm-accent" />
                    Votre évaluation
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
                        <p class="text-xs text-gray-400 mt-2">
                            Évalué le {{ $placement->evaluation->created_at->format('d/m/Y') }}
                        </p>
                    </div>
                </div>
            </div>
        @elseif (in_array($placement->statut, ['termine', 'evaluation']))
            {{-- Formulaire d'évaluation --}}
            <div class="bg-mm-accent/10 border-2 border-mm-accent rounded-2xl p-6 animate-on-scroll delay-200">
                <h3 class="font-bold text-mm-navy mb-2 flex items-center gap-2">
                    <x-icons.check-badge class="w-5 h-5 text-mm-accent" />
                    Évaluez votre répétiteur
                </h3>
                <p class="text-sm text-mm-navy mb-5">
                    Votre avis est précieux pour nous aider à améliorer la qualité de MON MAÎTRE.
                </p>

                <form method="POST" action="{{ route('parent.placements.evaluer', $placement) }}" class="space-y-5">
                    @csrf

                    {{-- Note sur 10 --}}
                    <div>
                        <label class="block text-sm font-medium mb-2">Note globale *</label>
                        <div class="flex flex-wrap gap-2">
                            @for ($i = 1; $i <= 10; $i++)
                                <label class="cursor-pointer">
                                    <input type="radio" name="note" value="{{ $i }}" class="peer sr-only"
                                           {{ old('note') == $i ? 'checked' : '' }} required>
                                    <div class="w-11 h-11 rounded-lg flex items-center justify-center font-bold text-sm transition-all
                                                border-2 border-gray-200 text-gray-500 hover:border-mm-secondary
                                                peer-checked:bg-mm-secondary peer-checked:text-white peer-checked:border-mm-secondary">
                                        {{ $i }}
                                    </div>
                                </label>
                            @endfor
                        </div>
                        <p class="text-xs text-gray-500 mt-2">1 = insuffisant, 10 = excellent</p>
                    </div>

                    {{-- Commentaire --}}
                    <div>
                        <label class="block text-sm font-medium mb-1">Commentaire (optionnel)</label>
                        <textarea name="commentaire" rows="4"
                            placeholder="Ex : Très bon répétiteur, ponctuel et pédagogue. Notre enfant a bien progressé."
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">{{ old('commentaire') }}</textarea>
                    </div>

                    <button type="submit"
                        class="w-full bg-mm-accent text-mm-navy font-semibold py-3 rounded-lg hover:bg-mm-accent-hover hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                        Envoyer mon évaluation
                    </button>
                </form>
            </div>
        @else
            {{-- Placement en cours : info --}}
            <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6 animate-on-scroll delay-200">
                <p class="text-sm text-gray-600">
                    <strong>Évaluation à venir :</strong> vous pourrez évaluer ce répétiteur lorsque le placement sera terminé.
                </p>
            </div>
        @endif

        {{-- Contact MON MAÎTRE --}}
        <div class="bg-mm-accent/20 border border-mm-accent rounded-2xl p-6 animate-on-scroll delay-300">
            <h3 class="font-bold text-mm-navy mb-2">Besoin d'aide ?</h3>
            <p class="text-sm text-mm-navy mb-3">
                Pour toute question ou problème avec ce placement, contactez MON MAÎTRE.
            </p>
            <div class="flex flex-wrap items-center gap-4 text-sm">
                <a href="mailto:contact@monmaitre.ci" class="inline-flex items-center gap-2 font-semibold text-mm-navy hover:text-mm-secondary transition">
                    <x-icons.envelope class="w-4 h-4" />
                    contact@monmaitre.ci
                </a>
                <a href="tel:+2250719167688" class="inline-flex items-center gap-2 font-semibold text-mm-navy hover:text-mm-secondary transition">
                    <x-icons.phone class="w-4 h-4" />
                    07 19 16 76 88
                </a>
            </div>
        </div>

    </div>

@endsection