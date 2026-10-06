<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class RegisteredUserController extends Controller
{
    /**
     * Désactivé : l'inscription se fait uniquement via /pre-inscription/parent
     * ou /pre-inscription/repetiteur (avec assignation de rôle Spatie).
     */
    public function create(): RedirectResponse
    {
        return redirect()
            ->route('pre-inscription.parent')
            ->with('info', 'Pour créer un compte, choisissez votre profil.');
    }

    /**
     * Désactivé : voir create().
     */
    public function store(): RedirectResponse
    {
        return redirect()
            ->route('pre-inscription.parent');
    }
}