@extends('layouts.admin')

@section('title', 'Placements')

@section('content')

    {{-- En-tête --}}
    <div class="mb-8 animate-on-scroll">
        <h2 class="text-3xl font-bold text-mm-navy tracking-tight">Placements</h2>
        <p class="text-gray-500 mt-1">Suivi des placements confirmés entre parents et répétiteurs.</p>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm animate-on-scroll">
            {{ session('success') }}
        </div>
    @endif

    {{-- Onglets --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-2 mb-6 flex flex-wrap gap-2 animate-on-scroll">
        <a href="{{ route('admin.placements.index') }}"
           class="px-4 py-2 rounded-lg text-sm font-semibold transition {{ !request('statut') ? 'bg-mm-navy text-white' : 'text-gray-600 hover:bg-gray-50' }}">
            Tous <span class="ml-1 text-xs opacity-75">({{ $counts['tous'] }})</span>
        </a>
        <a href="{{ route('admin.placements.index', ['statut' => 'confirme']) }}"
           class="px-4 py-2 rounded-lg text-sm font-semibold transition {{ request('statut') === 'confirme' ? 'bg-mm-accent text-mm-navy' : 'text-gray-600 hover:bg-gray-50' }}">
            Confirmés <span class="ml-1 text-xs opacity-75">({{ $counts['confirmes'] }})</span>
        </a>
        <a href="{{ route('admin.placements.index', ['statut' => 'en_cours']) }}"
           class="px-4 py-2 rounded-lg text-sm font-semibold transition {{ request('statut') === 'en_cours' ? 'bg-mm-secondary text-white' : 'text-gray-600 hover:bg-gray-50' }}">
            En cours <span class="ml-1 text-xs opacity-75">({{ $counts['en_cours'] }})</span>
        </a>
        <a href="{{ route('admin.placements.index', ['statut' => 'termine']) }}"
           class="px-4 py-2 rounded-lg text-sm font-semibold transition {{ request('statut') === 'termine' ? 'bg-gray-700 text-white' : 'text-gray-600 hover:bg-gray-50' }}">
            Terminés <span class="ml-1 text-xs opacity-75">({{ $counts['termines'] }})</span>
        </a>
    </div>

    {{-- Recherche --}}
    <form method="GET" action="{{ route('admin.placements.index') }}" class="bg-white rounded-2xl border border-gray-100 p-4 mb-6 flex flex-wrap gap-3 animate-on-scroll">
        @if(request('statut'))
            <input type="hidden" name="statut" value="{{ request('statut') }}">
        @endif
        <div class="relative flex-1 min-w-[200px]">
            <x-icons.search class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
            <input type="text" name="q" value="{{ request('q') }}"
                placeholder="Rechercher par référence, parent ou élève..."
                class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">
        </div>
                <button type="submit" class="bg-mm-navy text-white text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-mm-navy-hover hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
            Rechercher
        </button>
        @if(request('q') || request('statut'))
            <a href="{{ route('admin.placements.index') }}" class="border border-gray-200 text-gray-600 text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-gray-50 transition">
                Réinitialiser
            </a>
        @endif

        {{-- Export CSV (conserve les filtres actifs) --}}
        <a href="{{ route('admin.placements.export', request()->only('statut', 'q')) }}"
           class="inline-flex items-center gap-2 border-2 border-green-600 text-green-700 text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-green-50 hover:-translate-y-0.5 transition-all duration-300">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Exporter CSV
        </a>
    </form>

    {{-- Tableau --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden animate-on-scroll">
        @if ($placements->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 text-left text-xs uppercase tracking-wide">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Référence</th>
                            <th class="px-6 py-4 font-semibold">Parent</th>
                            <th class="px-6 py-4 font-semibold">Élève</th>
                            <th class="px-6 py-4 font-semibold">Répétiteur</th>
                            <th class="px-6 py-4 font-semibold">Matière</th>
                            <th class="px-6 py-4 font-semibold">Statut</th>
                            <th class="px-6 py-4 font-semibold text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($placements as $placement)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <span class="font-mono font-semibold text-mm-navy">{{ $placement->reference }}</span>
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $placement->parent->name }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-mm-navy">{{ $placement->eleve->prenom }} {{ $placement->eleve->nom }}</div>
                                    <div class="text-xs text-gray-500">{{ $placement->eleve->classe }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    {{-- PHOTO RÉPÉTITEUR --}}
                                    <div class="flex items-center gap-2">
                                        @if($placement->repetiteur->photo_url)
                                            <img
                                                src="{{ $placement->repetiteur->photo_url }}"
                                                alt="{{ $placement->repetiteur->user->name }}"
                                                style="width: 32px; height: 32px; border-radius: 9999px; object-fit: cover; border: 2px solid rgba(20, 59, 143, 0.2); flex-shrink: 0;">
                                        @else
                                            <div
                                                class="flex items-center justify-center font-bold flex-shrink-0"
                                                style="width: 32px; height: 32px; border-radius: 9999px; background-color: #062B5C; color: #F5A623; font-size: 0.75rem;">
                                                {{ $placement->repetiteur->initiale }}
                                            </div>
                                        @endif
                                        <span class="text-gray-600">{{ $placement->repetiteur->user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $placement->matiere }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-semibold whitespace-nowrap {{ $placement->statutCouleur() }}">
                                        {{ $placement->statutLabel() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.placements.show', $placement) }}"
                                       class="inline-flex items-center gap-1 text-mm-secondary hover:text-mm-navy text-sm font-semibold transition group">
                                        Voir
                                        <x-icons.arrow-right class="w-3.5 h-3.5 transition-transform group-hover:translate-x-1" />
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                {{ $placements->links() }}
            </div>
        @else
            <div class="py-16 text-center">
                <x-icons.target class="w-12 h-12 text-gray-300 mx-auto mb-3" />
                <p class="text-gray-500 font-medium">Aucun placement pour le moment.</p>
            </div>
        @endif
    </div>

@endsection