{{-- Vue désactivée : l'inscription se fait via /pre-inscription/parent
     ou /pre-inscription/repetiteur. Voir RegisteredUserController. --}}

     @extends('layouts.guest')

@section('title', 'Créer un compte – MON MAÎTRE')

@section('content')
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-mm-navy mb-3">
            <x-icons.users class="w-7 h-7 text-mm-accent" />
        </div>
        <h1 class="text-2xl font-bold text-mm-navy mb-1">Créer un compte</h1>
        <p class="text-sm text-gray-500">
            Choisissez votre profil pour commencer.
        </p>
    </div>

    <div class="space-y-3">
        <a href="{{ route('pre-inscription.parent') }}"
           class="block p-5 rounded-xl border-2 border-gray-100 hover:border-mm-navy hover:bg-mm-navy/5 transition group">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-mm-navy/10 flex items-center justify-center group-hover:bg-mm-navy transition">
                    <x-icons.users class="w-6 h-6 text-mm-navy group-hover:text-mm-accent transition" />
                </div>
                <div class="flex-1">
                    <p class="font-bold text-mm-navy">Je cherche un répétiteur</p>
                    <p class="text-xs text-gray-500 mt-0.5">Espace parent — pour créer une demande</p>
                </div>
                <x-icons.arrow-right class="w-5 h-5 text-gray-400 group-hover:text-mm-navy transition" />
            </div>
        </a>

        <a href="{{ route('pre-inscription.repetiteur') }}"
           class="block p-5 rounded-xl border-2 border-gray-100 hover:border-mm-secondary hover:bg-mm-secondary/5 transition group">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-mm-secondary/10 flex items-center justify-center group-hover:bg-mm-secondary transition">
                    <x-icons.academic class="w-6 h-6 text-mm-secondary group-hover:text-white transition" />
                </div>
                <div class="flex-1">
                    <p class="font-bold text-mm-navy">Devenir répétiteur</p>
                    <p class="text-xs text-gray-500 mt-0.5">Espace répétiteur — pour proposer vos services</p>
                </div>
                <x-icons.arrow-right class="w-5 h-5 text-gray-400 group-hover:text-mm-secondary transition" />
            </div>
        </a>
    </div>

    <p class="text-center text-sm text-gray-500 mt-6">
        Déjà un compte ?
        <a href="{{ route('login') }}" class="text-mm-secondary hover:text-mm-navy font-medium transition">
            Connectez-vous
        </a>
    </p>
@endsection