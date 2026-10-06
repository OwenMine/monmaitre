@extends('layouts.repetiteur')

@section('title', 'Nouvelle séance – ' . $placement->reference)

@section('content')

    {{-- Fil d'Ariane --}}
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6 animate-on-scroll">
        <a href="{{ route('repetiteur.placements.index') }}" class="hover:text-mm-secondary transition">Mes missions</a>
        <span>/</span>
        <a href="{{ route('repetiteur.seances.index', $placement) }}" class="hover:text-mm-secondary transition font-mono">{{ $placement->reference }}</a>
        <span>/</span>
        <span class="text-mm-secondary font-medium">Nouvelle séance</span>
    </div>

    <div class="mb-8 animate-on-scroll">
        <h2 class="text-3xl font-bold text-mm-navy tracking-tight">Nouvelle séance</h2>
        <p class="text-gray-500 mt-1">
            Mission <span class="font-mono font-semibold text-mm-navy">{{ $placement->reference }}</span>
            — {{ $placement->eleve->prenom }} {{ $placement->eleve->nom }}
        </p>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm animate-on-scroll">
            <p class="font-semibold mb-1">Veuillez corriger les erreurs suivantes :</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('repetiteur.seances.store', $placement) }}"
          class="bg-white rounded-2xl border border-gray-100 p-6 md:p-8 space-y-5 animate-on-scroll">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Date de la séance *</label>
                <input type="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}"
                    max="{{ now()->format('Y-m-d') }}" required
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Durée (minutes) *</label>
                <input type="number" name="duree_minutes" value="{{ old('duree_minutes', 60) }}"
                    min="15" max="300" required
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Statut de la séance *</label>
            <select name="statut" required class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">
                <option value="effectuee" {{ old('statut') === 'effectuee' ? 'selected' : '' }}>Effectuée</option>
                <option value="absence_repetiteur" {{ old('statut') === 'absence_repetiteur' ? 'selected' : '' }}>Absence de ma part</option>
                <option value="absence_eleve" {{ old('statut') === 'absence_eleve' ? 'selected' : '' }}>Absence de l'élève</option>
                <option value="annulee" {{ old('statut') === 'annulee' ? 'selected' : '' }}>Annulée</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Contenu de la séance *</label>
            <textarea name="contenu" rows="4" required
                placeholder="Ex : Révision des équations du premier degré, exercices 5 à 12 du manuel."
                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">{{ old('contenu') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Commentaire (optionnel)</label>
            <textarea name="commentaire" rows="3"
                placeholder="Ex : L'élève a bien progressé mais doit revoir les priorités opératoires."
                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">{{ old('commentaire') }}</textarea>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-100">
            <a href="{{ route('repetiteur.seances.index', $placement) }}"
               class="flex-1 border border-gray-200 text-gray-600 text-sm font-semibold py-3 rounded-lg hover:bg-gray-50 transition text-center">
                Annuler
            </a>
            <button type="submit"
                class="flex-1 bg-mm-secondary text-white text-sm font-semibold py-3 rounded-lg hover:bg-mm-secondary-hover hover:shadow-lg transition">
                Enregistrer la séance
            </button>
        </div>
    </form>

@endsection