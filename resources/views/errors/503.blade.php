<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance en cours – MON MAÎTRE</title>
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
            5<span class="text-mm-accent">0</span>3
        </div>

        <h1 class="text-2xl md:text-3xl font-bold text-mm-navy mb-3">
            Maintenance en cours
        </h1>

        <p class="text-gray-500 mb-8 leading-relaxed">
            MON MAÎTRE est en cours de maintenance pour vous offrir une meilleure expérience.
            Nous serons de retour très bientôt !
        </p>

        <p class="text-xs text-gray-400 mt-10">
            Urgence ? Appelez-nous :
            <a href="tel:+2250719167688" class="text-mm-secondary font-semibold hover:text-mm-navy">07 19 16 76 88</a>
        </p>
    </div>

</body>
</html>