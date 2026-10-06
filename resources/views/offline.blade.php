<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hors ligne – MON MAÎTRE</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-icon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-mm-light flex items-center justify-center p-6">

    <div class="max-w-lg w-full text-center">

        <a href="{{ url('/') }}" class="inline-block mb-8">
            <img src="{{ asset('images/logo-icon.png') }}" alt="MON MAÎTRE" class="h-20 w-auto mx-auto">
        </a>

        <div class="text-8xl md:text-9xl mb-4">
            📡
        </div>

        <h1 class="text-2xl md:text-3xl font-bold text-mm-navy mb-3">
            Vous êtes hors ligne
        </h1>

        <p class="text-gray-500 mb-8 leading-relaxed">
            Il semble que vous n'ayez pas de connexion internet.
            Reconnectez-vous pour continuer à utiliser MON MAÎTRE.
        </p>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <button onclick="window.location.reload()"
                class="inline-flex items-center justify-center gap-2 bg-mm-navy text-white font-semibold px-6 py-3 rounded-lg hover:bg-mm-navy-hover hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                Réessayer
            </button>
            <a href="{{ url('/') }}"
               class="inline-flex items-center justify-center gap-2 border-2 border-mm-navy text-mm-navy font-semibold px-6 py-3 rounded-lg hover:bg-mm-navy hover:text-white hover:-translate-y-0.5 transition-all duration-300">
                Retour à l'accueil
            </a>
        </div>

        <p class="text-xs text-gray-400 mt-10">
            Besoin d'aide ? Contactez MON MAÎTRE :
            <a href="tel:+2250719167688" class="text-mm-secondary font-semibold hover:text-mm-navy">07 19 16 76 88</a>
        </p>
    </div>

</body>
</html>