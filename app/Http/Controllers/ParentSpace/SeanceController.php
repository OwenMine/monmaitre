<?php

namespace App\Http\Controllers\ParentSpace;

use App\Http\Controllers\Controller;
use App\Models\Placement;
use App\Models\Seance;
use App\Notifications\SeanceContesteeNotification;
use App\Notifications\SeanceValideeNotification;
use Illuminate\Http\Request;

class SeanceController extends Controller
{
    /**
     * Valider une séance individuellement.
     */
    public function valider(Seance $seance)
    {
        if ($seance->placement->parent_id !== auth()->id()) {
            abort(403);
        }

        if (! $seance->estEnAttente()) {
            return redirect()->back()
                ->with('error', 'Cette séance a déjà été traitée.');
        }

        $seance->update([
            'statut'      => 'effectuee',
            'validee_at'  => now(),
            'validee_par' => auth()->id(),
        ]);

        // 🔔 Notifier le répétiteur
        $seance->repetiteur->user->notify(new SeanceValideeNotification($seance));

        return redirect()->back()
            ->with('success', 'Séance validée avec succès.');
    }

    /**
     * Contester une séance avec une raison.
     */
    public function contester(Request $request, Seance $seance)
    {
        if ($seance->placement->parent_id !== auth()->id()) {
            abort(403);
        }

        if (! $seance->estEnAttente()) {
            return redirect()->back()
                ->with('error', 'Cette séance a déjà été traitée.');
        }

        $data = $request->validate([
            'raison_contestation' => 'required|string|max:1000',
        ]);

        $seance->update([
            'statut'              => 'contested',
            'validee_at'          => now(),
            'validee_par'         => auth()->id(),
            'raison_contestation' => $data['raison_contestation'],
        ]);

        // 🔔 Notifier le répétiteur
        $seance->repetiteur->user->notify(new SeanceContesteeNotification($seance));

        // 🔔 Notifier les admins pour arbitrage
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new SeanceContesteeNotification($seance));
        }

        return redirect()->back()
            ->with('success', 'Séance contestée. MON MAÎTRE va arbitrer.');
    }

    /**
     * Valider toutes les séances en attente d'un placement.
     */
    public function validerTout(Placement $placement)
    {
        if ($placement->parent_id !== auth()->id()) {
            abort(403);
        }

        $seances = $placement->seances()->where('statut', 'en_attente')->get();

        if ($seances->isEmpty()) {
            return redirect()->back()
                ->with('error', 'Aucune séance en attente.');
        }

        foreach ($seances as $seance) {
            $seance->update([
                'statut'      => 'effectuee',
                'validee_at'  => now(),
                'validee_par' => auth()->id(),
            ]);

            $seance->repetiteur->user->notify(new SeanceValideeNotification($seance));
        }

        return redirect()->back()
            ->with('success', $seances->count() . ' séance(s) validée(s) avec succès.');
    }
}