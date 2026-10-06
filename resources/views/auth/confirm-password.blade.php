@extends('layouts.guest')

@section('title', 'Confirmation – MON MAÎTRE')

@section('content')
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-mm-navy mb-3">
            <x-icons.check-badge class="w-7 h-7 text-mm-accent" />
        </div>
        <h1 class="text-2xl font-bold text-mm-navy mb-1">Confirmez votre mot de passe</h1>
        <p class="text-sm text-gray-500">
            Cette zone est sécurisée. Confirmez votre mot de passe pour continuer.
        </p>
    </div>

    {{-- Erreurs --}}
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-5 text-sm">
            @foreach ($errors->all() as $error)
                <div>• {{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

        <div>
            <label for="password" class="block text-sm font-medium text-mm-navy mb-2">Mot de passe</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <x-icons.lock-closed class="w-5 h-5" />
                </span>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required autocomplete="current-password"
                    placeholder="Votre mot de passe"
                    class="w-full border border-gray-200 rounded-lg pl-10 pr-3 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mm-secondary focus:border-transparent transition">
            </div>
        </div>

        <button type="submit"
            class="w-full bg-mm-navy text-white py-3 rounded-lg font-semibold text-sm hover:bg-mm-navy-hover transition shadow-lg shadow-mm-navy/20 inline-flex items-center justify-center gap-2">
            Confirmer
            <x-icons.arrow-right class="w-4 h-4" />
        </button>
    </form>
@endsection