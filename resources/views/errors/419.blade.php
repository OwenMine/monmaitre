<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session expirée – MON MAÎTRE</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-icon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-mm-light flex items-center justify-center p-6">

    <div class="max-w-lg w-full text-center">

        <a href="{{ url('/') }}" class="inline-block mb-8">
            <img src="{{ asset('images/logo-icon.png') }}" alt="MON MAÎTRE" class="h-20 w-auto mx-auto">
        </a>

        <div class="text-8xl md:text-9xl font-bold text-mm-navy leading-none mb-4">
            4<span class="text-orange-500">1</span>9
        </div>

        <h1 class="text-2xl md:text-3xl font-bold text-mm-navy mb-3">
            Session expirée
        </h1>

        <p class="text-gray-500 mb-8 leading-relaxed">
            Votre session a expiré pour des raisons de sécurité.
            Veuillez vous reconnecter pour continuer.
        </p>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('login') }}"
               class="inline-flex items-center justify-center gap-2 bg-mm-navy text-white font-semibold px-6 py-3 rounded-lg hover:bg-mm-navy-hover hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                <x-icons.lock-closed class="w-4 h-4" />
                Se reconnecter
            </a>
            <a href="{{ url('/') }}"
               class="inline-flex items-center justify-center gap-2 border-2 border-mm-navy text-mm-navy font-semibold px-6 py-3 rounded-lg hover:bg-mm-navy hover:text-white hover:-translate-y-0.5 transition-all duration-300">
                Accueil
            </a>
        </div>
    </div>

</body>
</html>