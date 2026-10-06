@extends('layouts.repetiteur')

@section('title', 'Mon profil')

@section('content')

    {{-- En-tête --}}
    <div class="mb-8 animate-on-scroll">
        <h2 class="text-3xl font-bold text-mm-secondary tracking-tight">Mon profil répétiteur</h2>
        <p class="text-gray-500 mt-1">
            Statut actuel :
            <span class="inline-block px-3 py-1 rounded-md text-sm font-semibold {{ $repetiteur->statutCouleur() }}">
                {{ $repetiteur->statutLabel() }}
            </span>
        </p>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm animate-on-scroll">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm animate-on-scroll">
            {{ session('error') }}
        </div>
    @endif

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

    {{-- ============ PHOTO DE PROFIL ============ --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-6 animate-on-scroll">
        <h3 class="font-bold text-mm-navy mb-5 flex items-center gap-2">
            <x-icons.user-circle class="w-5 h-5 text-mm-secondary" />
            Photo de profil
        </h3>

        <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
            {{-- Aperçu --}}
            <div class="flex-shrink-0">
                @if ($repetiteur->photo_url)
                    <img src="{{ $repetiteur->photo_url }}" alt="Photo de profil"
                         style="width: 128px; height: 128px; border-radius: 9999px; object-fit: cover; border: 4px solid rgba(20, 59, 143, 0.2); box-shadow: 0 10px 25px rgba(6, 43, 92, 0.15);">
                @else
                    <div class="flex items-center justify-center font-bold"
                         style="width: 128px; height: 128px; border-radius: 9999px; background-color: #062B5C; color: #F5A623; font-size: 3rem; border: 4px solid rgba(20, 59, 143, 0.2); box-shadow: 0 10px 25px rgba(6, 43, 92, 0.15);">
                        {{ $repetiteur->initiale }}
                    </div>
                @endif
            </div>

            {{-- Formulaire upload --}}
            <div class="flex-1 w-full" style="max-width: 500px;">
                <form method="POST" action="{{ route('repetiteur.profil.photo.upload') }}"
                      enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium mb-1">
                            Choisir une photo (JPG ou PNG, max 2 Mo)
                        </label>
                        <input type="file" name="photo" accept=".jpg,.jpeg,.png" required
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button type="submit"
                            class="inline-flex items-center gap-2 bg-mm-secondary text-white text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-mm-secondary-hover hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                            <x-icons.upload class="w-4 h-4" />
                            {{ $repetiteur->photo_url ? 'Changer la photo' : 'Ajouter une photo' }}
                        </button>
                    </div>
                </form>

                @if ($repetiteur->photo_url)
                    <form method="POST" action="{{ route('repetiteur.profil.photo.delete') }}" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center gap-2 text-red-600 hover:text-red-800 text-sm font-semibold transition"
                            onclick="return confirm('Supprimer votre photo de profil ?')">
                            <x-icons.trash class="w-4 h-4" />
                            Supprimer la photo
                        </button>
                    </form>
                @endif

                <p class="text-xs text-gray-400 mt-3">
                    Une photo professionnelle inspire confiance aux parents. Elle sera visible dans vos propositions et placements.
                </p>
            </div>
        </div>
    </div>

    {{-- ============ FORMULAIRE PRINCIPAL ============ --}}
    <form method="POST" action="{{ route('repetiteur.profil.update') }}" class="space-y-6 animate-on-scroll">
        @csrf
        @method('PUT')

        {{-- Profil académique --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h3 class="font-bold text-mm-navy mb-5 flex items-center gap-2">
                <x-icons.academic class="w-5 h-5 text-mm-secondary" />
                Profil académique
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Niveau d'études *</label>
                    <input type="text" name="niveau_etudes" value="{{ old('niveau_etudes', $repetiteur->niveau_etudes) }}" required
                        placeholder="Licence, Master, Doctorat..."
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Diplôme *</label>
                    <input type="text" name="diplome" value="{{ old('diplome', $repetiteur->diplome) }}" required
                        placeholder="Licence Mathématiques"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Établissement</label>
                    <input type="text" name="etablissement" value="{{ old('etablissement', $repetiteur->etablissement) }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Année d'obtention</label>
                    <input type="number" name="annee_obtention" value="{{ old('annee_obtention', $repetiteur->annee_obtention) }}"
                        min="1950" max="{{ date('Y') }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">
                </div>
            </div>
        </div>

        {{-- Compétences --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h3 class="font-bold text-mm-navy mb-5 flex items-center gap-2">
                <x-icons.check-badge class="w-5 h-5 text-mm-secondary" />
                Compétences
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Matières enseignées * (une par ligne)</label>

                    {{-- Boutons de sélection rapide --}}
                    <div class="mb-3 flex flex-wrap gap-2">
                        @foreach(['Mathématiques', 'Physique-Chimie', 'Français', 'Anglais', 'Philosophie', 'SVT', 'Histoire-Géo', 'Informatique'] as $matiere)
                            <button type="button"
                                onclick="addMatiereRep('{{ $matiere }}')"
                                class="text-xs font-semibold px-3 py-1.5 rounded-md bg-mm-secondary/10 text-mm-secondary hover:bg-mm-secondary hover:text-white transition-all duration-200">
                                + {{ $matiere }}
                            </button>
                        @endforeach
                    </div>

                    <textarea name="matieres_text" id="matieres_text_rep" rows="4" required
                        placeholder="Mathématiques&#10;Physique-Chimie&#10;Informatique"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">{{ old('matieres_text', implode("\n", $repetiteur->matieres ?? [])) }}</textarea>
                    <p class="text-xs text-gray-400 mt-1.5">Cliquez sur les boutons ou tapez directement vos matières.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Classes enseignées * (une par ligne)</label>
                    <textarea name="classes_text" rows="3" required
                        placeholder="6e&#10;5e&#10;3e"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">{{ old('classes_text', implode("\n", $repetiteur->classes ?? [])) }}</textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Niveau minimum *</label>
                        <input type="text" name="niveau_min" value="{{ old('niveau_min', $repetiteur->niveau_min) }}" required
                            placeholder="Primaire"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Niveau maximum *</label>
                        <input type="text" name="niveau_max" value="{{ old('niveau_max', $repetiteur->niveau_max) }}" required
                            placeholder="Terminale"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">
                    </div>
                </div>
            </div>
        </div>

        {{-- Expérience --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h3 class="font-bold text-mm-navy mb-5 flex items-center gap-2">
                <x-icons.user-circle class="w-5 h-5 text-mm-secondary" />
                Expérience
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Années d'expérience *</label>
                    <input type="number" name="experience_annees" value="{{ old('experience_annees', $repetiteur->experience_annees ?? 0) }}" required min="0" max="50"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Expérience en répétition</label>
                    <textarea name="experience_repetition" rows="3"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">{{ old('experience_repetition', $repetiteur->experience_repetition) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Expériences particulières</label>
                    <textarea name="experiences_particulieres" rows="3"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">{{ old('experiences_particulieres', $repetiteur->experiences_particulieres) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Références</label>
                    <textarea name="references" rows="2"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">{{ old('references', $repetiteur->references) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Disponibilités --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h3 class="font-bold text-mm-navy mb-5 flex items-center gap-2">
                <x-icons.clock class="w-5 h-5 text-mm-secondary" />
                Disponibilités
            </h3>
            <div>
                <label class="block text-sm font-medium mb-2">Jours disponibles *</label>
                <div class="flex flex-wrap gap-4">
                    @foreach (['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche'] as $jour)
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="jours_disponibles[]" value="{{ $jour }}"
                                class="rounded border-gray-300 text-mm-secondary focus:ring-mm-secondary mr-2"
                                {{ in_array($jour, old('jours_disponibles', $repetiteur->jours_disponibles ?? [])) ? 'checked' : '' }}>
                            <span class="text-sm">{{ $jour }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Horaires disponibles *</label>
                    <input type="text" name="horaires_disponibles" value="{{ old('horaires_disponibles', $repetiteur->horaires_disponibles) }}" required
                        placeholder="16h à 20h"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Nombre maximum d'élèves *</label>
                    <input type="number" name="max_eleves" value="{{ old('max_eleves', $repetiteur->max_eleves ?? 3) }}" required min="1" max="20"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">
                </div>
            </div>
        </div>

        {{-- Zone --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h3 class="font-bold text-mm-navy mb-5 flex items-center gap-2">
                <x-icons.target class="w-5 h-5 text-mm-secondary" />
                Zone de déplacement
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Communes * (une par ligne)</label>
                    <textarea name="communes_text" rows="3" required
                        placeholder="Cocody&#10;Yopougon"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">{{ old('communes_text', implode("\n", $repetiteur->communes ?? [])) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Quartiers (un par ligne)</label>
                    <textarea name="quartiers_text" rows="3"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">{{ old('quartiers_text', implode("\n", $repetiteur->quartiers ?? [])) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Rayon de déplacement (km) *</label>
                    <input type="number" name="rayon_deplacement" value="{{ old('rayon_deplacement', $repetiteur->rayon_deplacement ?? 10) }}" required min="1" max="100"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">
                </div>
            </div>
        </div>

        <button type="submit"
            class="w-full bg-mm-secondary text-white py-3.5 rounded-lg font-semibold hover:bg-mm-secondary-hover hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
            ENREGISTRER MON PROFIL
        </button>
    </form>

    {{-- ============ DOCUMENTS ============ --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6 mt-6 animate-on-scroll">
        <h3 class="font-bold text-mm-navy mb-5 flex items-center gap-2">
            <x-icons.document class="w-5 h-5 text-mm-secondary" />
            Mes documents
        </h3>

        @if ($repetiteur->documents->count() > 0)
            <ul class="space-y-2 mb-4">
                @foreach ($repetiteur->documents as $doc)
                    <li class="flex justify-between items-center p-3 rounded-lg border border-gray-100 hover:border-mm-secondary/30 hover:bg-mm-secondary/5 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-mm-secondary/10 flex items-center justify-center">
                                <x-icons.document class="w-4 h-4 text-mm-secondary" />
                            </div>
                            <div>
                                <span class="text-sm font-semibold text-mm-navy">{{ $doc->typeLabel() }}</span>
                                <p class="text-xs text-gray-500">{{ $doc->nom_fichier }}</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('repetiteur.documents.destroy', $doc) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-semibold transition"
                                onclick="return confirm('Supprimer ce document ?')">
                                Supprimer
                            </button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-500 mb-4 text-sm">Aucun document pour le moment.</p>
        @endif

        <form method="POST" action="{{ route('repetiteur.documents.store') }}" enctype="multipart/form-data" class="space-y-3">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div>
                    <label class="block text-sm font-medium mb-1">Type de document *</label>
                    <select name="type" required class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">
                        <option value="cv">CV</option>
                        <option value="diplome">Diplôme</option>
                        <option value="certificat">Certificat</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">Fichier * (PDF, JPG, PNG – max 5 Mo)</label>
                    <input type="file" name="fichier" required accept=".pdf,.jpg,.jpeg,.png"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-mm-secondary focus:ring-2 focus:ring-mm-secondary/20 transition">
                </div>
            </div>
            <button type="submit" class="bg-mm-navy text-white text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-mm-navy-hover hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                Ajouter le document
            </button>
        </form>
    </div>

    {{-- Bouton envoyer candidature --}}
    @if ($repetiteur->statut === 'dossier_incomplet')
        <form method="POST" action="{{ route('repetiteur.profil.envoyer') }}" class="mt-6 animate-on-scroll">
            @csrf
            <button type="submit"
                class="w-full bg-green-600 text-white py-3.5 rounded-lg font-semibold hover:bg-green-700 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                ENVOYER MA CANDIDATURE À MON MAÎTRE
            </button>
        </form>
    @endif

    {{-- Script : ajout rapide de matières --}}
    <script>
        function addMatiereRep(matiere) {
            const textarea = document.getElementById('matieres_text_rep');
            const current = textarea.value.trim();
            const matieres = current ? current.split('\n').map(m => m.trim()) : [];

            if (!matieres.includes(matiere)) {
                textarea.value = current ? current + '\n' + matiere : matiere;
            }
        }
    </script>

@endsection