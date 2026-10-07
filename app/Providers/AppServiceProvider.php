<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // ============================================
        // HTTPS forcé en production
        // ============================================
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
        
        // ============================================
        // View Composer : notifications
        // ============================================
        View::composer('*', function ($view) {
            $user = auth()->user();

            $notificationsNonLues = 0;
            $dernieresNotifications = collect();

            if ($user) {
                // ⚡ C.8 : Cache 30s UNIQUEMENT pour le compteur (sérialisable)
                $notificationsNonLues = \Illuminate\Support\Facades\Cache::remember(
                    "user_{$user->id}_unread_notifs",
                    now()->addSeconds(30),
                    fn () => $user->unreadNotifications()->count()
                );

                // ⚡ Les 5 dernières notifs : requête directe (pas de cache)
                // Car DatabaseNotificationCollection n'est pas sérialisable
                $dernieresNotifications = $user->notifications()->limit(5)->get();
            }

            $view->with([
                'globalNotifsCount' => $notificationsNonLues,
                'globalNotifs'      => $dernieresNotifications,
            ]);
        });

        // ============================================
        // RATE LIMITERS PERSONNALISÉS
        // ============================================

        // Pré-inscription : 5 tentatives / heure / IP (anti-spam)
        RateLimiter::for('pre-inscription', function (Request $request) {
            return Limit::perHour(5)->by($request->ip());
        });

        // Formulaires généraux : 30 / heure / user
        RateLimiter::for('forms', function (Request $request) {
            return Limit::perHour(30)->by($request->user()?->id ?: $request->ip());
        });

        // ⚡ Actions critiques admin (valider, refuser, arbitrer) : 30 / min
       RateLimiter::for('admin-critical', function (Request $request) {
            return [
                Limit::perMinute(30)->by('user:' . $request->user()?->id),
                Limit::perMinute(60)->by('ip:' . $request->ip()),
            ];
        });

        // ⚡ Actions courantes admin (changement de statut) : 60 / min
        RateLimiter::for('admin-normal', function (Request $request) {
            return [
                Limit::perMinute(60)->by('user:' . $request->user()?->id),
                Limit::perMinute(120)->by('ip:' . $request->ip()),
            ];
        });

        // 📥 Exports CSV : 5 / min (évite les exports massifs répétés)
        RateLimiter::for('exports', function (Request $request) {
            return [
                Limit::perMinute(5)->by('user:' . $request->user()?->id),
                Limit::perMinute(10)->by('ip:' . $request->ip()),
            ];
        });

        // 🔧 Actions répétiteur sensibles : 30 / min
        RateLimiter::for('repetiteur-actions', function (Request $request) {
            return [
                Limit::perMinute(30)->by('user:' . $request->user()?->id),
                Limit::perMinute(60)->by('ip:' . $request->ip()),
            ];
        });

        // 👨‍👩‍👧 Actions parent sensibles : 30 / min
        RateLimiter::for('parent-actions', function (Request $request) {
            return [
                Limit::perMinute(30)->by('user:' . $request->user()?->id),
                Limit::perMinute(60)->by('ip:' . $request->ip()),
            ];
        });

    }
            protected $policies = [
            \App\Models\Placement::class => \App\Policies\PlacementPolicy::class,
        ];
}