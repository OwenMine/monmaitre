<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\PhoneHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PreInscriptionController extends Controller
{
    // ================================================================
    // PARENT
    // ================================================================

    public function formParent()
    {
        return view('pre-inscription.parent');
    }

    public function storeParent(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'phone'     => 'required|string|max:20|unique:users,phone',
            'whatsapp'  => 'nullable|string|max:20',
            'commune'   => 'required|string|max:100',
            'quartier'  => 'nullable|string|max:100',
            'password'  => 'required|string|min:8|confirmed',
        ]);

        // ⚡ B.19 : Utiliser PhoneHelper
        $phone = PhoneHelper::normalize($data['phone']);

        // Vérifier qu'après normalisation, le numéro n'existe pas déjà
        if (User::where('phone', $phone)->exists()) {
            return back()
                ->withInput()
                ->withErrors(['phone' => 'Ce numéro de téléphone est déjà utilisé.']);
        }

        $user = User::create([
            'name'     => $data['name'],
            'email'    => null, // Parent : email optionnel (null par défaut)
            'phone'    => $phone,
            'whatsapp' => isset($data['whatsapp']) ? PhoneHelper::normalize($data['whatsapp']) : null,
            'commune'  => $data['commune'],
            'quartier' => $data['quartier'] ?? null,
            'password' => Hash::make($data['password']),
            'role'     => 'parent',
            'status'   => 'actif',
        ]);

        $user->assignRole('parent');

        Auth::login($user);

        return redirect()->route('parent.dashboard')
            ->with('success', 'Bienvenue sur MON MAÎTRE ! Complétez votre demande pour continuer.');
    }

    // ================================================================
    // RÉPÉTITEUR
    // ================================================================

    public function formRepetiteur()
    {
        return view('pre-inscription.repetiteur');
    }

    public function storeRepetiteur(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'phone'       => 'required|string|max:20|unique:users,phone',
            'whatsapp'    => 'nullable|string|max:20',
            'commune'     => 'required|string|max:100',
            'quartier'    => 'nullable|string|max:100',
            'matieres'    => 'required|string|max:255',
            'niveaux'     => 'required|string|max:255',
            'experience'  => 'required|integer|min:0|max:50',
            'password'    => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => PhoneHelper::normalize($data['phone']),  // ⚡ B.19
            'whatsapp' => isset($data['whatsapp']) ? PhoneHelper::normalize($data['whatsapp']) : null,
            'commune'  => $data['commune'],
            'quartier' => $data['quartier'] ?? null,
            'password' => Hash::make($data['password']),
            'role'     => 'repetiteur',
            'status'   => 'actif',
        ]);

        $user->assignRole('repetiteur');

        Auth::login($user);

        return redirect()->route('repetiteur.dashboard')
            ->with('success', 'Bienvenue sur MON MAÎTRE ! Complétez votre profil pour continuer.');
    }
}