@extends('layouts.app')

@section('title', 'Politique cookies – MON MAÎTRE')

@section('content')
    <section class="py-16 bg-mm-light">
        <div class="max-w-3xl mx-auto px-6">
            <div class="mb-8">
                <p class="text-mm-secondary text-sm font-semibold uppercase tracking-wider mb-2">Légal</p>
                <h1 class="text-3xl md:text-4xl font-bold text-mm-navy mb-2">Politique cookies</h1>
                <p class="text-sm text-gray-500">Dernière mise à jour : {{ date('d/m/Y') }}</p>
            </div>

            <div class="bg-white rounded-2xl p-8 space-y-6 text-sm text-gray-700 leading-relaxed">
                <div>
                    <h2 class="font-bold text-mm-navy text-lg mb-2">1. Qu'est-ce qu'un cookie ?</h2>
                    <p>Un cookie est un petit fichier texte stocké sur votre appareil. Il nous permet de vous reconnaître lors de vos visites suivantes.</p>
                </div>

                <div>
                    <h2 class="font-bold text-mm-navy text-lg mb-2">2. Cookies utilisés</h2>
                    <p>MON MAÎTRE utilise uniquement des <strong>cookies essentiels</strong> au fonctionnement du site (session, authentification). Aucun cookie publicitaire ou de traçage n'est utilisé.</p>
                </div>

                <div>
                    <h2 class="font-bold text-mm-navy text-lg mb-2">3. Gestion des cookies</h2>
                    <p>Vous pouvez à tout moment supprimer les cookies via les paramètres de votre navigateur. Cela peut affecter votre expérience utilisateur.</p>
                </div>
            </div>

            <div class="mt-8 text-center">
                <a href="{{ route('accueil') }}" class="inline-flex items-center gap-2 text-mm-secondary hover:text-mm-navy font-semibold text-sm transition">
                    <x-icons.arrow-left class="w-4 h-4" />
                    Retour à l'accueil
                </a>
            </div>
        </div>
    </section>
@endsection