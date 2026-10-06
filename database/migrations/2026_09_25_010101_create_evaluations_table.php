<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('placement_id')->constrained('placements')->onDelete('cascade');
            $table->foreignId('parent_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('repetiteur_id')->constrained('repetiteurs')->onDelete('cascade');

            $table->unsignedTinyInteger('note'); // Note sur 10
            $table->text('commentaire')->nullable();

            $table->timestamps();

            // Un seul avis par placement
            $table->unique('placement_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};