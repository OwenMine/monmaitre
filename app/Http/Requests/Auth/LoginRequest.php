<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use App\Support\PhoneHelper;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles : on accepte un champ "identifier" (email OU téléphone).
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'identifier' => ['required', 'string'],
            'password'   => ['required', 'string'],
        ];
    }

    /**
     * Messages personnalisés.
     */
    public function messages(): array
    {
        return [
            'identifier.required' => 'Veuillez renseigner votre email ou votre numéro de téléphone.',
            'password.required'   => 'Veuillez renseigner votre mot de passe.',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $identifier = trim($this->string('identifier')->toString());
        $password   = $this->string('password')->toString();

        // ⚡ B.19 : Utiliser PhoneHelper au lieu de User::isEmail / User::normalizePhone
        $credentials = PhoneHelper::isEmail($identifier)
            ? ['email' => $identifier, 'password' => $password]
            : ['phone' => PhoneHelper::normalize($identifier), 'password' => $password];

        if (! Auth::attempt($credentials, $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'identifier' => 'Ces identifiants ne correspondent à aucun compte.',
            ]);
        }

        // ⚡ Sécurité : vérifier le statut du compte
        $user = Auth::user();

        if ($user->status !== 'actif') {
            Auth::logout();
            RateLimiter::hit($this->throttleKey());

            $message = match ($user->status) {
                'suspendu' => 'Votre compte a été suspendu. Contactez MON MAÎTRE au 07 19 16 76 88.',
                'inactif'  => 'Votre compte est inactif. Contactez MON MAÎTRE au 07 19 16 76 88.',
                default    => 'Votre compte n\'est pas actif. Contactez MON MAÎTRE.',
            };

            throw ValidationException::withMessages([
                'identifier' => $message,
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'identifier' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(
            Str::lower($this->string('identifier')).'|'.$this->ip()
        );
    }
}