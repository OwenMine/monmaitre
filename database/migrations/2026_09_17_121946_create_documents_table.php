<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repetiteur_id')->constrained('repetiteurs')->onDelete('cascade');
            $table->enum('type', ['cv', 'diplome', 'certificat', 'autre']);
            $table->string('nom_fichier');
            $table->string('chemin');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};