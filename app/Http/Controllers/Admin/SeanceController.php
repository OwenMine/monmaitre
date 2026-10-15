<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seance;
use App\Notifications\SeanceValideeNotification;
use App\Notifications\SeanceValideeAdminNotification;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;

class SeanceController extends Controller
{
    /**
     * Liste des séances contestées (pour arbitrage admin).
     * 🅱️ FONCTIONNALITÉ REPORTÉE — v1.0.2
     */
    public function conteste(): RedirectResponse
    {
        return redirect()->route('admin.dashboard')
            ->with('info', '🚧 La page "Séances contestées" est en cours de développement. Elle sera disponible prochainement.');

        /*
        $seances = Seance::with([
                'placement.parent',
                'placement.eleve',
                'placement.repetiteur.user',
                'valideePar',
            ])
            ->where('statut', 'contested')
            ->latest('validee_at')
            ->paginate(15);

        return view('admin.seances.conteste', compact('seances'));
        */
    }

    /**
     * ADMIN valide une séance au nom du parent.
     */
    public function validerParAdmin(Seance $seance): RedirectResponse
    {
        if ($seance->statut !== 'en_attente') {
            return redirect()->back()->with('error', 'Cette séance a déjà été traitée.');
        }

        $seance->load(['placement.parent', 'repetiteur.user']);

        $seance->update([
            'statut'      => 'effectuee',
            'validee_at'  => now(),
            'validee_par' => auth()->id(),
        ]);

        $seance->repetiteur->user->notify(new SeanceValideeAdminNotification($seance));
        $seance->placement->parent->notify(new SeanceValideeNotification($seance));

        AuditLogger::admin(
            'SEANCE_VALIDEE_ADMIN',
            "Validation de la séance du {$seance->date->format('d/m/Y')} pour {$seance->placement->eleve->prenom}",
            [
                'seance_id'     => $seance->id,
                'placement_id'  => $seance->placement_id,
                'repetiteur_id' => $seance->repetiteur_id,
                'date_seance'   => $seance->date->format('Y-m-d'),
            ]
        );

        return redirect()->back()
            ->with('success', 'Séance validée avec succès. Le répétiteur a été notifié.');
    }

    /**
     * Admin arbitre : valide la séance contestée (le répétiteur avait raison).
     * 🅱️ FONCTIONNALITÉ REPORTÉE — v1.0.2
     */
    public function arbitrerValide(Seance $seance): RedirectResponse
    {
        return redirect()->back()
            ->with('info', '🚧 L\'arbitrage des séances est en cours de développement. Il sera disponible prochainement.');

        /*
        if ($seance->statut !== 'contested') {
            return redirect()->back()->with('error', 'Cette séance n\'est pas en contestation.');
        }

        $seance->update(['statut' => 'effectuee']);

        $seance->placement->parent->notify(new SeanceArbitreeNotification($seance, 'validee'));
        $seance->repetiteur->user->notify(new SeanceArbitreeNotification($seance, 'validee'));

        return redirect()->back()
            ->with('success', 'Séance validée. Le parent et le répétiteur ont été notifiés.');
        */
    }

    /**
     * Admin arbitre : confirme la contestation (le parent avait raison).
     * 🅱️ FONCTIONNALITÉ REPORTÉE — v1.0.2
     */
    public function arbitrerConteste(Seance $seance): RedirectResponse
    {
        return redirect()->back()
            ->with('info', '🚧 L\'arbitrage des séances est en cours de développement. Il sera disponible prochainement.');

        /*
        if ($seance->statut !== 'contested') {
            return redirect()->back()->with('error', 'Cette séance n\'est pas en contestation.');
        }

        $seance->update(['statut' => 'annulee']);

        $seance->placement->parent->notify(new SeanceArbitreeNotification($seance, 'contestee'));
        $seance->repetiteur->user->notify(new SeanceArbitreeNotification($seance, 'contestee'));

        return redirect()->back()
            ->with('success', 'Contestation confirmée. La séance a été annulée.');
        */
    }
}