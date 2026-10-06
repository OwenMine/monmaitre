<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Email devient nullable (les parents peuvent ne pas en avoir)
            $table->string('email')->nullable()->change();

            // Téléphone devient unique (utilisé comme identifiant de connexion pour les parents)
            $table->string('phone')->nullable()->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
            $table->dropUnique(['phone']);
            $table->string('phone')->nullable()->change();
        });
    }
};