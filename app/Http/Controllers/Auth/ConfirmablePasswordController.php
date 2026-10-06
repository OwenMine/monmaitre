<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ConfirmablePasswordController extends Controller
{
    /**
     * Show the confirm password view.
     */
    public function show(): View
    {
        return view('auth.confirm-password');
    }

    /**
     * Confirm the user's password.
     */
    public function store(Request $request): RedirectResponse
    {
        if (! Auth::guard('web')->validate([
            'email' => $request->user()->email ?? $request->user()->phone,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended($this->redirectTo());
    }

    /**
     * Redirection après confirmation selon le rôle.
     */
    protected function redirectTo(): string
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return route('admin.dashboard');
        }

        if ($user->isRepetiteur()) {
            return route('repetiteur.dashboard');
        }

        return route('parent.dashboard');
    }
}