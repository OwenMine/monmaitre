<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Idempotent : crée le rôle s'il n'existe pas, sinon ne fait rien
        Role::firstOrCreate(['name' => 'parent', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'repetiteur', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    }
}