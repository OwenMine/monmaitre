<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();
        $filtre = $request->get('filtre', 'toutes');

        $query = $user->notifications();

        if ($filtre === 'non_lues') {
            $query->whereNull('read_at');
        } elseif ($filtre === 'lues') {
            $query->whereNotNull('read_at');
        }

        $notifications = $query->paginate(20)->withQueryString();

        $counts = [
            'toutes'   => $user->notifications()->count(),
            'non_lues' => $user->unreadNotifications()->count(),
            'lues'     => $user->notifications()->whereNotNull('read_at')->count(),
        ];

        $nonLues = $counts['non_lues'];

        return view('notifications.index', compact('notifications', 'nonLues', 'counts', 'filtre'));
    }

    public function read(string $id): RedirectResponse
    {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        $url = $notification->data['url'] ?? route('notifications.index');

        return redirect($url);
    }

    public function readAll(): RedirectResponse
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Toutes vos notifications ont été marquées comme lues.');
    }
}