@extends('layouts.app')

@section('title', 'Politique de confidentialité – MON MAÎTRE')

@section('content')
    <section class="py-16 bg-mm-light">
        <div class="max-w-3xl mx-auto px-6">
            <div class="mb-8">
                <p class="text-mm-secondary text-sm font-semibold uppercase tracking-wider mb-2">Légal</p>
                <h1 class="text-3xl md:text-4xl font-bold text-mm-navy mb-2">Politique de confidentialité</h1>
                <p class="text-sm text-gray-500">Dernière mise à jour : {{ date('d/m/Y') }}</p>
            </div>

            <div class="bg-white rounded-2xl p-8 space-y-6 text-sm text-gray-700 leading-relaxed">
                <div>
                    <h2 class="font-bold text-mm-navy text-lg mb-2">1. Données collectées</h2>
                    <p>Nous collectons les données que vous nous fournissez : nom, téléphone, commune, et selon votre profil, informations sur l'élève ou sur votre parcours académique.</p>
                </div>

                <div>
                    <h2 class="font-bold text-mm-navy text-lg mb-2">2. Utilisation des données</h2>
                    <p>Vos données servent uniquement à : mettre en relation parents et répétiteurs, assurer le suivi des placements, vous envoyer des notifications et améliorer nos services.</p>
                </div>

                <div>
                    <h2 class="font-bold text-mm-navy text-lg mb-2">3. Partage des données</h2>
                    <p>Vos coordonnées peuvent être partagées avec la contrepartie uniquement lors d'un placement confirmé. Nous ne vendons ni ne louons vos données à des tiers.</p>
                </div>

                <div>
                    <h2 class="font-bold text-mm-navy text-lg mb-2">4. Vos droits</h2>
                    <p>Vous pouvez à tout moment demander à consulter, modifier ou supprimer vos données en nous contactant.</p>
                </div>

                <div>
                    <h2 class="font-bold text-mm-navy text-lg mb-2">5. Contact</h2>
                    <p>📞 <a href="tel:+2250719167688" class="text-mm-secondary font-semibold">07 19 16 76 88</a><br>
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