@extends('layouts.admin')

@section('title', 'Suivi des placements')

@section('content')

    {{-- En-tête --}}
    <div class="mb-8 animate-on-scroll">
        <p class="text-sm text-mm-secondary font-semibold uppercase tracking-wider mb-1">Tableau de bord</p>
        <h2 class="text-3xl font-bold text-mm-navy tracking-tight">Suivi des placements</h2>
        <p class="text-gray-500 mt-1">Vue globale de l'activité et des indicateurs clés.</p>
    </div>

    {{-- ========== KPI ========== --}}
    @php
        $cards = [
            ['label' => 'Placements actifs',    'value' => $stats['placements_actifs'],    'color' => 'mm-navy',      'icon' => 'target'],
            ['label' => 'Séances ce mois',      'value' => $stats['seances_ce_mois'],      'color' => 'mm-secondary', 'icon' => 'clock'],
            ['label' => 'Taux de présence',     'value' => $stats['taux_presence_global'] . '%', 'color' => 'green', 'icon' => 'check-badge'],
            ['label' => 'Note moyenne',         'value' => $stats['note_moyenne_globale'] . '/10', 'color' => 'mm-accent', 'icon' => 'academic'],
        ];
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        @foreach ($cards as $i => $card)
            <div class="bg-white rounded-2xl border border-gray-100 p-6 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 animate-on-scroll delay-{{ ($i + 1) * 100 }}">

                <div class="flex justify-between items-start mb-4">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center
                        @if($card['color'] === 'mm-navy') bg-mm-navy/8
                        @elseif($card['color'] === 'mm-secondary') bg-mm-secondary/8
                        @elseif($card['color'] === 'mm-accent') bg-mm-accent/25
                        @else bg-green-100
                        @endif">
                        <x-dynamic-component :component="'icons.' . $card['icon']"
                            class="w-5 h-5
                            @if($card['color'] === 'mm-navy') text-mm-navy
                            @elseif($card['color'] === 'mm-secondary') text-mm-secondary
                            @elseif($card['color'] === 'mm-accent') text-mm-navy
                            @else text-green-600
                            @endif" />
                    </div>
                </div>

                <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-widest mb-2">{{ $card['label'] }}</p>
                <p class="text-3xl font-bold text-mm-navy leading-none">{{ $card['value'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- ========== ALERTES ========== --}}
    @if (count($alertes) > 0)
        <div class="bg-red-50 border border-red-200 rounded-2xl p-6 mb-8 animate-on-scroll">
            <h3 class="font-bold text-red-800 mb-4 flex items-center gap-2">
                <x-icons.bell class="w-5 h-5" />
                {{ count($alertes) }} alerte(s) à traiter
            </h3>
            <div class="space-y-2">
                @foreach ($alertes as $alerte)
                    <a href="{{ $alerte['url'] }}"
                       class="block p-3 rounded-lg bg-white border border-red-100 hover:border-red-300 hover:bg-red-50 transition">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
                                @if($alerte['couleur'] === 'red') bg-red-100 text-red-700
                                @else bg-orange-100 text-orange-700
                                @endif">
                                <x-dynamic-component :component="'icons.' . $alerte['icone']" class="w-4 h-4" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-mm-navy">{{ $alerte['titre'] }}</p>
                                <p class="text-xs text-gray-600 mt-0.5">{{ $alerte['message'] }}</p>
                            </div>
                            <x-icons.arrow-right class="w-4 h-4 text-gray-400 flex-shrink-0 mt-1" />
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ========== GRAPHIQUES ========== --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

        {{-- Graphique 1 : Évolution séances --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6 animate-on-scroll">
            <h3 class="font-bold text-mm-navy mb-4">Séances effectuées (12 dernières semaines)</h3>
            <div style="height: 260px;">
                <canvas id="chartSeances"></canvas>
            </div>
        </div>

        {{-- Graphique 2 : Répartition statuts --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6 animate-on-scroll delay-100">
            <h3 class="font-bold text-mm-navy mb-4">Répartition des placements par statut</h3>
            <div style="height: 260px;">
                <canvas id="chartStatuts"></canvas>
            </div>
        </div>
    </div>

    {{-- ========== TABLEAU PLACEMENTS ACTIFS ========== --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden animate-on-scroll">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between flex-wrap gap-3">
            <div>
                <h3 class="font-bold text-mm-navy text-lg">Placements actifs</h3>
                <p class="text-sm text-gray-500 mt-0.5">Suivi individuel des placements en cours.</p>
            </div>
            <a href="{{ route('admin.placements.index') }}"
               class="inline-flex items-center gap-1 text-sm text-mm-secondary hover:text-mm-navy font-medium transition group">
                Voir tous les placements
                <x-icons.arrow-right class="w-4 h-4 transition-transform group-hover:translate-x-1" />
            </a>
        </div>

        @if ($placementsActifs->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 text-left text-xs uppercase tracking-wide">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Référence</th>
                            <th class="px-6 py-4 font-semibold">Élève</th>
                            <th class="px-6 py-4 font-semibold">Répétiteur</th>
                            <th class="px-6 py-4 font-semibold text-center">Séances</th>
                            <th class="px-6 py-4 font-semibold text-center">Absences</th>
                            <th class="px-6 py-4 font-semibold text-center">Présence</th>
                            <th class="px-6 py-4 font-semibold text-center">Note</th>
                            <th class="px-6 py-4 font-semibold text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($placementsActifs as $p)
                            @php
                                $taux = $p->seances_total_count > 0
                                    ? round(($p->seances_effectuees_count / $p->seances_total_count) * 100, 0)
                                    : 0;
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <span class="font-mono font-semibold text-mm-navy text-xs">{{ $p->reference }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-mm-navy">{{ $p->eleve->prenom }} {{ $p->eleve->nom }}</div>
                                    <div class="text-xs text-gray-500">{{ $p->eleve->classe }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        @if($p->repetiteur->photo_url)
                                            <img src="{{ $p->repetiteur->photo_url }}"
                                                 style="width: 28px; height: 28px; border-radius: 9999px; object-fit: cover; flex-shrink: 0;">
                                        @else
                                            <div class="flex items-center justify-center font-bold flex-shrink-0"
                                                 style="width: 28px; height: 28px; border-radius: 9999px; background-color: #062B5C; color: #F5A623; font-size: 0.7rem;">
                                                {{ $p->repetiteur->initiale }}
                                            </div>
                                        @endif
                                        <span class="text-gray-600 text-xs">{{ $p->repetiteur->user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center font-semibold text-mm-navy">{{ $p->seances_effectuees_count }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if ($p->seances_absence_count > 0)
                                        <span class="font-semibold text-red-600">{{ $p->seances_absence_count }}</span>
                                    @else
                                        <span class="text-gray-400">0</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-semibold {{ $taux >= 80 ? 'text-green-600' : ($taux >= 50 ? 'text-orange-500' : 'text-red-600') }}">
                                        {{ $taux }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($p->evaluation)
                                        <span class="font-bold {{ $p->evaluation->noteCouleur() }}">{{ $p->evaluation->note }}/10</span>
                                    @else
                                        <span class="text-gray-400 text-xs">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.placements.show', $p) }}"
                                       class="text-mm-secondary hover:text-mm-navy text-xs font-semibold transition">
                                        Voir
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                {{ $placementsActifs->links() }}
            </div>
        @else
            <div class="py-16 text-center">
                <x-icons.target class="w-12 h-12 text-gray-300 mx-auto mb-3" />
                <p class="text-gray-500 font-medium">Aucun placement actif pour le moment.</p>
            </div>
        @endif
    </div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {

        // ============================================
        // GRAPHIQUE 1 : Séances par semaine
        // ============================================
        const ctx1 = document.getElementById('chartSeances').getContext('2d');
        const gradient = ctx1.createLinearGradient(0, 0, 0, 260);
        gradient.addColorStop(0, 'rgba(20, 59, 143, 0.3)');
        gradient.addColorStop(1, 'rgba(20, 59, 143, 0.02)');

        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: @json(array_column($seancesParSemaine, 'label')),
                datasets: [{
                    label: 'Séances effectuées',
                    data: @json(array_column($seancesParSemaine, 'count')),
                    borderColor: '#143B8F',
                    backgroundColor: gradient,
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#143B8F',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1, color: '#9CA3AF' }, grid: { color: '#F3F4F6' } },
                    x: { ticks: { color: '#9CA3AF' }, grid: { display: false } }
                }
            }
        });

        // ============================================
        // GRAPHIQUE 2 : Répartition statuts
        // ============================================
        const statutsLabels = {
            'en_attente': 'En attente',
            'confirme': 'Confirmé',
            'en_cours': 'En cours',
            'evaluation': 'Évaluation',
            'termine': 'Terminé',
            'remplacement': 'Remplacement',
            'annule': 'Annulé',
        };
        const statutsCouleurs = {
            'en_attente': '#9CA3AF',
            'confirme': '#F5A623',
            'en_cours': '#143B8F',
            'evaluation': '#0B3D91',
            'termine': '#10B981',
            'remplacement': '#F97316',
            'annule': '#EF4444',
        };

        const dataStatuts = @json($repartitionStatuts);
        const labels = Object.keys(dataStatuts).map(k => statutsLabels[k] || k);
        const valeurs = Object.values(dataStatuts);
        const couleurs = Object.keys(dataStatuts).map(k => statutsCouleurs[k] || '#6B7280');

        const ctx2 = document.getElementById('chartStatuts').getContext('2d');
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: valeurs,
                    backgroundColor: couleurs,
                    borderColor: '#FFFFFF',
                    borderWidth: 3,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            color: '#374151',
                            padding: 12,
                            font: { size: 12 }
                        }
                    }
                },
                cutout: '65%',
            }
        });

    });
</script>
@endpush