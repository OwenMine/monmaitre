<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->status !== 'actif') {
            $user = auth()->user();
            auth()->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $message = match ($user->status) {
                'suspendu' => 'Votre compte a été suspendu. Contactez MON MAÎTRE au 07 19 16 76 88.',
                'inactif'  => 'Votre compte est inactif.',
                default    => 'Votre compte n\'est pas actif.',
            };

            return redirect()->route('login')->with('error', $message);
        }

        return $next($request);
    }
}