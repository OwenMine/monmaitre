<?php

declare(strict_types=1);

use App\Http\Controllers\PublicController;
use App\Http\Controllers\PreInscriptionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PushSubscriptionController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\DemandeController as AdminDemandeController;
use App\Http\Controllers\Admin\RepetiteurController as AdminRepetiteurController;
use App\Http\Controllers\Admin\MatchingController as AdminMatchingController;
use App\Http\Controllers\Admin\PlacementController as AdminPlacementController;
use App\Http\Controllers\Admin\SeanceController as AdminSeanceController;
use App\Http\Controllers\Admin\SuiviController as AdminSuiviController;
use App\Http\Controllers\ParentSpace\DashboardController as ParentDashboard;
use App\Http\Controllers\ParentSpace\DemandeController as ParentDemandeController;
use App\Http\Controllers\ParentSpace\PropositionController as ParentPropositionController;
use App\Http\Controllers\ParentSpace\PlacementController as ParentPlacementController;
use App\Http\Controllers\ParentSpace\SeanceController as ParentSeanceController;
use App\Http\Controllers\Repetiteur\DashboardController as RepetiteurDashboard;
use App\Http\Controllers\Repetiteur\ProfilController as RepetiteurProfilController;
use App\Http\Controllers\Repetiteur\PlacementController as RepetiteurPlacementController;
use App\Http\Controllers\Repetiteur\SeanceController as RepetiteurSeanceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes publiques
|--------------------------------------------------------------------------
*/

Route::get('/', [PublicController::class, 'accueil'])->name('accueil');

// Photo de profil répétiteur
Route::get('/photo-repetiteur/{repetiteur}', [PublicController::class, 'photoRepetiteur'])
    ->middleware('throttle:120,1')
    ->name('repetiteur.photo');

// Page hors ligne (PWA)
Route::get('/offline', fn() => view('offline'))->name('offline');

// Pages légales
Route::get('/cgu', fn() => view('legal.cgu'))->name('legal.cgu');
Route::get('/confidentialite', fn() => view('legal.confidentialite'))->name('legal.confidentialite');
Route::get('/cookies', fn() => view('legal.cookies'))->name('legal.cookies');
Route::get('/mentions-legales', fn() => view('legal.mentions'))->name('legal.mentions');

// Pré-inscription
Route::get('/pre-inscription/parent', [PreInscriptionController::class, 'formParent'])
    ->name('pre-inscription.parent');
Route::post('/pre-inscription/parent', [PreInscriptionController::class, 'storeParent'])
    ->middleware('throttle:pre-inscription')
    ->name('pre-inscription.parent.store');

Route::get('/pre-inscription/repetiteur', [PreInscriptionController::class, 'formRepetiteur'])
    ->name('pre-inscription.repetiteur');
Route::post('/pre-inscription/repetiteur', [PreInscriptionController::class, 'storeRepetiteur'])
    ->middleware('throttle:pre-inscription')
    ->name('pre-inscription.repetiteur.store');

