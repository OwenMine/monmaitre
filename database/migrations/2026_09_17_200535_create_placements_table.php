<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('placements', function (Blueprint $table) {
            $table->id();

            // Référence unique
            $table->string('reference')->unique();

            // Relations
            $table->foreignId('demande_id')->constrained('demandes')->onDelete('cascade');
            $table->foreignId('proposition_id')->nullable()->constrained('propositions')->onDelete('set null');
            $table->foreignId('parent_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('repetiteur_id')->constrained('repetiteurs')->onDelete('cascade');
            $table->foreignId('eleve_id')->constrained('eleves')->onDelete('cascade');

            // Détails du placement
            $table->string('matiere');
            $table->string('adresse');
            $table->date('date_debut');
            $table->string('frequence')->nullable();
            $table->string('horaires')->nullable();
            $table->decimal('tarif_convenu', 10, 2)->nullable();

            // Statut
            $table->enum('statut', [
                'en_attente',
                'confirme',
                'en_cours',
                'evaluation',
                'termine',
                'remplacement',
                'annule',
            ])->default('confirme');

            // Suivi
            $table->text('commentaires')->nullable();
            $table->timestamp('date_fin')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('placements');
    }
};