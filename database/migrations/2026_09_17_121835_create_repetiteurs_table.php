<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('repetiteurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Profil académique
            $table->string('niveau_etudes')->nullable();
            $table->string('diplome')->nullable();
            $table->string('etablissement')->nullable();
            $table->year('annee_obtention')->nullable();

            // Compétences
            $table->json('matieres')->nullable();
            $table->json('classes')->nullable();
            $table->string('niveau_min')->nullable();
            $table->string('niveau_max')->nullable();

            // Expérience
            $table->integer('experience_annees')->default(0);
            $table->text('experience_repetition')->nullable();
            $table->text('experiences_particulieres')->nullable();
            $table->text('references')->nullable();

            // Disponibilités
            $table->json('jours_disponibles')->nullable();
            $table->string('horaires_disponibles')->nullable();
            $table->integer('max_eleves')->default(3);

            // Zone de déplacement
            $table->json('communes')->nullable();
            $table->json('quartiers')->nullable();
            $table->integer('rayon_deplacement')->default(10);

            // Statut
            $table->enum('statut', [
                'pre_inscrit',
                'dossier_incomplet',
                'candidature_envoyee',
                'a_examiner',
                'entretien_a_effectuer',
                'documents_a_verifier',
                'profil_valide',
                'profil_en_attente',
                'profil_refuse',
                'profil_suspendu',
            ])->default('dossier_incomplet');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repetiteurs');
    }
};