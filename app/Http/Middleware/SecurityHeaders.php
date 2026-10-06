<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // ============================================
        // HEADERS STANDARDS
        // ============================================
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()');

        // Masquer la version PHP
        $response->headers->remove('X-Powered-By');

        // ============================================
        // CONTENT SECURITY POLICY
        // ============================================
        if (app()->environment('local')) {
            // Développement : permissif pour Vite HMR
            $csp = "default-src 'self' 'unsafe-inline' 'unsafe-eval' data: blob:; " .
                   "script-src 'self' 'unsafe-inline' 'unsafe-eval' https:; " .
                   "style-src 'self' 'unsafe-inline' https:; " .
                   "img-src 'self' data: blob: https:; " .
                   "font-src 'self' data: https:; " .
                   "connect-src 'self' ws: wss: https:; " .
                   "frame-src 'self' https:;";
        } else {
            // Production : strict
            $csp = "default-src 'self'; " .
                   "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; " .
                   "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
                   "img-src 'self' data: blob: https://images.unsplash.com; " .
                   "font-src 'self' data: https://fonts.gstatic.com; " .
                   "connect-src 'self' https://fcm.googleapis.com https://*.googleapis.com; " .
                   "frame-src 'self' https:; " .
                   "object-src 'none'; " .
                   "base-uri 'self'; " .
                   "form-action 'self'; " .
                   "frame-ancestors 'self';";
        }

        $response->headers->set('Content-Security-Policy', $csp);

        // HSTS en production
        if (app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        return $response;
    }
}