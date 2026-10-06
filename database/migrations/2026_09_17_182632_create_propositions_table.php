<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('propositions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demande_id')->constrained('demandes')->onDelete('cascade');
            $table->foreignId('repetiteur_id')->constrained('repetiteurs')->onDelete('cascade');
            $table->enum('statut', ['proposee', 'acceptee', 'refusee', 'annulee'])->default('proposee');
            $table->text('message')->nullable(); // message de MON MAÎTRE au parent
            $table->timestamp('date_proposition')->nullable();
            $table->timestamp('date_reponse')->nullable();
            $table->text('raison_refus')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('propositions');
    }
};