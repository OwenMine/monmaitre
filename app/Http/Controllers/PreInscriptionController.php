<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\PhoneHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PreInscriptionController extends Controller
{
    public function formParent(): View
    {
        return view('pre-inscription.parent');
    }

    public function storeParent(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'phone'    => 'required|string|max:20|unique:users,phone',
            'whatsapp' => 'nullable|string|max:20',
            'commune'  => 'required|string|max:100',
            'quartier' => 'nullable|string|max:100',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $phone = PhoneHelper::normalize($data['phone']);

        if (User::where('phone', $phone)->exists()) {
            return back()
                ->withInput()
                ->withErrors(['phone' => 'Ce numéro de téléphone est déjà utilisé.']);
        }

        $user = User::create([
            'name'     => $data['name'],
            'email'    => null,
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

    public function formRepetiteur(): View
    {
        return view('pre-inscription.repetiteur');
    }

    public function storeRepetiteur(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'phone'      => 'required|string|max:20|unique:users,phone',
            'whatsapp'   => 'nullable|string|max:20',
            'commune'    => 'required|string|max:100',
            'quartier'   => 'nullable|string|max:100',
            'matieres'   => 'required|string|max:255',
            'niveaux'    => 'required|string|max:255',
            'experience' => 'required|integer|min:0|max:50',
            'password'   => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => PhoneHelper::normalize($data['phone']),
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