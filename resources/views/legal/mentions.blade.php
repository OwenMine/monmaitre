@extends('layouts.app')

@section('title', 'Mentions légales – MON MAÎTRE')

@section('content')
    <section class="py-16 bg-mm-light">
        <div class="max-w-3xl mx-auto px-6">
            <div class="mb-8">
                <p class="text-mm-secondary text-sm font-semibold uppercase tracking-wider mb-2">Légal</p>
                <h1 class="text-3xl md:text-4xl font-bold text-mm-navy mb-2">Mentions légales</h1>
            </div>

            <div class="bg-white rounded-2xl p-8 space-y-6 text-sm text-gray-700 leading-relaxed">
                <div>
                    <h2 class="font-bold text-mm-navy text-lg mb-2">Éditeur</h2>
                    <p><strong>MON MAÎTRE</strong><br>
                    Plateforme de placement de répétiteurs à domicile<br>
                    Abidjan, Côte d'Ivoire</p>
                </div>

                <div>
                    <h2 class="font-bold text-mm-navy text-lg mb-2">Contact</h2>
                    <p>📞 <a href="tel:+2250719167688" class="text-mm-secondary font-semibold">07 19 16 76 88</a><br>
                    ✉️ <a href="mailto:contact@monmaitre.ci" class="text-mm-secondary font-semibold">contact@monmaitre.ci</a></p>
                </div>

                <div>
                    <h2 class="font-bold text-mm-navy text-lg mb-2">Hébergement</h2>
                    <p>Les informations d'hébergement seront précisées lors de la mise en production.</p>
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