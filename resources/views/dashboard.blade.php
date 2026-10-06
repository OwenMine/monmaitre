@extends('layouts.repetiteur')

@section('title', 'Tableau de bord')

@section('content')

    {{-- Message d'accueil --}}
    <div class="mb-8 animate-on-scroll">
        <h2 class="text-3xl font-bold text-[#132D39]">Bonjour {{ auth()->user()->name }} 👋</h2>
        <p class="text-gray-500 mt-1">Bienvenue dans votre espace répétiteur.</p>
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

    @php $profil = auth()->user()->repetiteur; @endphp

    {{-- Statut du profil --}}
    <div class="card-hover mb-6 animate-on-scroll">
        <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-3">Statut de votre profil</p>

        @if ($profil)
            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-sm font-semibold {{ $profil->statutCouleur() }}">
                <span class="w-1.5 h-1.5 rounded-full
                    @if($profil->statut === 'profil_valide') bg-green-500
                    @elseif(in_array($profil->statut, ['profil_refuse', 'profil_suspendu'])) bg-red-500
                    @else bg-[#E7C273]
                    @endif"></span>
                {{ $profil->statutLabel() }}
            </span>

            <div class="mt-4">
                <a href="{{ route('repetiteur.profil.edit') }}"
                   class="inline-flex items-center gap-2 text-sm font-semibold text-[#55766F] hover:text-[#132D39] transition group">
                    Compléter mon profil
                    <x-icons.arrow-right class="w-4 h-4 transition-transform group-hover:translate-x-1" />
                </a>
            </div>
        @else
            <p class="text-gray-700 mb-4">
                Vous n'avez pas encore de profil. Complétez-le pour recevoir des missions.
            </p>
            <a href="{{ route('repetiteur.profil.edit') }}"
               class="inline-flex items-center gap-2 bg-[#55766F] text-white px-6 py-3 rounded-lg font-semibold hover:bg-[#466158] hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                Créer mon profil
                <x-icons.arrow-right class="w-4 h-4" />
            </a>
        @endif
    </div>

    {{-- Mes missions --}}
    <div class="card-hover animate-on-scroll delay-100">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h3 class="font-bold text-[#132D39]">Mes missions</h3>
                <p class="text-sm text-gray-500 mt-1">Vos placements confirmés avec des parents.</p>
            </div>
            <a href="{{ route('repetiteur.placements.index') }}"
               class="inline-flex items-center gap-2 bg-[#55766F] text-white text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-[#466158] hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                Voir mes missions
                <x-icons.arrow-right class="w-4 h-4" />
            </a>
        </div>
    </div>

@endsection