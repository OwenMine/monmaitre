@extends('layouts.repetiteur')

@section('title', 'Tableau de bord')

@section('content')

    {{-- Message d'accueil --}}
    <div class="mb-8 animate-on-scroll">
        <h2 class="text-3xl font-bold text-mm-secondary">Bonjour {{ auth()->user()->name }} 👋</h2>
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

    {{-- ============================================ --}}
    {{-- SI PAS DE PROFIL --}}
    {{-- ============================================ --}}
    @if (! $profil)
        <div class="card-hover animate-on-scroll">
            <p class="text-gray-700 mb-4">
                Vous n'avez pas encore de profil. Complétez-le pour recevoir des missions.
            </p>
            <a href="{{ route('repetiteur.profil.edit') }}"
               class="inline-flex items-center gap-2 bg-mm-secondary text-white px-6 py-3 rounded-lg font-semibold hover:bg-mm-secondary-hover hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                Créer mon profil
                <x-icons.arrow-right class="w-4 h-4" />
            </a>
        </div>
    @else

        {{-- ============================================ --}}
        {{-- CARTE PROFIL + STATUT --}}
        {{-- ============================================ --}}
        <div class="card-hover mb-6 animate-on-scroll">
            <div class="flex flex-col md:flex-row md:items-center gap-6">

                {{-- Photo --}}
                <div class="flex-shrink-0 self-center md:self-start">
                    @if ($profil->photo_url)
                        <img src="{{ $profil->photo_url }}" alt="{{ auth()->user()->name }}"
                             style="width: 96px; height: 96px; border-radius: 9999px; object-fit: cover; border: 3px solid rgba(20, 59, 143, 0.2); box-shadow: 0 10px 25px rgba(6, 43, 92, 0.15);">
                    @else
                        <div class="flex items-center justify-center font-bold"
                             style="width: 96px; height: 96px; border-radius: 9999px; background-color: #062B5C; color: #F5A623; font-size: 2.5rem; border: 3px solid rgba(20, 59, 143, 0.2); box-shadow: 0 10px 25px rgba(6, 43, 92, 0.15);">
                            {{ $profil->initiale }}
                        </div>
                    @endif
                </div>

                {{-- Infos --}}
                <div class="flex-1 text-center md:text-left">
                    <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-2">
                        Statut de votre profil
                    </p>
                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-sm font-semibold {{ $profil->statutCouleur() }}">
                        <span class="w-1.5 h-1.5 rounded-full
                            @if($profil->statut === 'profil_valide') bg-green-500
                            @elseif(in_array($profil->statut, ['profil_refuse', 'profil_suspendu'])) bg-red-500
                            @else bg-[#F5A623]
                            @endif"></span>
                        {{ $profil->statutLabel() }}
                    </span>

                    {{-- Badges --}}
                    @if (count($badges ?? []) > 0)
                        <div class="flex flex-wrap gap-2 mt-3 justify-center md:justify-start">
                            @foreach ($badges as $badge)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-semibold
                                    @if($badge['color'] === 'green') bg-green-100 text-green-700
                                    @elseif($badge['color'] === 'mm-accent') bg-mm-accent/30 text-mm-navy
                                    @elseif($badge['color'] === 'mm-secondary') bg-mm-secondary/10 text-mm-secondary
                                    @else bg-mm-navy/10 text-mm-navy
                                    @endif">
                                    <x-dynamic-component :component="'icons.' . $badge['icon']" class="w-3.5 h-3.5" />
                                    {{ $badge['label'] }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    @if ($profil->photo_url)
                        <p class="text-xs text-gray-400 mt-2">📸 Photo de profil enregistrée</p>
                    @else
                        <p class="text-xs text-orange-500 mt-2">⚠️ Aucune photo de profil. Ajoutez-en une pour rassurer les parents.</p>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="flex-shrink-0">
                    <a href="{{ route('repetiteur.profil.edit') }}"
                       class="inline-flex items-center gap-2 bg-mm-secondary text-white text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-mm-secondary-hover hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                        Compléter mon profil
                        <x-icons.arrow-right class="w-4 h-4" />
                    </a>
                </div>
            </div>
        </div>

        {{-- ============================================ --}}
        {{-- KPI STATS --}}
        {{-- ============================================ --}}
        @if (isset($stats))
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">

                {{-- Missions actives --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-6 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 animate-on-scroll delay-100">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-11 h-11 rounded-xl bg-mm-secondary/10 flex items-center justify-center">
                            <x-icons.target class="w-5 h-5 text-mm-secondary" />
                        </div>
                    </div>
                    <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-widest mb-2">Missions actives</p>
                    <p class="text-3xl font-bold text-mm-navy leading-none">{{ $stats['missions_actives'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $stats['missions_total'] }} au total</p>
                </div>

                {{-- Séances effectuées --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-6 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 animate-on-scroll delay-200">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-11 h-11 rounded-xl bg-green-100 flex items-center justify-center">
                            <x-icons.check-badge class="w-5 h-5 text-green-600" />
                        </div>
                    </div>
                    <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-widest mb-2">Séances effectuées</p>
                    <p class="text-3xl font-bold text-mm-navy leading-none">{{ $stats['seances_effectuees'] }}</p>
                    @if ($stats['seances_en_attente'] > 0)
                        <p class="text-xs text-orange-500 mt-1">{{ $stats['seances_en_attente'] }} en attente de validation</p>
                    @else
                        <p class="text-xs text-gray-500 mt-1">{{ $stats['heures_totales'] }}h au total</p>
                    @endif
                </div>

                {{-- Note moyenne --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-6 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 animate-on-scroll delay-300">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-11 h-11 rounded-xl bg-mm-accent/25 flex items-center justify-center">
                            <x-icons.academic class="w-5 h-5 text-mm-navy" />
                        </div>
                    </div>
                    <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-widest mb-2">Note moyenne</p>
                    @if ($stats['note_moyenne'] > 0)
                        <p class="text-3xl font-bold
                            @if($stats['note_moyenne'] >= 8) text-green-600
                            @elseif($stats['note_moyenne'] >= 6) text-mm-secondary
                            @elseif($stats['note_moyenne'] >= 4) text-orange-500
                            @else text-red-600
                            @endif leading-none">
                            {{ $stats['note_moyenne'] }}<span class="text-lg text-gray-400">/10</span>
                        </p>
                        <p class="text-xs text-gray-500 mt-1">{{ $stats['nb_evaluations'] }} évaluation(s)</p>
                    @else
                        <p class="text-3xl font-bold text-gray-300 leading-none">—</p>
                        <p class="text-xs text-gray-400 mt-1">Aucune évaluation</p>
                    @endif
                </div>

                {{-- Revenus estimés --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-6 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 animate-on-scroll delay-400">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-11 h-11 rounded-xl bg-mm-navy/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-mm-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-widest mb-2">Revenus estimés</p>
                    <p class="text-2xl font-bold text-mm-navy leading-none">{{ number_format($stats['revenus_estimes'], 0, ',', ' ') }}</p>
                    <p class="text-xs text-gray-500 mt-1">FCFA (estimation)</p>
                </div>

            </div>
        @endif

        {{-- ============================================ --}}
        {{-- GRAPHIQUE + TAUX PRÉSENCE --}}
        {{-- ============================================ --}}
        @if (isset($stats))
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">

                {{-- Graphique --}}
                <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 p-6 animate-on-scroll">
                    <h3 class="font-bold text-mm-navy mb-4">Séances effectuées (4 dernières semaines)</h3>
                    <div style="height: 220px;">
                        <canvas id="chartSeances"></canvas>
                    </div>
                </div>

                {{-- Taux de présence --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-6 animate-on-scroll delay-100 flex flex-col justify-center">
                    <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-widest mb-3">Taux de présence</p>
                    <p class="text-5xl font-bold
                        @if($stats['taux_presence'] >= 90) text-green-600
                        @elseif($stats['taux_presence'] >= 70) text-mm-secondary
                        @elseif($stats['taux_presence'] >= 50) text-orange-500
                        @else text-red-600
                        @endif leading-none mb-3">
                        {{ $stats['taux_presence'] }}%
                    </p>
                    <div class="w-full bg-gray-100 rounded-full h-2 mb-3">
                        <div class="h-2 rounded-full transition-all duration-500
                            @if($stats['taux_presence'] >= 90) bg-green-600
                            @elseif($stats['taux_presence'] >= 70) bg-mm-secondary
                            @elseif($stats['taux_presence'] >= 50) bg-orange-500
                            @else bg-red-600
                            @endif"
                            style="width: {{ $stats['taux_presence'] }}%">
                        </div>
                    </div>
                    @if ($stats['absences'] > 0)
                        <p class="text-xs text-red-500">{{ $stats['absences'] }} absence(s) au total</p>
                    @else
                        <p class="text-xs text-green-600">✓ Aucune absence</p>
                    @endif
                </div>
            </div>
        @endif

        {{-- ============================================ --}}
        {{-- DERNIÈRES MISSIONS --}}
        {{-- ============================================ --}}
        <div class="card-hover animate-on-scroll delay-200">
            <div class="flex items-center justify-between flex-wrap gap-4 mb-4">
                <div>
                    <h3 class="font-bold text-mm-navy">Mes dernières missions</h3>
                    <p class="text-sm text-gray-500 mt-1">Vos 3 placements les plus récents.</p>
                </div>
                <a href="{{ route('repetiteur.placements.index') }}"
                   class="inline-flex items-center gap-2 bg-mm-secondary text-white text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-mm-secondary-hover hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                    Voir toutes mes missions
                    <x-icons.arrow-right class="w-4 h-4" />
                </a>
            </div>

            @if (($dernieresMissions ?? collect())->count() > 0)
                <div class="space-y-3 mt-5">
                    @foreach ($dernieresMissions as $placement)
                        <a href="{{ route('repetiteur.placements.show', $placement) }}"
                           class="block p-4 rounded-xl border border-gray-100 hover:border-mm-secondary/30 hover:bg-mm-secondary/5 transition">
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3 flex-1 min-w-0">
                                    <div class="w-10 h-10 rounded-full bg-mm-secondary/10 flex items-center justify-center font-bold text-mm-secondary flex-shrink-0">
                                        {{ strtoupper(substr($placement->eleve->prenom, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-mm-navy text-sm truncate">
                                            {{ $placement->eleve->prenom }} {{ $placement->eleve->nom }}
                                        </p>
                                        <p class="text-xs text-gray-500 truncate">
                                            {{ $placement->matiere }} • {{ $placement->eleve->classe }}
                                        </p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-semibold whitespace-nowrap {{ $placement->statutCouleur() }}">
                                    {{ $placement->statutLabel() }}
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <x-icons.target class="w-10 h-10 text-gray-300 mx-auto mb-2" />
                    <p class="text-sm text-gray-500">Aucune mission pour le moment.</p>
                </div>
            @endif
        </div>

    @endif

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('chartSeances');
        if (! canvas) return;

        const ctx = canvas.getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 220);
        gradient.addColorStop(0, 'rgba(20, 59, 143, 0.3)');
        gradient.addColorStop(1, 'rgba(20, 59, 143, 0.02)');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json(array_column($seancesParSemaine ?? [], 'label')),
                datasets: [{
                    label: 'Séances',
                    data: @json(array_column($seancesParSemaine ?? [], 'count')),
                    backgroundColor: '#143B8F',
                    borderRadius: 8,
                    borderSkipped: false,
                    maxBarThickness: 60,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, color: '#9CA3AF' },
                        grid: { color: '#F3F4F6' }
                    },
                    x: {
                        ticks: { color: '#9CA3AF' },
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>
@endpush