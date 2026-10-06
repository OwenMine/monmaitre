<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended($this->redirectTo());
        }

        return view('auth.verify-email');
    }

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