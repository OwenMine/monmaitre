@extends('layouts.parent')

@section('title', 'Mes placements')

@section('content')

    {{-- En-tête --}}
    <div class="mb-8 animate-on-scroll">
        <h2 class="text-3xl font-bold text-mm-navy tracking-tight">Mes placements</h2>
        <p class="text-gray-500 mt-1">Suivi de vos répétiteurs à domicile.</p>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm animate-on-scroll">
            {{ session('success') }}
        </div>
    @endif

    @if ($placements->count() > 0)
        <div class="space-y-4">
            @foreach ($placements as $i => $placement)
                <a href="{{ route('parent.placements.show', $placement) }}"
                   class="block bg-white rounded-2xl border border-gray-100 p-6 hover:shadow-lg hover:-translate-y-1 hover:border-gray-200 transition-all duration-300 animate-on-scroll delay-{{ min(($i % 4 + 1) * 100, 400) }}">

                    <div class="flex items-start justify-between gap-4 flex-wrap">

                        {{-- Infos principales --}}
                        <div class="flex items-start gap-4 flex-1 min-w-0">

                            {{-- PHOTO RÉPÉTITEUR --}}
                            @if($placement->repetiteur->photo_url)
                                <img
                                    src="{{ $placement->repetiteur->photo_url }}"
                                    alt="{{ $placement->repetiteur->user->name }}"
                                    style="width: 48px; height: 48px; border-radius: 9999px; object-fit: cover; border: 2px solid rgba(20, 59, 143, 0.2); flex-shrink: 0;">
                            @else
                                <div
                                    class="flex items-center justify-center font-bold flex-shrink-0"
                                    style="width: 48px; height: 48px; border-radius: 9999px; background-color: #062B5C; color: #F5A623; font-size: 1rem;">
                                    {{ $placement->repetiteur->initiale }}
                                </div>
                            @endif

                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-mono text-xs font-bold text-mm-navy bg-mm-accent/30 px-2 py-0.5 rounded">
                                        {{ $placement->reference }}
                                    </span>
                                </div>
                                <p class="font-bold text-mm-navy truncate">
                                    {{ $placement->repetiteur->user->name }}
                                </p>
                                <p class="text-sm text-gray-500 truncate">
                                    {{ $placement->matiere }} • {{ $placement->eleve->prenom }} {{ $placement->eleve->nom }}
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    Depuis le {{ $placement->date_debut?->format('d/m/Y') ?? '—' }}
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
            <x-icons.target class="w-12 h-12 text-gray-300 mx-auto mb-3" />
            <p class="text-gray-500 font-medium">Aucun placement pour le moment.</p>
            <p class="text-sm text-gray-400 mt-1">
                Vos placements apparaîtront ici dès qu'une proposition sera acceptée.
            </p>
        </div>
    @endif

@endsection