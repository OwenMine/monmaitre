<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Page dédiée : liste complète des notifications (paginée + filtrée).
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $filtre = $request->get('filtre', 'toutes'); // toutes | non_lues | lues

        $query = $user->notifications();

        // Appliquer le filtre
        if ($filtre === 'non_lues') {
            $query->whereNull('read_at');
        } elseif ($filtre === 'lues') {
            $query->whereNotNull('read_at');
        }

        $notifications = $query->paginate(20)->withQueryString();

        // Compteurs pour les onglets
        $counts = [
            'toutes'    => $user->notifications()->count(),
            'non_lues'  => $user->unreadNotifications()->count(),
            'lues'      => $user->notifications()->whereNotNull('read_at')->count(),
        ];

        $nonLues = $counts['non_lues'];

        return view('notifications.index', compact('notifications', 'nonLues', 'counts', 'filtre'));
    }

    /**
     * Marquer une notification comme lue (redirige vers son URL cible).
     */
    public function read(string $id)
    {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        $url = $notification->data['url'] ?? route('notifications.index');

        return redirect($url);
    }

    /**
     * Marquer toutes les notifications comme lues.
     */
    public function readAll()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Toutes vos notifications ont été marquées comme lues.');
    }
}