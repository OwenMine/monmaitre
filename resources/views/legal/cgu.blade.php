@extends('layouts.app')

@section('title', 'Conditions Générales d\'Utilisation – MON MAÎTRE')

@section('content')
    <section class="py-16 bg-mm-light">
        <div class="max-w-3xl mx-auto px-6">
            <div class="mb-8">
                <p class="text-mm-secondary text-sm font-semibold uppercase tracking-wider mb-2">Légal</p>
                <h1 class="text-3xl md:text-4xl font-bold text-mm-navy mb-2">Conditions Générales d'Utilisation</h1>
                <p class="text-sm text-gray-500">Dernière mise à jour : {{ date('d/m/Y') }}</p>
            </div>

            <div class="bg-white rounded-2xl p-8 space-y-6 text-sm text-gray-700 leading-relaxed">
                <div>
                    <h2 class="font-bold text-mm-navy text-lg mb-2">1. Objet</h2>
                    <p>MON MAÎTRE est une plateforme de mise en relation entre parents et répétiteurs à domicile en Côte d'Ivoire. Notre service consiste à rechercher, sélectionner et proposer le répétiteur adapté au besoin scolaire de votre enfant.</p>
                </div>

                <div>
                    <h2 class="font-bold text-mm-navy text-lg mb-2">2. Inscription</h2>
                    <p>L'inscription est gratuite et se fait via les formulaires dédiés (parent ou répétiteur). Les informations fournies doivent être exactes et à jour. L'utilisateur est responsable de la confidentialité de ses identifiants.</p>
                </div>

                <div>
                    <h2 class="font-bold text-mm-navy text-lg mb-2">3. Utilisation du service</h2>
                    <p>MON MAÎTRE se réserve le droit de suspendre ou refuser tout profil ne respectant pas nos critères de qualité, ou pour tout comportement contraire à l'éthique éducative.</p>
                </div>

                <div>
                    <h2 class="font-bold text-mm-navy text-lg mb-2">4. Responsabilité</h2>
                    <p>MON MAÎTRE met tout en œuvre pour sélectionner des répétiteurs qualifiés. Cependant, la relation contractuelle finale (tarifs, organisation des séances) reste entre le parent et le répétiteur.</p>
                </div>

                <div>
                    <h2 class="font-bold text-mm-navy text-lg mb-2">5. Contact</h2>
                    <p>Pour toute question relative aux présentes CGU :<br>
                    📞 <a href="tel:+2250719167688" class="text-mm-secondary font-semibold">07 19 16 76 88</a><br>
                    ✉️ <a href="mailto:contact@monmaitre.ci" class="text-mm-secondary font-semibold">contact@monmaitre.ci</a></p>
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