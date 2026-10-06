<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('whatsapp')->nullable()->after('phone');
            $table->string('address')->nullable()->after('whatsapp');
            $table->string('commune')->nullable()->after('address');
            $table->string('quartier')->nullable()->after('commune');
            $table->enum('role', ['parent', 'repetiteur', 'admin'])->default('parent')->after('quartier');
            $table->enum('status', ['actif', 'inactif', 'suspendu'])->default('actif')->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone', 'whatsapp', 'address',
                'commune', 'quartier', 'role', 'status',
            ]);
        });
    }
};