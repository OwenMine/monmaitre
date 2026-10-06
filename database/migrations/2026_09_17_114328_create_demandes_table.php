<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('eleve_id')->nullable()->constrained('eleves')->onDelete('set null');
            $table->json('matieres')->nullable();
            $table->text('objectif')->nullable();
            $table->text('difficultes')->nullable();
            $table->string('preparation_examen')->nullable();
            $table->integer('seances_semaine')->nullable();
            $table->integer('duree_seance')->nullable();
            $table->json('jours_disponibles')->nullable();
            $table->string('horaires_disponibles')->nullable();
            $table->date('date_debut_souhaitee')->nullable();
            $table->string('adresse_cours')->nullable();
            $table->text('informations_complementaires')->nullable();
            $table->enum('statut', [
                'dossier_incomplet',
                'dossier_complet',
                'demande_envoyee',
                'en_analyse',
                'recherche_repetiteur',
                'repetiteur_identifie',
                'repetiteur_contacte',
                'attente_confirmation_parent',
                'placement_confirme',
                'en_cours',
                'termine',
                'annule',
                'remplacement_demande',
            ])->default('dossier_incomplet');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demandes');
    }
};