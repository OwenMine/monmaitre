@extends('layouts.parent')

@section('title', 'Nouvelle demande')

@section('content')

    {{-- Fil d'Ariane --}}
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6 animate-on-scroll">
        <a href="{{ route('parent.demandes.index') }}" class="hover:text-mm-navy transition">Mes demandes</a>
        <span>/</span>
        <span class="text-mm-navy font-medium">Nouvelle demande</span>
    </div>

    {{-- En-tête --}}
    <div class="mb-8 animate-on-scroll">
        <h2 class="text-3xl font-bold text-mm-navy tracking-tight">Nouvelle demande de répétiteur</h2>
        <p class="text-gray-500 mt-1">Remplissez ce formulaire pour que MON MAÎTRE recherche le meilleur répétiteur.</p>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm animate-on-scroll">
            <p class="font-semibold mb-1">Veuillez corriger les erreurs suivantes :</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('parent.demandes.store') }}" class="space-y-6 animate-on-scroll">
        @csrf

        {{-- Élève --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h3 class="font-bold text-mm-navy mb-5 flex items-center gap-2">
                <x-icons.academic class="w-5 h-5 text-mm-secondary" />
                Informations de l'élève
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Nom *</label>
                    <input type="text" name="eleve_nom" value="{{ old('eleve_nom') }}" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Prénom *</label>
                    <input type="text" name="eleve_prenom" value="{{ old('eleve_prenom') }}" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Âge</label>
                    <input type="number" name="eleve_age" value="{{ old('eleve_age') }}" min="3" max="30"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Classe *</label>
                    <input type="text" name="eleve_classe" value="{{ old('eleve_classe') }}" required
                        placeholder="6e, 3e, Terminale..."
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">Établissement</label>
                    <input type="text" name="eleve_etablissement" value="{{ old('eleve_etablissement') }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">
                </div>
            </div>
        </div>

        {{-- Besoin pédagogique --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h3 class="font-bold text-mm-navy mb-5 flex items-center gap-2">
                <x-icons.document class="w-5 h-5 text-mm-secondary" />
                Besoin pédagogique
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Matières souhaitées * (une par ligne)</label>

                    {{-- Boutons de sélection rapide --}}
                    <div class="mb-3 flex flex-wrap gap-2">
                        @foreach(['Mathématiques', 'Physique-Chimie', 'Français', 'Anglais', 'Philosophie', 'SVT', 'Histoire-Géo', 'Informatique'] as $matiere)
                            <button type="button"
                                onclick="addMatiere('{{ $matiere }}')"
                                class="text-xs font-semibold px-3 py-1.5 rounded-md bg-mm-secondary/10 text-mm-secondary hover:bg-mm-secondary hover:text-white transition-all duration-200">
                                + {{ $matiere }}
                            </button>
                        @endforeach
                    </div>

                    <textarea name="matieres_text" id="matieres_text" rows="4" required
                        placeholder="Mathématiques&#10;Physique-Chimie"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">{{ old('matieres_text') }}</textarea>
                    <p class="text-xs text-gray-400 mt-1.5">Cliquez sur les boutons ou tapez directement vos matières.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Objectif *</label>
                    <textarea name="objectif" rows="2" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">{{ old('objectif') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Difficultés particulières</label>
                    <textarea name="difficultes" rows="2"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">{{ old('difficultes') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Préparation à un examen</label>
                    <input type="text" name="preparation_examen" value="{{ old('preparation_examen') }}"
                        placeholder="BEPC, BAC, aucun..."
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">
                </div>
            </div>
        </div>

        {{-- Organisation --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h3 class="font-bold text-mm-navy mb-5 flex items-center gap-2">
                <x-icons.clock class="w-5 h-5 text-mm-secondary" />
                Organisation des séances
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Séances par semaine *</label>
                    <input type="number" name="seances_semaine" value="{{ old('seances_semaine', 2) }}" required min="1" max="14"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Durée d'une séance (minutes) *</label>
                    <input type="number" name="duree_seance" value="{{ old('duree_seance', 60) }}" required min="30" max="240"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium mb-2">Jours disponibles *</label>
                <div class="flex flex-wrap gap-4">
                    @foreach (['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche'] as $jour)
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="jours_disponibles[]" value="{{ $jour }}"
                                class="rounded border-gray-300 text-mm-secondary focus:ring-mm-secondary mr-2"
                                {{ in_array($jour, old('jours_disponibles', [])) ? 'checked' : '' }}>
                            <span class="text-sm">{{ $jour }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium mb-1">Horaires disponibles *</label>
                <input type="text" name="horaires_disponibles" value="{{ old('horaires_disponibles') }}" required
                    placeholder="Ex : 16h à 19h"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Date de début souhaitée *</label>
                    <input type="date" name="date_debut_souhaitee" value="{{ old('date_debut_souhaitee') }}" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Adresse des cours *</label>
                    <input type="text" name="adresse_cours" value="{{ old('adresse_cours') }}" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">
                </div>
            </div>
        </div>

        {{-- Informations complémentaires --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h3 class="font-bold text-mm-navy mb-5">Informations complémentaires</h3>
            <textarea name="informations_complementaires" rows="4"
                placeholder="Décrivez-nous votre besoin particulier..."
                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">{{ old('informations_complementaires') }}</textarea>
        </div>

        <button type="submit"
            class="w-full bg-mm-navy text-white py-3.5 rounded-lg font-semibold hover:bg-mm-navy-hover hover:shadow-lg hover:shadow-mm-navy/30 hover:-translate-y-0.5 transition-all duration-300">
            ENVOYER MA DEMANDE
        </button>
    </form>

    {{-- Script : ajout rapide de matières --}}
    <script>
        function addMatiere(matiere) {
            const textarea = document.getElementById('matieres_text');
            const current = textarea.value.trim();
            const matieres = current ? current.split('\n').map(m => m.trim()) : [];

            if (!matieres.includes(matiere)) {
                textarea.value = current ? current + '\n' + matiere : matiere;
            }
        }
    </script>

@endsection