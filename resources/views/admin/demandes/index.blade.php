@extends('layouts.admin')

@section('title', 'Demandes')

@section('content')

    {{-- En-tête --}}
    <div class="mb-8 animate-on-scroll">
        <h2 class="text-3xl font-bold text-[#132D39] tracking-tight">Demandes de répétiteurs</h2>
        <p class="text-gray-500 mt-1">Gérez toutes les demandes des parents.</p>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm animate-on-scroll">
            {{ session('success') }}
        </div>
    @endif

    {{-- Onglets --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-2 mb-6 flex flex-wrap gap-2 animate-on-scroll">
        <a href="{{ route('admin.demandes.index') }}"
           class="px-4 py-2 rounded-lg text-sm font-semibold transition {{ !request('statut') ? 'bg-[#132D39] text-white' : 'text-gray-600 hover:bg-gray-50' }}">
            Toutes
            <span class="ml-1 text-xs opacity-75">({{ $counts['toutes'] }})</span>
        </a>
        <a href="{{ route('admin.demandes.index', ['statut' => 'demande_envoyee']) }}"
           class="px-4 py-2 rounded-lg text-sm font-semibold transition {{ request('statut') === 'demande_envoyee' ? 'bg-[#E7C273] text-[#132D39]' : 'text-gray-600 hover:bg-gray-50' }}">
            Nouvelles
            <span class="ml-1 text-xs opacity-75">({{ $counts['nouvelles'] }})</span>
        </a>
        <a href="{{ route('admin.demandes.index', ['statut' => 'en_analyse']) }}"
           class="px-4 py-2 rounded-lg text-sm font-semibold transition {{ request('statut') === 'en_analyse' ? 'bg-[#55766F] text-white' : 'text-gray-600 hover:bg-gray-50' }}">
            En cours
            <span class="ml-1 text-xs opacity-75">({{ $counts['en_cours'] }})</span>
        </a>
        <a href="{{ route('admin.demandes.index', ['statut' => 'en_cours']) }}"
           class="px-4 py-2 rounded-lg text-sm font-semibold transition {{ request('statut') === 'en_cours' ? 'bg-[#55766F] text-white' : 'text-gray-600 hover:bg-gray-50' }}">
            Placées
            <span class="ml-1 text-xs opacity-75">({{ $counts['placees'] }})</span>
        </a>
        <a href="{{ route('admin.demandes.index', ['statut' => 'termine']) }}"
           class="px-4 py-2 rounded-lg text-sm font-semibold transition {{ request('statut') === 'termine' ? 'bg-gray-700 text-white' : 'text-gray-600 hover:bg-gray-50' }}">
            Terminées
            <span class="ml-1 text-xs opacity-75">({{ $counts['terminees'] }})</span>
        </a>
    </div>

    {{-- Recherche --}}
    <form method="GET" action="{{ route('admin.demandes.index') }}" class="bg-white rounded-2xl border border-gray-100 p-4 mb-6 flex flex-wrap gap-3 animate-on-scroll">
        @if(request('statut'))
            <input type="hidden" name="statut" value="{{ request('statut') }}">
        @endif
        <div class="relative flex-1 min-w-[200px]">
            <x-icons.search class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
            <input type="text" name="q" value="{{ request('q') }}"
                placeholder="Rechercher par parent, élève ou matière..."
                class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2.5 text-sm focus:outline-none focus:border-[#55766F] focus:ring-2 focus:ring-[#55766F]/20 transition">
        </div>
        <button type="submit" class="bg-[#132D39] text-white text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-[#0d1f27] hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
            Rechercher
        </button>
        @if(request('q') || request('statut'))
            <a href="{{ route('admin.demandes.index') }}" class="border border-gray-200 text-gray-600 text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-gray-50 transition">
                Réinitialiser
            </a>
        @endif
    </form>

    {{-- Tableau --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden animate-on-scroll">
        @if ($demandes->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 text-left text-xs uppercase tracking-wide">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Réf.</th>
                            <th class="px-6 py-4 font-semibold">Parent</th>
                            <th class="px-6 py-4 font-semibold">Élève</th>
                            <th class="px-6 py-4 font-semibold">Matières</th>
                            <th class="px-6 py-4 font-semibold">Statut</th>
                            <th class="px-6 py-4 font-semibold">Date</th>
                            <th class="px-6 py-4 font-semibold text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($demandes as $demande)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-mono font-semibold text-[#132D39]">
                                    #MM-{{ str_pad($demande->id, 4, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-[#132D39]">{{ $demande->parent->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $demande->parent->commune }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-[#132D39]">{{ $demande->eleve->prenom }} {{ $demande->eleve->nom }}</div>
                                    <div class="text-xs text-gray-500">{{ $demande->eleve->classe }}</div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ implode(', ', array_slice($demande->matieres ?? [], 0, 2)) }}
                                    @if(count($demande->matieres ?? []) > 2)
                                        <span class="text-xs text-gray-400">+{{ count($demande->matieres) - 2 }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-semibold whitespace-nowrap
                                        @if($demande->statut === 'demande_envoyee') bg-[#E7C273] text-[#132D39]
                                        @elseif($demande->statut === 'termine') bg-gray-100 text-gray-700
                                        @elseif($demande->statut === 'annule') bg-red-100 text-red-700
                                        @elseif(in_array($demande->statut, ['placement_confirme', 'en_cours'])) bg-green-100 text-green-700
                                        @else bg-[#55766F] text-white
                                        @endif">
                                        {{ $demande->statutLabel() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-500 text-xs">
                                    {{ $demande->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.demandes.show', $demande) }}"
                                       class="inline-flex items-center gap-1 text-[#55766F] hover:text-[#132D39] text-sm font-semibold transition group">
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
                {{ $demandes->links() }}
            </div>
        @else
            <div class="py-16 text-center">
                <x-icons.document class="w-12 h-12 text-gray-300 mx-auto mb-3" />
                <p class="text-gray-500 font-medium">Aucune demande trouvée.</p>
                @if(request('q') || request('statut'))
                    <p class="text-sm text-gray-400 mt-1">Essayez de modifier vos filtres.</p>
                @endif
            </div>
        @endif
    </div>

@endsection