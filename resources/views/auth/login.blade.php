<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion – MON MAÎTRE</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-icon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <x-assets-loader />
</head>
<body class="min-h-screen bg-mm-light">

    <div class="min-h-screen flex">

        {{-- ====================================================== --}}
        {{-- COLONNE GAUCHE : Image de fond + Branding --}}
        {{-- ====================================================== --}}
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">

            {{-- Image de fond (avec fallback Unsplash) --}}
            <img
                src="{{ asset('images/carousel/slide-1.jpg') }}"
                alt=""
                class="absolute inset-0 w-full h-full object-cover"
                onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=1200&q=80';">

            {{-- Overlay bleu dégradé (assure la lisibilité) --}}
            <div class="absolute inset-0"
                 style="background: linear-gradient(135deg, rgba(6, 43, 92, 0.95) 0%, rgba(20, 59, 143, 0.85) 50%, rgba(11, 61, 145, 0.75) 100%);">
            </div>

            {{-- Décor --}}
            <div class="absolute -top-20 -right-20 w-80 h-80 rounded-full bg-mm-accent/20 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 rounded-full bg-mm-secondary/40 blur-3xl"></div>

            {{-- Contenu --}}
            <div class="relative z-10 flex flex-col justify-between p-12 text-white w-full">

                {{-- Logo (couleurs originales sur pastille blanche) --}}
                <div>
                    <div class="inline-flex items-center justify-center bg-white rounded-2xl px-5 py-3 shadow-xl">
                        <img
                            src="{{ asset('images/logo-full.png') }}"
                            alt="MON MAÎTRE"
                            class="h-16 w-auto">
                    </div>
                </div>

                {{-- Titre --}}
                <div class="max-w-md">
                    <h2 class="text-4xl lg:text-5xl font-bold leading-tight mb-4">
                        Bienvenue<br>à nouveau !
                    </h2>
                    <p class="text-gray-100 text-lg leading-relaxed">
                        Connectez-vous pour accéder à votre espace personnel et suivre vos demandes, propositions et placements.
                    </p>
                </div>

                {{-- Bas de page --}}
                <div>
                    <p class="text-xs text-mm-accent font-semibold tracking-wider mb-2">
                        CONFIANCE • SOUTIEN • RÉUSSITE
                    </p>
                    <p class="text-sm text-gray-300">
                        © {{ date('Y') }} MON MAÎTRE – Côte d'Ivoire
                    </p>
                </div>
            </div>
        </div>

        {{-- ====================================================== --}}
        {{-- COLONNE DROITE : Formulaire --}}
        {{-- ====================================================== --}}
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 md:p-12 bg-mm-light">
            <div class="w-full max-w-md">

                {{-- Logo mobile --}}
                <div class="lg:hidden flex justify-center mb-8">
                    <img
                        src="{{ asset('images/logo-full.png') }}"
                        alt="MON MAÎTRE"
                        class="h-20 w-auto">
                </div>

                <h2 class="text-2xl md:text-3xl font-bold text-mm-navy mb-2">Connexion</h2>
                <p class="text-gray-500 mb-8">Accédez à votre espace personnel.</p>

                {{-- Erreurs --}}
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">
                        @foreach ($errors->all() as $error)
                            <div>• {{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                @if (session('status'))
                    <div class="bg-mm-secondary/10 border border-mm-secondary text-mm-navy px-4 py-3 rounded-lg mb-6 text-sm">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    {{-- Identifier --}}
                    <div>
                        <label class="block text-sm font-medium text-mm-navy mb-2">
                            Email ou téléphone
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <x-icons.user-circle class="w-5 h-5" />
                            </span>
                            <input
                                type="text"
                                name="identifier"
                                value="{{ old('identifier') }}"
                                required autofocus
                                placeholder="votre@email.com ou 07 19 16 76 88"
                                class="w-full border border-gray-200 rounded-lg pl-10 pr-3 py-3 focus:outline-none focus:ring-2 focus:ring-mm-secondary focus:border-transparent transition">
                        </div>
                        <p class="text-xs text-gray-400 mt-1.5">
                            Parents : utilisez votre numéro de téléphone.
                        </p>
                    </div>

                    {{-- Mot de passe --}}
                    <div x-data="{ showPassword: false }">
                        <label class="block text-sm font-medium text-mm-navy mb-2">Mot de passe</label>
                        <div class="relative">
                            {{-- Icône cadenas --}}
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <x-icons.lock-closed class="w-5 h-5" />
                            </span>

                            {{-- Champ mot de passe --}}
                            <input
                                :type="showPassword ? 'text' : 'password'"
                                type="password"
                                name="password"
                                required
                                placeholder="Votre mot de passe"
                                class="w-full border border-gray-200 rounded-lg pl-10 pr-12 py-3 focus:outline-none focus:ring-2 focus:ring-mm-secondary focus:border-transparent transition">

                            {{-- Bouton œil (afficher/masquer) --}}
                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-mm-secondary transition"
                                :title="showPassword ? 'Masquer le mot de passe' : 'Afficher le mot de passe'">

                                {{-- Œil ouvert (mot de passe caché) --}}
                                <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>

                                {{-- Œil barré (mot de passe visible) --}}
                                <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Remember / Forgot --}}
                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 text-mm-secondary focus:ring-mm-secondary">
                            <span class="ml-2 text-gray-600">Se souvenir de moi</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-mm-secondary hover:text-mm-navy transition">
                                Mot de passe oublié ?
                            </a>
                        @endif
                    </div>

                    {{-- Bouton --}}
                    <button type="submit"
                        class="w-full bg-mm-navy text-white py-3 rounded-lg font-semibold hover:bg-mm-navy-hover transition shadow-lg shadow-mm-navy/20 inline-flex items-center justify-center gap-2">
                        Se connecter
                        <x-icons.arrow-right class="w-4 h-4" />
                    </button>
                </form>

                {{-- Contact en cas de problème --}}
                <div class="mt-6 p-3 bg-mm-accent/10 border border-mm-accent/30 rounded-lg text-xs text-mm-navy">
                    <strong>Mot de passe oublié et pas d'email ?</strong><br>
                    Contactez MON MAÎTRE :
                    <a href="tel:+2250719167688" class="font-semibold underline">07 19 16 76 88</a>
                </div>

                {{-- Lien inscription --}}
                <p class="text-center text-sm text-gray-500 mt-8">
                    Pas encore de compte ?
                    <a href="{{ route('pre-inscription.parent') }}" class="text-mm-secondary hover:text-mm-navy font-medium transition">
                        Je cherche un répétiteur
                    </a>
                    ·
                    <a href="{{ route('pre-inscription.repetiteur') }}" class="text-mm-secondary hover:text-mm-navy font-medium transition">
                        Devenir répétiteur
                    </a>
                </p>

            </div>
        </div>

    </div>

</body>
</html>