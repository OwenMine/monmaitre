<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ============================================
        // DEMANDES : index sur parent_id et statut
        // ============================================
        Schema::table('demandes', function (Blueprint $table) {
            $table->index('parent_id', 'demandes_parent_id_index');
            $table->index('statut', 'demandes_statut_index');
            $table->index(['parent_id', 'statut'], 'demandes_parent_statut_index');
        });

        // ============================================
        // PLACEMENTS : index sur parent_id, repetiteur_id, statut
        // ============================================
        Schema::table('placements', function (Blueprint $table) {
            $table->index('parent_id', 'placements_parent_id_index');
            $table->index('repetiteur_id', 'placements_repetiteur_id_index');
            $table->index('statut', 'placements_statut_index');
        });

        // ============================================
        // PROPOSITIONS : index sur demande_id et statut
        // ============================================
        Schema::table('propositions', function (Blueprint $table) {
            $table->index('demande_id', 'propositions_demande_id_index');
            $table->index('statut', 'propositions_statut_index');
        });

        // ============================================
        // EVALUATIONS : index sur repetiteur_id
        // ============================================
        Schema::table('evaluations', function (Blueprint $table) {
            $table->index('repetiteur_id', 'evaluations_repetiteur_id_index');
        });

        // ============================================
        // SEANCES : index déjà en place (placement_id + date)
        // Vérification rapide
        // ============================================
        Schema::table('seances', function (Blueprint $table) {
            // Ces index existent déjà via la migration initiale
            // Uniquement si tu veux renforcer :
            // $table->index('repetiteur_id', 'seances_repetiteur_id_index');
            // $table->index('statut', 'seances_statut_index');
        });

        // ============================================
        // USERS : index sur role, status, phone
        // ============================================
        Schema::table('users', function (Blueprint $table) {
            $table->index('role', 'users_role_index');
            $table->index('status', 'users_status_index');
        });
    }

    public function down(): void
    {
        Schema::table('demandes', function (Blueprint $table) {
            $table->dropIndex('demandes_parent_id_index');
            $table->dropIndex('demandes_statut_index');
            $table->dropIndex('demandes_parent_statut_index');
        });

        Schema::table('placements', function (Blueprint $table) {
            $table->dropIndex('placements_parent_id_index');
            $table->dropIndex('placements_repetiteur_id_index');
            $table->dropIndex('placements_statut_index');
        });

        Schema::table('propositions', function (Blueprint $table) {
            $table->dropIndex('propositions_demande_id_index');
            $table->dropIndex('propositions_statut_index');
        });

        Schema::table('evaluations', function (Blueprint $table) {
            $table->dropIndex('evaluations_repetiteur_id_index');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_role_index');
            $table->dropIndex('users_status_index');
        });
    }
};