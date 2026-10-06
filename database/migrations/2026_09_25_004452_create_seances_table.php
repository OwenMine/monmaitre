<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('placement_id')->constrained('placements')->onDelete('cascade');
            $table->foreignId('repetiteur_id')->constrained('repetiteurs')->onDelete('cascade');

            // Détails
            $table->date('date');
            $table->integer('duree_minutes')->default(60);
            $table->text('contenu');
            $table->text('commentaire')->nullable();

            // Statut
            $table->enum('statut', [
                'effectuee',
                'absence_repetiteur',
                'absence_eleve',
                'annulee',
            ])->default('effectuee');

            $table->timestamps();

            $table->index(['placement_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seances');
    }
};