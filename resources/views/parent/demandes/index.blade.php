@extends('layouts.parent')

@section('title', 'Mes demandes')

@section('content')

    {{-- En-tête --}}
    <div class="mb-8 flex justify-between items-start flex-wrap gap-4 animate-on-scroll">
        <div>
            <h2 class="text-3xl font-bold text-[#132D39] tracking-tight">Mes demandes</h2>
            <p class="text-gray-500 mt-1">Suivez l'état de vos demandes de répétiteurs.</p>
        </div>
        <a href="{{ route('parent.demandes.create') }}"
           class="inline-flex items-center gap-2 bg-[#132D39] text-white text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-[#0d1f27] hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
            <x-icons.plus class="w-4 h-4" />
            Nouvelle demande
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm animate-on-scroll">
            {{ session('success') }}
        </div>
    @endif

    @if ($demandes->count() > 0)
        <div class="space-y-4">
            @foreach ($demandes as $i => $demande)
                <a href="{{ route('parent.demandes.show', $demande) }}"
                   class="block bg-white rounded-2xl border border-gray-100 p-6 hover:shadow-lg hover:-translate-y-1 hover:border-gray-200 transition-all duration-300 animate-on-scroll delay-{{ min(($i % 4 + 1) * 100, 400) }}">

                    <div class="flex items-start justify-between gap-4 flex-wrap">

                        {{-- Infos principales --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="font-mono text-xs font-bold text-[#132D39] bg-[#E7C273]/30 px-2 py-0.5 rounded">
                                    #MM-{{ str_pad($demande->id, 4, '0', STR_PAD_LEFT) }}
                                </span>
                                <span class="text-xs text-gray-400">•</span>
                                <span class="text-xs text-gray-400">{{ $demande->created_at->format('d/m/Y') }}</span>
                            </div>

                            <p class="font-bold text-[#132D39] text-lg">
                                {{ $demande->eleve->prenom }} {{ $demande->eleve->nom }}
                                <span class="text-gray-400 font-normal text-sm">– {{ $demande->eleve->classe }}</span>
                            </p>

                            <p class="text-sm text-gray-500 mt-1">
                                {{ implode(', ', array_slice($demande->matieres ?? [], 0, 3)) }}
                            </p>
                        </div>

                        {{-- Statut + flèche --}}
                        <div class="flex items-center gap-4">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-semibold whitespace-nowrap
                                @if($demande->statut === 'demande_envoyee') bg-[#E7C273] text-[#132D39]
                                @elseif($demande->statut === 'termine') bg-gray-100 text-gray-700
                                @elseif($demande->statut === 'annule') bg-red-100 text-red-700
                                @elseif(in_array($demande->statut, ['placement_confirme', 'en_cours'])) bg-green-100 text-green-700
                                @else bg-[#55766F] text-white
                                @endif">
                                {{ $demande->statutLabel() }}
                            </span>
                            <x-icons.arrow-right class="w-5 h-5 text-gray-400" />
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $demandes->links() }}
        </div>
    @else
        <div class="bg-white rounded-2xl border border-gray-100 py-16 text-center animate-on-scroll">
            <x-icons.document class="w-12 h-12 text-gray-300 mx-auto mb-3" />
            <p class="text-gray-500 font-medium">Vous n'avez pas encore de demande.</p>
            <p class="text-sm text-gray-400 mt-1 mb-6">Créez votre première demande pour que MON MAÎTRE recherche un répétiteur.</p>
            <a href="{{ route('parent.demandes.create') }}"
               class="inline-flex items-center gap-2 bg-[#132D39] text-white text-sm font-semibold px-6 py-3 rounded-lg hover:bg-[#0d1f27] hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                <x-icons.plus class="w-4 h-4" />
                Créer ma première demande
            </a>
        </div>
    @endif

@endsection