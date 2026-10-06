@extends('layouts.admin')

@section('title', 'Tableau de bord')

@section('content')

    {{-- Titre --}}
    <div class="mb-8 animate-on-scroll">
        <h2 class="text-3xl font-bold text-[#132D39] tracking-tight">Tableau de bord</h2>
        <p class="text-gray-500 mt-1">Bienvenue, {{ auth()->user()->name }}.</p>
    </div>

    {{-- Statistiques --}}
    @php
        $cards = [
            ['label' => 'Parents',             'value' => $stats['total_parents'],         'color' => 'mm-dark',  'icon' => 'users',        'dot' => 'bg-[#132D39]'],
            ['label' => 'Répétiteurs',         'value' => $stats['total_repetiteurs'],     'color' => 'mm-sage',  'icon' => 'academic',     'dot' => 'bg-[#55766F]'],
            ['label' => 'Candidatures',        'value' => $stats['candidatures_attente'],  'color' => 'mm-gold',  'icon' => 'clock',        'dot' => 'bg-[#E7C273]'],
            ['label' => 'Répétiteurs validés', 'value' => $stats['repetiteurs_valides'],   'color' => 'mm-sage',  'icon' => 'check-badge',  'dot' => 'bg-[#55766F]'],
            ['label' => 'Demandes totales',    'value' => $stats['total_demandes'],        'color' => 'mm-dark',  'icon' => 'document',     'dot' => 'bg-[#132D39]'],
            ['label' => 'Nouvelles demandes',  'value' => $stats['demandes_nouvelles'],    'color' => 'mm-gold',  'icon' => 'bell',         'dot' => 'bg-[#E7C273]'],
            ['label' => 'Demandes en cours',   'value' => $stats['demandes_en_cours'],     'color' => 'mm-sage',  'icon' => 'cog',          'dot' => 'bg-[#55766F]'],
            ['label' => 'Placements actifs',   'value' => $stats['placements_actifs'],     'color' => 'mm-dark',  'icon' => 'target',       'dot' => 'bg-[#132D39]'],
        ];
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-10">
        @foreach ($cards as $i => $card)
            <div class="bg-white rounded-2xl border border-gray-100 p-6 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 hover:border-gray-200 animate-on-scroll delay-{{ min(($i % 4 + 1) * 100, 400) }}">

                {{-- Icône + point coloré --}}
                <div class="flex justify-between items-start mb-5">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center
                        @if($card['color'] === 'mm-dark') bg-[#132D39]/8
                        @elseif($card['color'] === 'mm-sage') bg-[#55766F]/8
                        @else bg-[#E7C273]/25
                        @endif">
                        <x-dynamic-component :component="'icons.' . $card['icon']"
                            class="w-5 h-5
                            @if($card['color'] === 'mm-dark') text-[#132D39]
                            @elseif($card['color'] === 'mm-sage') text-[#55766F]
                            @else text-[#132D39]
                            @endif" />
                    </div>
                    <span class="w-2 h-2 rounded-full {{ $card['dot'] }} mt-1"></span>
                </div>

                {{-- Label --}}
                <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-widest mb-2">{{ $card['label'] }}</p>

                {{-- Valeur --}}
                <p class="text-4xl font-bold text-[#132D39] leading-none">{{ $card['value'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- Dernières demandes + Actions rapides --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

        {{-- Dernières demandes --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 overflow-hidden animate-on-scroll">
            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-[#132D39] text-lg">Dernières demandes</h3>
                <a href="{{ route('admin.demandes.index') }}"
                   class="inline-flex items-center gap-1 text-sm text-[#55766F] hover:text-[#132D39] font-medium transition group">
                    Voir tout
                    <x-icons.arrow-right class="w-4 h-4 transition-transform group-hover:translate-x-1" />
                </a>
            </div>

            @if ($dernieresDemandes->count() > 0)
                <div class="divide-y divide-gray-100">
                    @foreach ($dernieresDemandes as $demande)
                        <a href="{{ route('admin.demandes.show', $demande) }}" class="px-6 py-4 flex items-center justify-between gap-4 hover:bg-gray-50 transition">
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-semibold text-[#132D39] truncate">
                                    {{ $demande->eleve->prenom }} {{ $demande->eleve->nom }}
                                    <span class="text-gray-400 font-normal">– {{ $demande->eleve->classe }}</span>
                                </p>
                                <p class="text-xs text-gray-500 mt-0.5 truncate">
                                    #MM-{{ str_pad($demande->id, 4, '0', STR_PAD_LEFT) }} • {{ $demande->parent->name }}
                                </p>
                            </div>
                            <span class="text-[11px] font-semibold px-2.5 py-1 rounded-md bg-[#E7C273]/25 text-[#132D39] whitespace-nowrap">
                                {{ $demande->statutLabel() }}
                            </span>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="px-6 py-12 text-center text-sm text-gray-400">
                    Aucune demande pour le moment.
                </div>
            @endif
        </div>

        {{-- Actions rapides --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6 animate-on-scroll delay-100">
            <h3 class="font-bold text-[#132D39] text-lg mb-5">Actions rapides</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.demandes.index') }}" class="flex items-center gap-3 p-4 rounded-xl border border-gray-100 hover:border-[#E7C273] hover:bg-[#E7C273]/5 hover:-translate-y-0.5 transition-all duration-300 text-sm font-medium text-[#132D39]">
                    <div class="w-9 h-9 rounded-lg bg-[#E7C273]/20 flex items-center justify-center">
                        <x-icons.document class="w-4 h-4 text-[#132D39]" />
                    </div>
                    Voir les demandes
                </a>
                <a href="{{ route('admin.repetiteurs.index') }}" class="flex items-center gap-3 p-4 rounded-xl border border-gray-100 hover:border-[#55766F] hover:bg-[#55766F]/5 hover:-translate-y-0.5 transition-all duration-300 text-sm font-medium text-[#132D39]">
                    <div class="w-9 h-9 rounded-lg bg-[#55766F]/10 flex items-center justify-center">
                        <x-icons.academic class="w-4 h-4 text-[#55766F]" />
                    </div>
                    Voir les répétiteurs
                </a>
                <a href="{{ route('admin.placements.index') }}" class="flex items-center gap-3 p-4 rounded-xl border border-gray-100 hover:border-[#132D39] hover:bg-[#132D39]/5 hover:-translate-y-0.5 transition-all duration-300 text-sm font-medium text-[#132D39]">
                    <div class="w-9 h-9 rounded-lg bg-[#132D39]/8 flex items-center justify-center">
                        <x-icons.target class="w-4 h-4 text-[#132D39]" />
                    </div>
                    Voir les placements
                </a>
            </div>
        </div>

    </div>

        {{-- Derniers répétiteurs --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden animate-on-scroll delay-200">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-mm-navy text-lg">Répétiteurs récents</h3>
            <a href="{{ route('admin.repetiteurs.index') }}"
               class="inline-flex items-center gap-1 text-sm text-mm-secondary hover:text-mm-navy font-medium transition group">
                Voir tout
                <x-icons.arrow-right class="w-4 h-4 transition-transform group-hover:translate-x-1" />
            </a>
        </div>

        @if ($derniersRepetiteurs->count() > 0)
            <div class="divide-y divide-gray-100">
                @foreach ($derniersRepetiteurs as $rep)
                    <a href="{{ route('admin.repetiteurs.show', $rep) }}" class="px-6 py-4 flex items-center gap-4 hover:bg-gray-50 transition">

                        {{-- PHOTO RÉPÉTITEUR --}}
                        @if($rep->photo_url)
                            <img
                                src="{{ $rep->photo_url }}"
                                alt="{{ $rep->user->name }}"
                                style="width: 40px; height: 40px; border-radius: 9999px; object-fit: cover; border: 2px solid rgba(20, 59, 143, 0.2); flex-shrink: 0;">
                        @else
                            <div
                                class="flex items-center justify-center font-bold flex-shrink-0"
                                style="width: 40px; height: 40px; border-radius: 9999px; background-color: #062B5C; color: #F5A623; font-size: 0.875rem;">
                                {{ $rep->initiale }}
                            </div>
                        @endif

                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-mm-navy truncate">{{ $rep->user->name }}</p>
                            <p class="text-xs text-gray-500 truncate">
                                {{ implode(', ', array_slice($rep->matieres ?? [], 0, 2)) }}
                                @if ($rep->user->commune)
                                    • {{ $rep->user->commune }}
                                @endif
                            </p>
                        </div>
                        <span class="text-[11px] font-semibold px-2.5 py-1 rounded-md whitespace-nowrap
                            @if($rep->statut === 'profil_valide') bg-green-100 text-green-700
                            @elseif(in_array($rep->statut, ['profil_refuse', 'profil_suspendu'])) bg-red-100 text-red-700
                            @else bg-gray-100 text-gray-700
                            @endif">
                            {{ $rep->statutLabel() }}
                        </span>
                    </a>
                @endforeach
            </div>
        @else
            <div class="px-6 py-12 text-center text-sm text-gray-400">
                Aucun répétiteur pour le moment.
            </div>
        @endif
    </div>

@endsection