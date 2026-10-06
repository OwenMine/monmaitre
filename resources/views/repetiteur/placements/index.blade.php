@extends('layouts.repetiteur')

@section('title', 'Mes missions')

@section('content')

    {{-- En-tête --}}
    <div class="mb-8 animate-on-scroll">
        <h2 class="text-3xl font-bold text-[#132D39] tracking-tight">Mes missions</h2>
        <p class="text-gray-500 mt-1">Vos placements confirmés avec des parents.</p>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm animate-on-scroll">
            {{ session('success') }}
        </div>
    @endif

    @if ($placements->count() > 0)
        <div class="space-y-4">
            @foreach ($placements as $i => $placement)
                <a href="{{ route('repetiteur.placements.show', $placement) }}"
                   class="block bg-white rounded-2xl border border-gray-100 p-6 hover:shadow-lg hover:-translate-y-1 hover:border-gray-200 transition-all duration-300 animate-on-scroll delay-{{ min(($i % 4 + 1) * 100, 400) }}">

                    <div class="flex items-start justify-between gap-4 flex-wrap">

                        {{-- Infos principales --}}
                        <div class="flex items-start gap-4 flex-1 min-w-0">
                            <div class="w-12 h-12 rounded-full bg-[#55766F] flex items-center justify-center text-white font-bold flex-shrink-0">
                                {{ strtoupper(substr($placement->eleve->prenom, 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-mono text-xs font-bold text-[#132D39] bg-[#E7C273]/30 px-2 py-0.5 rounded">
                                        {{ $placement->reference }}
                                    </span>
                                </div>
                                <p class="font-bold text-[#132D39] truncate">
                                    {{ $placement->eleve->prenom }} {{ $placement->eleve->nom }}
                                    <span class="text-gray-400 font-normal">– {{ $placement->eleve->classe }}</span>
                                </p>
                                <p class="text-sm text-gray-500 truncate">
                                    {{ $placement->matiere }}
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    Depuis le {{ $placement->date_debut?->format('d/m/Y') ?? '—' }} • {{ $placement->frequence }}
                                </p>
                            </div>
                        </div>

                        {{-- Statut + flèche --}}
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-semibold whitespace-nowrap {{ $placement->statutCouleur() }}">
                                {{ $placement->statutLabel() }}
                            </span>
                            <x-icons.arrow-right class="w-5 h-5 text-gray-400" />
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $placements->links() }}
        </div>
    @else
        <div class="bg-white rounded-2xl border border-gray-100 py-16 text-center animate-on-scroll">
            <x-icons.academic class="w-12 h-12 text-gray-300 mx-auto mb-3" />
            <p class="text-gray-500 font-medium">Aucune mission pour le moment.</p>
            <p class="text-sm text-gray-400 mt-1">
                Vos missions apparaîtront ici dès que MON MAÎTRE vous placera chez un parent.
            </p>
        </div>
    @endif

@endsection