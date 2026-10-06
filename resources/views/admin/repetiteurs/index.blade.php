@extends('layouts.admin')

@section('title', 'Répétiteurs')

@section('content')

    {{-- En-tête --}}
    <div class="mb-8 animate-on-scroll">
        <h2 class="text-3xl font-bold text-mm-navy tracking-tight">Répétiteurs</h2>
        <p class="text-gray-500 mt-1">Gérez tous les profils de répétiteurs.</p>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm animate-on-scroll">
            {{ session('success') }}
        </div>
    @endif

    {{-- Onglets --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-2 mb-6 flex flex-wrap gap-2 animate-on-scroll">
        <a href="{{ route('admin.repetiteurs.index') }}"
           class="px-4 py-2 rounded-lg text-sm font-semibold transition {{ !request('statut') ? 'bg-mm-navy text-white' : 'text-gray-600 hover:bg-gray-50' }}">
            Tous <span class="ml-1 text-xs opacity-75">({{ $counts['tous'] }})</span>
        </a>
        <a href="{{ route('admin.repetiteurs.index', ['statut' => 'candidature_envoyee']) }}"
           class="px-4 py-2 rounded-lg text-sm font-semibold transition {{ request('statut') === 'candidature_envoyee' ? 'bg-mm-accent text-mm-navy' : 'text-gray-600 hover:bg-gray-50' }}">
            En attente <span class="ml-1 text-xs opacity-75">({{ $counts['attente'] }})</span>
        </a>
        <a href="{{ route('admin.repetiteurs.index', ['statut' => 'profil_valide']) }}"
           class="px-4 py-2 rounded-lg text-sm font-semibold transition {{ request('statut') === 'profil_valide' ? 'bg-mm-secondary text-white' : 'text-gray-600 hover:bg-gray-50' }}">
            Validés <span class="ml-1 text-xs opacity-75">({{ $counts['valides'] }})</span>
        </a>
        <a href="{{ route('admin.repetiteurs.index', ['statut' => 'profil_refuse']) }}"
           class="px-4 py-2 rounded-lg text-sm font-semibold transition {{ request('statut') === 'profil_refuse' ? 'bg-red-600 text-white' : 'text-gray-600 hover:bg-gray-50' }}">
            Refusés/Suspendus <span class="ml-1 text-xs opacity-75">({{ $counts['refuses'] }})</span>
        </a>
    </div>

    {{-- Recherche + filtres --}}
    <form method="GET" action="{{ route('admin.repetiteurs.index') }}" class="bg-white rounded-2xl border border-gray-100 p-4 mb-6 flex flex-wrap gap-3 animate-on-scroll">
        @if(request('statut'))
            <input type="hidden" name="statut" value="{{ request('statut') }}">
        @endif
        <div class="relative flex-1 min-w-[200px]">
            <x-icons.search class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
            <input type="text" name="q" value="{{ request('q') }}"
                placeholder="Rechercher par nom ou email..."
                class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">
        </div>
        <input type="text" name="matiere" value="{{ request('matiere') }}"
            placeholder="Matière (ex: Maths)"
            class="border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition w-40">
        <input type="text" name="commune" value="{{ request('commune') }}"
            placeholder="Commune (ex: Cocody)"
            class="border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition w-40">
        <button type="submit" class="bg-mm-navy text-white text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-mm-navy-hover hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
            Filtrer
        </button>
        @if(request('q') || request('statut') || request('matiere') || request('commune'))
            <a href="{{ route('admin.repetiteurs.index') }}" class="border border-gray-200 text-gray-600 text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-gray-50 transition">
                Réinitialiser
            </a>
        @endif
    </form>

    {{-- Tableau --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden animate-on-scroll">
        @if ($repetiteurs->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 text-left text-xs uppercase tracking-wide">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Répétiteur</th>
                            <th class="px-6 py-4 font-semibold">Matières</th>
                            <th class="px-6 py-4 font-semibold">Expérience</th>
                            <th class="px-6 py-4 font-semibold">Commune</th>
                            <th class="px-6 py-4 font-semibold">Statut</th>
                            <th class="px-6 py-4 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($repetiteurs as $rep)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        {{-- PHOTO DE PROFIL --}}
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
                                        <div>
                                            <div class="font-semibold text-mm-navy">{{ $rep->user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $rep->user->email ?? $rep->user->phone }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ implode(', ', array_slice($rep->matieres ?? [], 0, 2)) }}
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $rep->experience_annees }} an(s)
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $rep->user->commune ?? '—' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-semibold whitespace-nowrap
                                        @if($rep->statut === 'profil_valide') bg-green-100 text-green-700
                                        @elseif(in_array($rep->statut, ['profil_refuse', 'profil_suspendu'])) bg-red-100 text-red-700
                                        @elseif(in_array($rep->statut, ['candidature_envoyee', 'a_examiner'])) bg-mm-accent/30 text-mm-navy
                                        @else bg-gray-100 text-gray-700
                                        @endif">
                                        {{ $rep->statutLabel() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('admin.repetiteurs.show', $rep) }}"
                                           class="text-mm-secondary hover:text-mm-navy text-sm font-semibold transition">
                                            Voir
                                        </a>
                                        @if($rep->statut !== 'profil_valide')
                                            <form method="POST" action="{{ route('admin.repetiteurs.valider', $rep) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-800 text-sm font-semibold transition">
                                                    Valider
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                {{ $repetiteurs->links() }}
            </div>
        @else
            <div class="py-16 text-center">
                <x-icons.academic class="w-12 h-12 text-gray-300 mx-auto mb-3" />
                <p class="text-gray-500 font-medium">Aucun répétiteur trouvé.</p>
                @if(request('q') || request('statut') || request('matiere') || request('commune'))
                    <p class="text-sm text-gray-400 mt-1">Essayez de modifier vos filtres.</p>
                @endif
            </div>
        @endif
    </div>

@endsection