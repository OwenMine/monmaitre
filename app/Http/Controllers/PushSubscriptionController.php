<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PushSubscriptionController extends Controller
{
    public function publicKey(): JsonResponse
    {
        return response()->json([
            'publicKey' => config('webpush.vapid.public_key'),
        ]);
    }

    public function subscribe(Request $request): JsonResponse
    {
        $request->validate([
            'endpoint'    => 'required|string',
            'keys.auth'   => 'required|string',
            'keys.p256dh' => 'required|string',
        ]);

        $user = $request->user();

        $exists = $user->pushSubscriptions()
            ->where('endpoint', $request->endpoint)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => true,
                'message' => 'Abonnement déjà existant.',
            ]);
        }

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