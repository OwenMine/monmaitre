@extends('layouts.repetiteur')

@section('title', 'Séances – ' . $placement->reference)

@section('content')

    {{-- Fil d'Ariane --}}
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6 animate-on-scroll">
        <a href="{{ route('repetiteur.placements.index') }}" class="hover:text-mm-secondary transition">Mes missions</a>
        <span>/</span>
        <a href="{{ route('repetiteur.placements.show', $placement) }}" class="hover:text-mm-secondary transition font-mono">{{ $placement->reference }}</a>
        <span>/</span>
        <span class="text-mm-secondary font-medium">Séances</span>
    </div>

    {{-- En-tête --}}
    <div class="mb-8 flex justify-between items-start flex-wrap gap-4 animate-on-scroll">
        <div>
            <h2 class="text-3xl font-bold text-mm-navy tracking-tight">Suivi des séances</h2>
            <p class="text-gray-500 mt-1">
                Mission <span class="font-mono font-semibold text-mm-navy">{{ $placement->reference }}</span>
                — {{ $placement->eleve->prenom }} {{ $placement->eleve->nom }}
            </p>
        </div>
        <a href="{{ route('repetiteur.seances.create', $placement) }}"
           class="inline-flex items-center gap-2 bg-mm-secondary text-white text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-mm-secondary-hover hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
            <x-icons.plus class="w-4 h-4" />
            Enregistrer une séance
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 animate-on-scroll">
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Séances effectuées</p>
            <p class="text-3xl font-bold text-mm-navy">{{ $placement->nombreSeancesEffectuees() }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Taux de présence</p>
            <p class="text-3xl font-bold text-green-600">{{ $placement->tauxPresenceRepetiteur() }}%</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Heures totales</p>
            <p class="text-3xl font-bold text-mm-navy">{{ round($placement->totalMinutesEffectuees() / 60, 1) }}h</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Absences</p>
            <p class="text-3xl font-bold {{ $placement->nombreAbsencesRepetiteur() > 0 ? 'text-red-600' : 'text-mm-navy' }}">
                {{ $placement->nombreAbsencesRepetiteur() }}
            </p>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm animate-on-scroll">
            {{ session('success') }}
        </div>
    @endif

    {{-- Liste des séances --}}
    @if ($seances->count() > 0)
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden animate-on-scroll">
            <div class="divide-y divide-gray-100">
                @foreach ($seances as $seance)
                    <div class="px-6 py-5 hover:bg-gray-50 transition">
                        <div class="flex items-start justify-between gap-4 flex-wrap">

                            <div class="flex items-start gap-4 flex-1 min-w-0">
                                {{-- Date --}}
                                <div class="flex-shrink-0 w-14 h-14 rounded-xl bg-mm-secondary/10 flex flex-col items-center justify-center">
                                    <p class="text-[10px] font-semibold text-mm-secondary uppercase">{{ $seance->date->translatedFormat('M') }}</p>
                                    <p class="text-lg font-bold text-mm-secondary leading-none">{{ $seance->date->format('d') }}</p>
                                </div>

                                {{-- Contenu --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-semibold {{ $seance->statutCouleur() }}">
                                            {{ $seance->statutLabel() }}
                                        </span>
                                        <span class="text-xs text-gray-500">{{ $seance->duree_minutes }} min</span>
                                    </div>
                                    <p class="text-sm text-mm-navy font-medium">{{ $seance->contenu }}</p>
                                    @if ($seance->commentaire)
                                        <p class="text-xs text-gray-500 mt-1 italic">{{ $seance->commentaire }}</p>
                                    @endif
                                </div>
                            </div>

                            {{-- Actions --}}
                            <form method="POST" action="{{ route('repetiteur.seances.destroy', $seance) }}"
                                  onsubmit="return confirm('Supprimer cette séance ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 transition p-2">
                                    <x-icons.trash class="w-4 h-4" />
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                {{ $seances->links() }}
            </div>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-gray-100 py-16 text-center animate-on-scroll">
            <x-icons.clock class="w-12 h-12 text-gray-300 mx-auto mb-3" />
            <p class="text-gray-500 font-medium">Aucune séance enregistrée.</p>
            <p class="text-sm text-gray-400 mt-1 mb-6">
                Commencez par enregistrer votre première séance.
            </p>
            <a href="{{ route('repetiteur.seances.create', $placement) }}"
               class="inline-flex items-center gap-2 bg-mm-secondary text-white text-sm font-semibold px-6 py-3 rounded-lg hover:bg-mm-secondary-hover transition">
                <x-icons.plus class="w-4 h-4" />
                Enregistrer une séance
            </a>
        </div>
    @endif

@endsection