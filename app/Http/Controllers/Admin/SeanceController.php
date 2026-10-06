<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seance;
use App\Notifications\SeanceValideeNotification;
use App\Notifications\SeanceValideeAdminNotification;
use App\Services\AuditLogger;
use Illuminate\Http\Request;

class SeanceController extends Controller
{
    /**
     * Liste des séances contestées (pour arbitrage admin).
     */
    public function conteste()
    {
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
    }

    /**
     * ⚡ ADMIN valide une séance au nom du parent.
     * Utile quand le parent ne valide pas (oubli, absence, etc.).
     */
    public function validerParAdmin(Seance $seance)
    {
        // Vérifier le statut
        if ($seance->statut !== 'en_attente') {
            return redirect()->back()->with('error', 'Cette séance a déjà été traitée.');
        }

        // Charger les relations
        $seance->load(['placement.parent', 'repetiteur.user']);

        // 1. Mettre à jour la séance
        $seance->update([
            'statut'      => 'effectuee',
            'validee_at'  => now(),
            'validee_par' => auth()->id(),
        ]);

        // 2. Notifier le répétiteur
        $seance->repetiteur->user->notify(new SeanceValideeAdminNotification($seance));

        // 3. Notifier le parent (informatif)
        $seance->placement->parent->notify(new SeanceValideeNotification($seance));

        // 4. Log audit
        AuditLogger::admin('SEANCE_VALIDEE_ADMIN',
            "Validation de la séance du {$seance->date->format('d/m/Y')} pour {$seance->placement->eleve->prenom}",
            [
                'seance_id' => $seance->id,
                'placement_id' => $seance->placement_id,
                'repetiteur_id' => $seance->repetiteur_id,
                'date_seance' => $seance->date->format('Y-m-d'),
            ]
        );

        return redirect()->back()
            ->with('success', 'Séance validée avec succès. Le répétiteur a été notifié.');
    }

    /**
     * Admin arbitre : valide la séance contestée (le répétiteur avait raison).
     */
    public function arbitrerValide(Seance $seance)
    {
        if ($seance->statut !== 'contested') {
            return redirect()->back()->with('error', 'Cette séance n\'est pas en contestation.');
        }

        $seance->update(['statut' => 'effectuee']);

        $seance->placement->parent->notify(new SeanceArbitreeNotification($seance, 'validee'));
        $seance->repetiteur->user->notify(new SeanceArbitreeNotification($seance, 'validee'));

        return redirect()->back()
            ->with('success', 'Séance validée. Le parent et le répétiteur ont été notifiés.');
    }

    /**
     * Admin arbitre : confirme la contestation (le parent avait raison).
     */
    public function arbitrerConteste(Seance $seance)
    {
        if ($seance->statut !== 'contested') {
            return redirect()->back()->with('error', 'Cette séance n\'est pas en contestation.');
        }

        $seance->update(['statut' => 'annulee']);

        $seance->placement->parent->notify(new SeanceArbitreeNotification($seance, 'contestee'));
        $seance->repetiteur->user->notify(new SeanceArbitreeNotification($seance, 'contestee'));

        return redirect()->back()
            ->with('success', 'Contestation confirmée. La séance a été annulée.');
    }
}