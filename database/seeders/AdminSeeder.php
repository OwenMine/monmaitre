<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email    = env('ADMIN_EMAIL', 'admin@monmaitre.ci');
        $password = env('ADMIN_DEFAULT_PASSWORD');

        // Sécurité : en production, le mot de passe doit être défini explicitement
        if (! $password) {
            if (app()->environment('production')) {
                throw new \RuntimeException(
                    'ADMIN_DEFAULT_PASSWORD doit être défini en production. '
                    . 'Ajoutez-le à votre fichier .env avant de lancer ce seeder.'
                );
            }

            // En dev uniquement : mot de passe par défaut
            $password = 'MonMaitre2026!';
        }

        $admin = User::updateOrCreate(
            ['email' => $email],
            [
                'name'     => env('ADMIN_NAME', 'Administrateur MON MAÎTRE'),
                'phone'    => env('ADMIN_PHONE', '0719167688'),
                'password' => Hash::make($password),
                'role'     => 'admin',
                'status'   => 'actif',
            ]
        );

        if (! $admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }
    }
}