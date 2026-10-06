@extends('layouts.parent')

@section('title', 'Mes propositions')

@section('content')

    {{-- En-tête --}}
    <div class="mb-8 animate-on-scroll">
        <h2 class="text-3xl font-bold text-mm-navy tracking-tight">Mes propositions</h2>
        <p class="text-gray-500 mt-1">Les répétiteurs proposés par MON MAÎTRE pour vos demandes.</p>
    </div>

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

    @if ($propositions->count() > 0)
        <div class="space-y-6">
            @foreach ($propositions as $i => $proposition)
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden animate-on-scroll delay-{{ min(($i % 4 + 1) * 100, 400) }}">

                    {{-- En-tête proposition --}}
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between flex-wrap gap-3">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Demande</p>
                            <p class="font-semibold text-mm-navy">
                                #MM-{{ str_pad($proposition->demande->id, 4, '0', STR_PAD_LEFT) }}
                                — {{ $proposition->demande->eleve->prenom }} {{ $proposition->demande->eleve->nom }}
                            </p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-semibold {{ $proposition->statutCouleur() }}">
                            {{ $proposition->statutLabel() }}
                        </span>
                    </div>

                    {{-- Corps --}}
                    <div class="p-6">

                        {{-- Profil proposé --}}
                        <div class="flex items-start gap-5 mb-6">
                            {{-- PHOTO RÉPÉTITEUR --}}
                            <div class="flex-shrink-0">
                                @if ($proposition->repetiteur->photo_url)
                                    <img
                                        src="{{ $proposition->repetiteur->photo_url }}"
                                        alt="{{ $proposition->repetiteur->user->name }}"
                                        style="width: 64px; height: 64px; border-radius: 9999px; object-fit: cover; border: 3px solid rgba(20, 59, 143, 0.2);">
                                @else
                                    <div
                                        class="flex items-center justify-center font-bold"
                                        style="width: 64px; height: 64px; border-radius: 9999px; background-color: #062B5C; color: #F5A623; font-size: 1.5rem;">
                                        {{ $proposition->repetiteur->initiale }}
                                    </div>
                                @endif
                            </div>

                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-mm-navy">{{ $proposition->repetiteur->user->name }}</h3>
                                <p class="text-sm text-gray-500 mt-1">{{ $proposition->repetiteur->diplome ?? '—' }}</p>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4 text-xs">
                                    <div>
                                        <p class="text-gray-400 uppercase tracking-wide font-semibold mb-1">Matières</p>
                                        <p class="text-mm-navy font-medium">
                                            {{ implode(', ', array_slice($proposition->repetiteur->matieres ?? [], 0, 2)) }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-gray-400 uppercase tracking-wide font-semibold mb-1">Expérience</p>
                                        <p class="text-mm-navy font-medium">
                                            {{ $proposition->repetiteur->experience_annees }} an(s)
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-gray-400 uppercase tracking-wide font-semibold mb-1">Zone</p>
                                        <p class="text-mm-navy font-medium">
                                            {{ implode(', ', array_slice($proposition->repetiteur->communes ?? [], 0, 2)) }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-gray-400 uppercase tracking-wide font-semibold mb-1">Disponibilité</p>
                                        <p class="text-mm-navy font-medium">
                                            {{ implode(', ', array_slice($proposition->repetiteur->jours_disponibles ?? [], 0, 2)) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Message de MON MAÎTRE --}}
                        @if ($proposition->message)
                            <div class="bg-mm-accent/20 border-l-4 border-mm-accent rounded-lg p-4 mb-6">
                                <p class="text-xs font-semibold text-mm-navy uppercase tracking-wide mb-1">Message de MON MAÎTRE</p>
                                <p class="text-sm text-mm-navy italic">"{{ $proposition->message }}"</p>
                            </div>
                        @endif

                        {{-- Actions selon statut --}}
                        @if ($proposition->statut === 'proposee')
                            <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-100">
                                <form method="POST" action="{{ route('parent.propositions.accepter', $proposition) }}" class="flex-1">
                                    @csrf
                                    <button type="submit"
                                        class="w-full bg-mm-secondary text-white font-semibold py-3 rounded-lg hover:bg-mm-secondary-hover hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                                        ✓ Accepter cette proposition
                                    </button>
                                </form>
                                <button type="button"
                                    onclick="document.getElementById('modal-refus-{{ $proposition->id }}').classList.remove('hidden')"
                                    class="flex-1 border border-gray-200 text-gray-700 font-semibold py-3 rounded-lg hover:bg-gray-50 hover:-translate-y-0.5 transition-all duration-300">
                                    ✗ Demander une autre proposition
                                </button>
                            </div>
                        @elseif ($proposition->statut === 'acceptee')
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-sm">
                                <p class="font-semibold text-green-800">✓ Proposition acceptée</p>
                                <p class="text-green-700 mt-1">
                                    MON MAÎTRE va maintenant organiser le placement.
                                    Vous serez contacté prochainement.
                                </p>
                            </div>
                        @elseif ($proposition->statut === 'refusee')
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-sm">
                                <p class="font-semibold text-gray-700">Proposition refusée</p>
                                <p class="text-gray-600 mt-1">
                                    MON MAÎTRE va vous proposer un nouveau profil.
                                </p>
                                @if ($proposition->raison_refus)
                                    <p class="text-gray-500 mt-2 italic">"{{ $proposition->raison_refus }}"</p>
                                @endif
                            </div>
                        @endif

                    </div>
                </div>

                {{-- Modal de refus --}}
                <div id="modal-refus-{{ $proposition->id }}" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-bold text-mm-navy">Demander une autre proposition</h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    Dites-nous pourquoi cette proposition ne vous convient pas.
                                </p>
                            </div>
                            <button type="button"
                                onclick="document.getElementById('modal-refus-{{ $proposition->id }}').classList.add('hidden')"
                                class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <form method="POST" action="{{ route('parent.propositions.refuser', $proposition) }}">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-mm-navy mb-2">
                                    Raison (optionnelle)
                                </label>
                                <textarea name="raison_refus" rows="3"
                                    placeholder="Ex : Les horaires ne correspondent pas, la zone est trop éloignée..."
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition"></textarea>
                            </div>

                            <div class="flex gap-3">
                                <button type="button"
                                    onclick="document.getElementById('modal-refus-{{ $proposition->id }}').classList.add('hidden')"
                                    class="flex-1 border border-gray-200 text-gray-600 text-sm font-semibold py-2.5 rounded-lg hover:bg-gray-50 transition">
                                    Annuler
                                </button>
                                <button type="submit"
                                    class="flex-1 bg-mm-navy text-white text-sm font-semibold py-2.5 rounded-lg hover:bg-mm-navy-hover transition">
                                    Confirmer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $propositions->links() }}
        </div>
    @else
        <div class="bg-white rounded-2xl border border-gray-100 py-16 text-center animate-on-scroll">
            <x-icons.bell class="w-12 h-12 text-gray-300 mx-auto mb-3" />
            <p class="text-gray-500 font-medium">Aucune proposition pour le moment.</p>
            <p class="text-sm text-gray-400 mt-1">
                MON MAÎTRE vous contactera dès qu'un répétiteur correspondant sera trouvé.
            </p>
        </div>
    @endif

@endsection