/*
|--------------------------------------------------------------------------
| Espace Parent
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:parent'])->prefix('parent')->name('parent.')->group(function () {
    Route::get('/dashboard', [ParentDashboard::class, 'index'])->name('dashboard');

    // Demandes
    Route::get('/demandes', [ParentDemandeController::class, 'index'])->name('demandes.index');
    Route::get('/demandes/creer', [ParentDemandeController::class, 'create'])->name('demandes.create');
    Route::post('/demandes', [ParentDemandeController::class, 'store'])
        ->middleware('throttle:forms')
        ->name('demandes.store');
    Route::get('/demandes/{demande}', [ParentDemandeController::class, 'show'])->name('demandes.show');

    // Propositions
    Route::get('/propositions', [ParentPropositionController::class, 'index'])->name('propositions.index');
    Route::post('/propositions/{proposition}/accepter', [ParentPropositionController::class, 'accepter'])
        ->middleware('throttle:parent-actions')
        ->name('propositions.accepter');
    Route::post('/propositions/{proposition}/refuser', [ParentPropositionController::class, 'refuser'])
        ->middleware('throttle:parent-actions')
        ->name('propositions.refuser');

    // Placements
    Route::get('/placements', [ParentPlacementController::class, 'index'])->name('placements.index');
    Route::get('/placements/{placement}', [ParentPlacementController::class, 'show'])->name('placements.show');
    Route::post('/placements/{placement}/evaluer', [ParentPlacementController::class, 'storeEvaluation'])
        ->middleware('throttle:forms')
        ->name('placements.evaluer');

    // Séances
    Route::post('/seances/{seance}/valider', [ParentSeanceController::class, 'valider'])
        ->middleware('throttle:parent-actions')
        ->name('seances.valider');
    Route::post('/seances/{seance}/contester', [ParentSeanceController::class, 'contester'])
        ->middleware('throttle:parent-actions')
        ->name('seances.contester');
    Route::post('/placements/{placement}/valider-tout', [ParentSeanceController::class, 'validerTout'])
        ->middleware('throttle:parent-actions')
        ->name('placements.valider-tout');
});

/*
|--------------------------------------------------------------------------
| Espace Répétiteur
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:repetiteur'])->prefix('repetiteur')->name('repetiteur.')->group(function () {
    Route::get('/dashboard', [RepetiteurDashboard::class, 'index'])->name('dashboard');

    // Profil
    Route::get('/profil', [RepetiteurProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [RepetiteurProfilController::class, 'update'])
        ->middleware('throttle:repetiteur-actions')
        ->name('profil.update');
    Route::post('/profil/candidature/envoyer', [RepetiteurProfilController::class, 'envoyerCandidature'])
        ->middleware('throttle:repetiteur-actions')
        ->name('profil.candidature.envoyer');

    // Photo
    Route::post('/profil/photo', [RepetiteurProfilController::class, 'uploadPhoto'])
        ->middleware('throttle:repetiteur-actions')
        ->name('profil.photo.upload');
    Route::delete('/profil/photo', [RepetiteurProfilController::class, 'supprimerPhoto'])
        ->middleware('throttle:repetiteur-actions')
        ->name('profil.photo.delete');

    // Documents
    Route::post('/documents', [RepetiteurProfilController::class, 'uploadDocument'])
        ->middleware('throttle:repetiteur-actions')
        ->name('documents.store');
    Route::delete('/documents/{document}', [RepetiteurProfilController::class, 'supprimerDocument'])
        ->middleware('throttle:repetiteur-actions')
        ->name('documents.destroy');

    // Placements
    Route::get('/placements', [RepetiteurPlacementController::class, 'index'])->name('placements.index');
    Route::get('/placements/{placement}', [RepetiteurPlacementController::class, 'show'])->name('placements.show');

    // Séances
    Route::get('/placements/{placement}/seances', [RepetiteurSeanceController::class, 'index'])->name('seances.index');
    Route::get('/placements/{placement}/seances/creer', [RepetiteurSeanceController::class, 'create'])->name('seances.create');
    Route::post('/placements/{placement}/seances', [RepetiteurSeanceController::class, 'store'])
        ->middleware('throttle:repetiteur-actions')
        ->name('seances.store');
    Route::delete('/seances/{seance}', [RepetiteurSeanceController::class, 'destroy'])
        ->middleware('throttle:repetiteur-actions')
        ->name('seances.destroy');
});

/*
|--------------------------------------------------------------------------
| Espace Admin
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // Demandes
    Route::get('/demandes', [AdminDemandeController::class, 'index'])->name('demandes.index');
    Route::get('/demandes/{demande}', [AdminDemandeController::class, 'show'])->name('demandes.show');
    Route::post('/demandes/{demande}/statut', [AdminDemandeController::class, 'changerStatut'])
        ->middleware('throttle:admin-normal')
        ->name('demandes.statut');

    // Répétiteurs
    Route::get('/repetiteurs', [AdminRepetiteurController::class, 'index'])->name('repetiteurs.index');
    Route::get('/repetiteurs/{repetiteur}', [AdminRepetiteurController::class, 'show'])->name('repetiteurs.show');
    Route::post('/repetiteurs/{repetiteur}/valider', [AdminRepetiteurController::class, 'valider'])
        ->middleware('throttle:admin-critical')
        ->name('repetiteurs.valider');
    Route::post('/repetiteurs/{repetiteur}/refuser', [AdminRepetiteurController::class, 'refuser'])
        ->middleware('throttle:admin-critical')
        ->name('repetiteurs.refuser');
    Route::post('/repetiteurs/{repetiteur}/suspendre', [AdminRepetiteurController::class, 'suspendre'])
        ->middleware('throttle:admin-critical')
        ->name('repetiteurs.suspendre');
    Route::post('/repetiteurs/{repetiteur}/statut', [AdminRepetiteurController::class, 'changerStatut'])
        ->middleware('throttle:admin-normal')
        ->name('repetiteurs.statut');

    // Documents
    Route::get('/documents/{document}/voir', [AdminRepetiteurController::class, 'voirDocument'])->name('documents.voir');
    Route::get('/documents/{document}/telecharger', [AdminRepetiteurController::class, 'telechargerDocument'])->name('documents.telecharger');

    // Matching
    Route::get('/demandes/{demande}/rechercher', [AdminMatchingController::class, 'rechercher'])->name('matching.rechercher');
    Route::get('/demandes/{demande}/resultats', [AdminMatchingController::class, 'resultats'])->name('matching.resultats');
    Route::post('/demandes/{demande}/proposer/{repetiteur}', [AdminMatchingController::class, 'proposer'])
        ->middleware('throttle:admin-critical')
        ->name('matching.proposer');
    Route::post('/propositions/{proposition}/valider', [AdminMatchingController::class, 'validerParAdmin'])
        ->middleware('throttle:admin-critical')
        ->name('matching.valider-admin');

    // Placements
    Route::get('/placements/export', [AdminPlacementController::class, 'export'])
        ->middleware('throttle:exports')
        ->name('placements.export');
    Route::get('/placements', [AdminPlacementController::class, 'index'])->name('placements.index');
    Route::get('/placements/{placement}', [AdminPlacementController::class, 'show'])->name('placements.show');
    Route::put('/placements/{placement}', [AdminPlacementController::class, 'update'])
        ->middleware('throttle:admin-normal')
        ->name('placements.update');

    // Suivi
    Route::get('/suivi', [AdminSuiviController::class, 'index'])->name('suivi.index');

    // Séances — validation admin
    Route::post('/seances/{seance}/valider-admin', [AdminSeanceController::class, 'validerParAdmin'])
        ->middleware('throttle:admin-critical')
        ->name('seances.valider-admin');

    // 🅱️ Séances contestées — Arbitrage (FONCTIONNALITÉ REPORTÉE — v1.0.2)
    // Décommenter quand SeanceArbitreeNotification + admin/seances/conteste.blade.php seront créés
    // Route::get('/seances/conteste', [AdminSeanceController::class, 'conteste'])->name('seances.conteste');
    // Route::post('/seances/{seance}/arbitrer-valide', [AdminSeanceController::class, 'arbitrerValide'])
    //     ->middleware('throttle:admin-critical')
    //     ->name('seances.arbitrer-valide');
    // Route::post('/seances/{seance}/arbitrer-conteste', [AdminSeanceController::class, 'arbitrerConteste'])
    //     ->middleware('throttle:admin-critical')
    //     ->name('seances.arbitrer-conteste');
});

/*
|--------------------------------------------------------------------------
| Notifications (tous les rôles authentifiés)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');

    Route::match(['get', 'post'], '/notifications/{id}/read', [NotificationController::class, 'read'])
        ->name('notifications.read');

    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])
        ->name('notifications.read-all');

    // Push subscriptions
    Route::get('/push/public-key', [PushSubscriptionController::class, 'publicKey'])
        ->name('push.public-key');
    Route::post('/push/subscribe', [PushSubscriptionController::class, 'subscribe'])
        ->name('push.subscribe');
    Route::post('/push/unsubscribe', [PushSubscriptionController::class, 'unsubscribe'])
        ->name('push.unsubscribe');
});

/*
|--------------------------------------------------------------------------
| Authentification (Breeze)
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';