<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seances', function (Blueprint $table) {
            // Nouveau statut : on modifie l'enum
            // ⚠️ MySQL : on doit dropper et recréer l'enum
            $table->enum('statut', [
                'en_attente',          // NOUVEAU : par défaut à la création
                'effectuee',           // Validée par le parent
                'contested',           // NOUVEAU : contestée par le parent
                'absence_repetiteur',
                'absence_eleve',
                'annulee',
            ])->default('en_attente')->change();

            // Colonnes de validation
            $table->timestamp('validee_at')->nullable()->after('statut');
            $table->foreignId('validee_par')->nullable()->after('validee_at')
                  ->constrained('users')->onDelete('set null');
            $table->text('raison_contestation')->nullable()->after('validee_par');
        });
    }

    public function down(): void
    {
        Schema::table('seances', function (Blueprint $table) {
            $table->dropForeign(['validee_par']);
            $table->dropColumn(['validee_at', 'validee_par', 'raison_contestation']);
        });
    }
};