<?php

declare(strict_types=1);

namespace App\Http\Controllers\ParentSpace;

use App\Http\Controllers\Controller;
use App\Models\Placement;
use App\Models\Seance;
use App\Notifications\SeanceContesteeNotification;
use App\Notifications\SeanceValideeNotification;
use App\Support\AuthHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SeanceController extends Controller
{
    /**
     * Valider une séance individuellement.
     */
    public function valider(Seance $seance): RedirectResponse
    {
        // ⚡ D.1 : Vérification d'ownership via AuthHelper
        AuthHelper::verifierPropriete($seance->placement, champ: 'parent_id');

        // ⚡ Transaction + lock pour éviter les doubles validations
        $result = DB::transaction(function () use ($seance) {
            $seance = Seance::lockForUpdate()->find($seance->id);

            if (! $seance) {
                return ['error' => 'Séance introuvable.'];
            }

            if (! $seance->estEnAttente()) {
                return ['error' => 'Cette séance a déjà été traitée.'];
            }

            $seance->update([
                'statut'      => 'effectuee',
                'validee_at'  => now(),
                'validee_par' => auth()->id(),
            ]);

            return ['success' => true, 'seance' => $seance];
        });

        if (isset($result['error'])) {
            return redirect()->back()->with('error', $result['error']);
        }

        // 🔔 Notifier le répétiteur
        $result['seance']->repetiteur->user->notify(
            new SeanceValideeNotification($result['seance'])
        );

        return redirect()->back()
            ->with('success', 'Séance validée avec succès.');
    }

    /**
     * Contester une séance avec une raison.
     */
    public function contester(Request $request, Seance $seance): RedirectResponse
    {
        // ⚡ D.1 : Vérification d'ownership via AuthHelper
        AuthHelper::verifierPropriete($seance->placement, champ: 'parent_id');

        $data = $request->validate([
            'raison_contestation' => 'required|string|max:1000',
        ]);

        // ⚡ Transaction + lock
        $result = DB::transaction(function () use ($seance, $data) {
            $seance = Seance::lockForUpdate()->find($seance->id);

            if (! $seance) {
                return ['error' => 'Séance introuvable.'];
            }

            if (! $seance->estEnAttente()) {
                return ['error' => 'Cette séance a déjà été traitée.'];
            }

            $seance->update([
                'statut'              => 'contested',
                'validee_at'          => now(),
                'validee_par'         => auth()->id(),
                'raison_contestation' => $data['raison_contestation'],
            ]);

            return ['success' => true, 'seance' => $seance];
        });

        if (isset($result['error'])) {
            return redirect()->back()->with('error', $result['error']);
        }

        $seance = $result['seance'];

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
    public function validerTout(Placement $placement): RedirectResponse
    {
        // ⚡ D.1 : Vérification d'ownership via AuthHelper
        AuthHelper::verifierPropriete($placement, champ: 'parent_id');

        $seances = $placement->seances()->where('statut', 'en_attente')->get();

        if ($seances->isEmpty()) {
            return redirect()->back()->with('error', 'Aucune séance en attente.');
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