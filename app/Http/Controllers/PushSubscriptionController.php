<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PushSubscriptionController extends Controller
{
    /**
     * Retourne la clé publique VAPID (pour que le navigateur puisse s'abonner).
     */
    public function publicKey(): JsonResponse
    {
        return response()->json([
            'publicKey' => config('webpush.vapid.public_key'),
        ]);
    }

    /**
     * Enregistre un nouvel abonnement push.
     */
    public function subscribe(Request $request): JsonResponse
    {
        $request->validate([
            'endpoint'    => 'required|string',
            'keys.auth'   => 'required|string',
            'keys.p256dh' => 'required|string',
        ]);

        $user = $request->user();

        // Vérifier si l'abonnement existe déjà
        $exists = $user->pushSubscriptions()
            ->where('endpoint', $request->endpoint)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => true,
                'message' => 'Abonnement déjà existant.',
            ]);
        }

        // Créer l'abonnement
        $user->updatePushSubscription(
            $request->endpoint,
            $request->keys['p256dh'],
            $request->keys['auth'],
            $request->input('contentEncoding', 'aesgcm')
        );

        return response()->json([
            'success' => true,
            'message' => 'Notifications push activées avec succès.',
        ]);
    }

    /**
     * Supprime un abonnement push.
     */
    public function unsubscribe(Request $request): JsonResponse
    {
        $request->validate([
            'endpoint' => 'required|string',
        ]);

        $user = $request->user();

        $deleted = $user->pushSubscriptions()
            ->where('endpoint', $request->endpoint)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => $deleted ? 'Notifications désactivées.' : 'Abonnement introuvable.',
        ]);
    }
}