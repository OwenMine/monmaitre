@extends('layouts.guest')

@section('title', 'Vérification email – MON MAÎTRE')

@section('content')
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-mm-navy mb-3">
            <x-icons.envelope class="w-7 h-7 text-mm-accent" />
        </div>
        <h1 class="text-2xl font-bold text-mm-navy mb-1">Vérifiez votre email</h1>
        <p class="text-sm text-gray-500">
            Un lien de vérification vous a été envoyé. Cliquez dessus pour activer votre compte.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-5 text-sm">
            Un nouveau lien de vérification vient d'être envoyé à votre adresse email.
        </div>
    @endif

    <div class="space-y-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit"
                class="w-full bg-mm-navy text-white py-3 rounded-lg font-semibold text-sm hover:bg-mm-navy-hover transition">
                Renvoyer le lien de vérification
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full border border-gray-200 text-gray-700 py-3 rounded-lg font-semibold text-sm hover:bg-gray-50 transition">
                Se déconnecter
            </button>
        </form>
    </div>
@endsection