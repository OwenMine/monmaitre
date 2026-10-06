@extends('layouts.admin')

@section('title', 'Recherche – Demande #MM-' . str_pad($demande->id, 4, '0', STR_PAD_LEFT))

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
        <a href="{{ route('admin.demandes.index') }}" class="hover:text-mm-navy transition">Demandes</a>
        <span>/</span>
        <a href="{{ route('admin.demandes.show', $demande) }}" class="hover:text-mm-navy transition font-mono">#MM-{{ str_pad($demande->id, 4, '0', STR_PAD_LEFT) }}</a>
        <span>/</span>
        <span class="text-mm-navy font-medium">Recherche</span>
    </div>

    {{-- En-tête --}}
    <div class="mb-8 animate-on-scroll">
        <p class="text-sm text-mm-secondary font-semibold uppercase tracking-wider mb-1">Recherche assistée</p>
        <h2 class="text-3xl font-bold text-mm-navy">Répétiteurs pour la demande #MM-{{ str_pad($demande->id, 4, '0', STR_PAD_LEFT) }}</h2>
    </div>

    {{-- Résumé de la demande --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-6 grid grid-cols-2 md:grid-cols-4 gap-5 animate-on-scroll">
        <div>
            <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Élève</p>
            <p class="font-semibold text-mm-navy">{{ $demande->eleve->prenom }} {{ $demande->eleve->nom }}</p>
            <p class="text-xs text-gray-500">{{ $demande->eleve->classe }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Matières</p>
            <p class="font-semibold text-mm-navy text-sm">{{ implode(', ', $demande->matieres ?? []) }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Commune</p>
            <p class="font-semibold text-mm-navy">{{ $demande->parent->commune }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Jours</p>
            <p class="font-semibold text-mm-navy text-sm">{{ implode(', ', $demande->jours_disponibles ?? []) }}</p>
        </div>
    </div>

    {{-- Filtres --}}
    <form method="GET" action="{{ route('admin.matching.resultats', $demande) }}"
          class="bg-white rounded-2xl border border-gray-100 p-4 mb-6 flex flex-wrap gap-3 animate-on-scroll">
        <input type="text" name="commune" value="{{ $filtres['commune'] ?? '' }}"
            placeholder="Filtrer par commune"
            class="border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition flex-1 min-w-[180px]">
        <input type="text" name="matiere" value="{{ $filtres['matiere'] ?? '' }}"
            placeholder="Filtrer par matière"
            class="border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition flex-1 min-w-[180px]">
        <input type="number" name="experience_min" value="{{ $filtres['experience_min'] ?? '' }}"
            placeholder="Expérience min (ans)"
            min="0" max="50"
            class="border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition w-48">
        <button type="submit" class="bg-mm-navy text-white text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-mm-navy-hover hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
            Filtrer
        </button>
        @if(!empty(array_filter($filtres ?? [])))
            <a href="{{ route('admin.matching.rechercher', $demande) }}"
               class="border border-gray-200 text-gray-600 text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-gray-50 transition">
                Réinitialiser
            </a>
        @endif
    </form>

    {{-- Résultats --}}
    <div class="mb-4 flex items-center justify-between animate-on-scroll">
        <h3 class="text-lg font-bold text-mm-navy">
            {{ $suggestions->count() }} profil(s) trouvé(s)
        </h3>
        <p class="text-xs text-gray-500">Trié par pertinence</p>
    </div>

    @if ($suggestions->count() > 0)
        <div class="space-y-4">
            @foreach ($suggestions as $i => $rep)
                @php
                    $propositionExistante = $propositions[$rep->id] ?? null;
                    $dejaPropose = $propositionExistante !== null;
                    $score = $rep->score ?? 0;
                @endphp
                <div class="bg-white rounded-2xl border border-gray-100 p-6 hover:shadow-lg transition-all duration-300 animate-on-scroll delay-{{ min(($i % 4 + 1) * 100, 400) }}">
                    <div class="flex items-start gap-5">

                        {{-- Score --}}
                        <div class="flex-shrink-0 text-center">
                            <div class="w-16 h-16 rounded-full flex items-center justify-center font-bold text-lg
                                @if($score >= 70) bg-green-100 text-green-700
                                @elseif($score >= 40) bg-mm-accent/30 text-mm-navy
                                @else bg-gray-100 text-gray-600
                                @endif">
                                {{ $score }}
                            </div>
                            <p class="text-[10px] text-gray-500 mt-1 uppercase font-semibold">Score</p>
                        </div>

                        {{-- Infos --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-4 mb-4 flex-wrap">
                                <div class="flex items-center gap-3">
                                    @if($rep->photo_url)
                                        <img src="{{ $rep->photo_url }}" alt="{{ $rep->user->name }}"
                                             style="width: 44px; height: 44px; border-radius: 9999px; object-fit: cover; border: 2px solid rgba(20, 59, 143, 0.2); flex-shrink: 0;">
                                    @else
                                        <div class="flex items-center justify-center font-bold flex-shrink-0"
                                             style="width: 44px; height: 44px; border-radius: 9999px; background-color: #062B5C; color: #F5A623; font-size: 0.875rem;">
                                            {{ $rep->initiale }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-bold text-mm-navy">{{ $rep->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $rep->user->commune }}</p>
                                    </div>
                                </div>

                                {{-- Badges de statut de proposition --}}
                                @if($dejaPropose)
                                    @php
                                        $statutClass = match($propositionExistante->statut) {
                                            'proposee' => 'bg-mm-accent/30 text-mm-navy',
                                            'acceptee' => 'bg-green-100 text-green-700',
                                            'refusee'  => 'bg-red-100 text-red-700',
                                            'annulee'  => 'bg-gray-100 text-gray-700',
                                            default    => 'bg-gray-100 text-gray-700',
                                        };
                                    @endphp
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-xs font-semibold bg-gray-100 text-gray-700">
                                            📌 Déjà proposé
                                        </span>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-xs font-semibold {{ $statutClass }}">
                                            {{ $propositionExistante->statutLabel() }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            {{-- Détails --}}
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-xs mb-4">
                                <div>
                                    <p class="text-gray-400 uppercase tracking-wide font-semibold mb-1">Matières</p>
                                    <p class="text-mm-navy font-medium">{{ implode(', ', array_slice($rep->matieres ?? [], 0, 2)) }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-400 uppercase tracking-wide font-semibold mb-1">Expérience</p>
                                    <p class="text-mm-navy font-medium">{{ $rep->experience_annees }} an(s)</p>
                                </div>
                                <div>
                                    <p class="text-gray-400 uppercase tracking-wide font-semibold mb-1">Diplôme</p>
                                    <p class="text-mm-navy font-medium">{{ $rep->diplome ?? '—' }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-400 uppercase tracking-wide font-semibold mb-1">Dispo</p>
                                    <p class="text-mm-navy font-medium">{{ implode(', ', array_slice($rep->jours_disponibles ?? [], 0, 2)) }}</p>
                                </div>
                            </div>

                            {{-- Score détail --}}
                            @if(isset($rep->score_details))
                                <div class="pt-4 border-t border-gray-100 flex flex-wrap gap-4 text-xs">
                                    @php
                                        $labels = ['matieres' => 'Matières', 'zone' => 'Zone', 'niveau' => 'Niveau', 'disponibilite' => 'Disponibilité'];
                                    @endphp
                                    @foreach ($rep->score_details as $key => $points)
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-2 h-2 rounded-full {{ $points > 0 ? 'bg-green-500' : 'bg-gray-300' }}"></span>
                                            <span class="text-gray-600">{{ $labels[$key] ?? $key }}</span>
                                            <span class="font-semibold {{ $points > 0 ? 'text-green-700' : 'text-gray-400' }}">+{{ $points }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- Boutons d'action --}}
                        <div class="flex-shrink-0 flex flex-col gap-2">
                            @if (!$dejaPropose)
                                {{-- Pas encore proposé → bouton "Proposer" --}}
                                <button type="button"
                                    onclick="document.getElementById('modal-proposer-{{ $rep->id }}').classList.remove('hidden')"
                                    class="bg-mm-secondary text-white text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-mm-secondary-hover hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 whitespace-nowrap">
                                    Proposer au parent
                                </button>
                            @elseif (in_array($propositionExistante->statut, ['proposee', 'refusee']))
                                {{-- Proposé mais pas encore accepté/annulé → bouton "Valider au nom du parent" --}}
                                <button type="button"
                                    onclick="document.getElementById('modal-valider-admin-{{ $rep->id }}').classList.remove('hidden')"
                                    class="bg-green-600 text-white text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-green-700 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 whitespace-nowrap">
                                    ✓ Valider au nom du parent
                                </button>
                                <p class="text-[10px] text-gray-400 text-center">
                                    (le parent a confirmé par tel)
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Modale "Proposer" --}}
                @if (!$dejaPropose)
                    <div id="modal-proposer-{{ $rep->id }}" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
                        <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h3 class="text-lg font-bold text-mm-navy">Proposer ce répétiteur</h3>
                                    <p class="text-sm text-gray-500 mt-1">{{ $rep->user->name }} sera proposé au parent.</p>
                                </div>
                                <button type="button"
                                    onclick="document.getElementById('modal-proposer-{{ $rep->id }}').classList.add('hidden')"
                                    class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>

                            <form method="POST" action="{{ route('admin.matching.proposer', [$demande, $rep]) }}">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-mm-navy mb-2">Message au parent (optionnel)</label>
                                    <textarea name="message" rows="4"
                                        placeholder="Ex : Voici un répétiteur expérimenté en mathématiques..."
                                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition"></textarea>
                                </div>
                                <div class="flex gap-3">
                                    <button type="button"
                                        onclick="document.getElementById('modal-proposer-{{ $rep->id }}').classList.add('hidden')"
                                        class="flex-1 border border-gray-200 text-gray-600 text-sm font-semibold py-2.5 rounded-lg hover:bg-gray-50 transition">
                                        Annuler
                                    </button>
                                    <button type="submit"
                                        class="flex-1 bg-mm-secondary text-white text-sm font-semibold py-2.5 rounded-lg hover:bg-mm-secondary-hover transition">
                                        Envoyer la proposition
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

                {{-- Modale "Valider au nom du parent" --}}
                @if ($dejaPropose && in_array($propositionExistante->statut, ['proposee', 'refusee']))
                    <div id="modal-valider-admin-{{ $rep->id }}" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
                        <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-mm-navy">Valider au nom du parent</h3>
                                        <p class="text-sm text-gray-500 mt-1">
                                            Cette action va créer un placement définitif avec <strong>{{ $rep->user->name }}</strong>.
                                        </p>
                                    </div>
                                </div>
                                <button type="button"
                                    onclick="document.getElementById('modal-valider-admin-{{ $rep->id }}').classList.add('hidden')"
                                    class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>

                            <div class="bg-mm-accent/10 border border-mm-accent/30 rounded-lg p-3 mb-4 text-xs text-mm-navy">
                                <strong>ℹ️ À utiliser uniquement si le parent a confirmé par téléphone.</strong><br>
                                Le parent et le répétiteur recevront une notification.
                            </div>

                            <form method="POST" action="{{ route('admin.matching.valider-admin', $propositionExistante) }}">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-mm-navy mb-2">Message (optionnel)</label>
                                    <textarea name="message_admin" rows="3"
                                        placeholder="Ex : Confirmé par téléphone le 05/10/2026. Le parent souhaite démarrer la semaine prochaine."
                                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition"></textarea>
                                </div>
                                <div class="flex gap-3">
                                    <button type="button"
                                        onclick="document.getElementById('modal-valider-admin-{{ $rep->id }}').classList.add('hidden')"
                                        class="flex-1 border border-gray-200 text-gray-600 text-sm font-semibold py-2.5 rounded-lg hover:bg-gray-50 transition">
                                        Annuler
                                    </button>
                                    <button type="submit"
                                        class="flex-1 bg-green-600 text-white text-sm font-semibold py-2.5 rounded-lg hover:bg-green-700 transition">
                                        ✓ Confirmer la validation
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-2xl border border-gray-100 py-16 text-center animate-on-scroll">
            <x-icons.search class="w-12 h-12 text-gray-300 mx-auto mb-3" />
            <p class="text-gray-500 font-medium">Aucun répétiteur ne correspond à cette demande.</p>
            <p class="text-sm text-gray-400 mt-1">Essayez d'élargir les critères ou validez plus de profils.</p>
        </div>
    @endif

@endsection