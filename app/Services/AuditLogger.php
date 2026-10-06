<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class AuditLogger
{
    /**
     * Enregistre une action d'audit.
     */
    public static function log(string $action, string $description, array $data = []): void
    {
        $user = Auth::user();

        $context = [
            'user_id'    => $user?->id,
            'user_name'  => $user?->name,
            'user_email' => $user?->email,
            'user_role'  => $user?->role,
            'ip'         => Request::ip(),
            'user_agent' => Request::userAgent(),
            'url'        => Request::fullUrl(),
            'method'     => Request::method(),
            'action'     => $action,
            'description'=> $description,
            'data'       => $data,
            'timestamp'  => now()->toDateTimeString(),
        ];

        Log::channel('audit')->info("[$action] $description", $context);
    }

    /**
     * Log une action admin.
     */
    public static function admin(string $action, string $description, array $data = []): void
    {
        self::log("ADMIN:$action", $description, $data);
    }

    /**
     * Log une action répétiteur.
     */
    public static function repetiteur(string $action, string $description, array $data = []): void
    {
        self::log("REPETITEUR:$action", $description, $data);
    }

    /**
     * Log une action parent.
     */
    public static function parent(string $action, string $description, array $data = []): void
    {
        self::log("PARENT:$action", $description, $data);
    }
}