@extends('layouts.app')

@section('title', 'Inscription Répétiteur – MON MAÎTRE')

@section('content')

    <section class="pt-6 md:pt-8 pb-20 md:pb-24 bg-mm-light pattern-dots">
        <div class="max-w-2xl mx-auto px-6 md:px-8">

            {{-- Fil d'Ariane --}}
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2 animate-on-scroll">
                <a href="{{ route('accueil') }}" class="hover:text-mm-navy transition">Accueil</a>
                <span>/</span>
                <span class="text-mm-navy font-medium">Inscription Répétiteur</span>
            </div>

            {{-- En-tête --}}
            <div class="text-center mb-8 animate-on-scroll">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-mm-secondary mb-3">
                    <x-icons.academic class="w-7 h-7 text-white" />
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-mm-navy tracking-tight mb-1">
                    Devenir répétiteur
                </h1>
                <p class="text-gray-500 text-sm">Créez votre espace répétiteur en quelques secondes.</p>
            </div>

            {{-- Erreurs --}}
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm animate-on-scroll">
                    <p class="font-semibold mb-1">Veuillez corriger les erreurs suivantes :</p>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Formulaire --}}
            <form method="POST" action="{{ route('pre-inscription.repetiteur.store') }}"
                  class="bg-white p-6 md:p-8 rounded-2xl border border-gray-100 space-y-5 animate-on-scroll delay-100">
                @csrf

                <div>
                    <label class="block text-sm font-medium mb-1.5">Nom complet *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        placeholder="Ex : Koffi Jean"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1.5">Email *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            placeholder="votre@email.com"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1.5">Téléphone *</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" required
                            placeholder="07 XX XX XX XX"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1.5">WhatsApp</label>
                    <input type="tel" name="whatsapp" value="{{ old('whatsapp') }}"
                        placeholder="07 XX XX XX XX"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1.5">Commune *</label>
                        <input type="text" name="commune" value="{{ old('commune') }}" required
                            placeholder="Cocody, Yopougon..."
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1.5">Quartier</label>
                        <input type="text" name="quartier" value="{{ old('quartier') }}"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1.5">Matières enseignées *</label>
                    <input type="text" name="matieres" value="{{ old('matieres') }}" required
                        placeholder="Mathématiques, Physique, Français..."
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1.5">Niveaux / Classes *</label>
                    <input type="text" name="niveaux" value="{{ old('niveaux') }}" required
                        placeholder="6e à Terminale, CM2..."
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1.5">Années d'expérience *</label>
                    <input type="number" name="experience" value="{{ old('experience', 0) }}" required min="0" max="50"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">
                </div>

                <div x-data="{ showPassword: false }">
                    <label class="block text-sm font-medium mb-1.5">Mot de passe *</label>
                    <div class="relative">
                        <input
                            :type="showPassword ? 'text' : 'password'"
                            type="password"
                            name="password"
                            required
                            minlength="8"
                            placeholder="Minimum 8 caractères"
                            class="w-full border border-gray-200 rounded-lg pl-3 pr-12 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">

                        <button
                            type="button"
                            @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-mm-secondary transition"
                            :title="showPassword ? 'Masquer' : 'Afficher'">
                            <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1.5">Confirmer le mot de passe *</label>
                    <input type="password" name="password_confirmation" required minlength="8"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">
                </div>

                <div class="flex items-start bg-gray-50 rounded-lg p-4">
                    <input type="checkbox" required class="rounded border-gray-300 text-mm-secondary focus:ring-mm-secondary mt-1 mr-3">
                    <label class="text-sm text-gray-600 leading-relaxed">
                        J'autorise MON MAÎTRE à utiliser mes coordonnées pour créer mon espace et étudier ma candidature.
                    </label>
                </div>

                <button type="submit"
                    class="w-full bg-mm-secondary text-white py-3.5 rounded-lg font-semibold hover:bg-mm-secondary-hover hover:shadow-lg hover:shadow-mm-secondary/30 hover:-translate-y-0.5 transition-all duration-300">
                    CRÉER MON ESPACE RÉPÉTITEUR
                </button>

                <p class="text-center text-sm text-gray-500 pt-2">
                    Déjà un compte ?
                    <a href="{{ route('login') }}" class="text-mm-secondary hover:text-mm-navy font-semibold transition">Connectez-vous</a>
                </p>
            </form>

            {{-- Alternative parent (une seule occurrence) --}}
            <div class="mt-10 text-center text-sm text-gray-500 animate-on-scroll delay-200">
                Vous cherchez un répétiteur pour votre enfant ?
                <a href="{{ route('pre-inscription.parent') }}" class="text-mm-secondary hover:text-mm-navy font-semibold transition">
                    Je cherche un répétiteur
                </a>
            </div>

        </div>
    </section>

@endsection