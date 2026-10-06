<?php

namespace App\Services;

use App\Models\Demande;
use App\Models\Repetiteur;
use Illuminate\Support\Collection;

class MatchingService
{
    /**
     * Suggère les meilleurs répétiteurs pour une demande donnée.
     * Retourne une collection triée par score de pertinence (décroissant).
     */
    public function suggerer(Demande $demande, array $filtres = []): Collection
    {
        // Récupérer uniquement les répétiteurs validés
        $query = Repetiteur::with('user')
            ->where('statut', 'profil_valide');

        // Filtres supplémentaires (recherche manuelle admin)
        if (!empty($filtres['commune'])) {
            $query->where('communes', 'like', '%' . $filtres['commune'] . '%');
        }

        if (!empty($filtres['matiere'])) {
            $query->where('matieres', 'like', '%' . $filtres['matiere'] . '%');
        }

        if (!empty($filtres['experience_min'])) {
            $query->where('experience_annees', '>=', (int) $filtres['experience_min']);
        }

        $repetiteurs = $query->get();

        // Calculer le score pour chaque répétiteur
        $scored = $repetiteurs->map(function ($rep) use ($demande) {
            $score = $this->calculerScore($demande, $rep);
            $rep->score = $score;
            $rep->score_details = $this->detailsScore($demande, $rep);
            return $rep;
        });

        // Trier par score décroissant
        return $scored->sortByDesc('score')->values();
    }

    /**
     * Calcule un score de pertinence entre 0 et 100.
     */
    protected function calculerScore(Demande $demande, Repetiteur $rep): int
    {
        $score = 0;
        $details = $this->detailsScore($demande, $rep);

        foreach ($details as $points) {
            $score += $points;
        }

        return min(100, $score);
    }

    /**
     * Retourne le détail du score (pour affichage admin).
     */
    protected function detailsScore(Demande $demande, Repetiteur $rep): array
    {
        $details = [];

        // 1. Matières en commun (max 40 pts)
        $matieresDemande = $demande->matieres ?? [];
        $matieresRep = $rep->matieres ?? [];
        $matchMatieres = array_intersect($matieresDemande, $matieresRep);
        $details['matieres'] = count($matchMatieres) > 0 ? 40 : 0;

        // 2. Commune du parent dans les zones du répétiteur (max 25 pts)
        $communeParent = $demande->parent->commune;
        $communesRep = $rep->communes ?? [];
        $details['zone'] = in_array($communeParent, $communesRep) ? 25 : 0;

        // 3. Niveau / classe compatible (max 20 pts)
        // Vérifie si la classe de l'élève est dans les classes du répétiteur
        $classeEleve = $demande->eleve->classe;
        $classesRep = $rep->classes ?? [];
        $details['niveau'] = in_array($classeEleve, $classesRep) ? 20 : 0;

        // 4. Disponibilité (max 15 pts)
        $joursDemande = $demande->jours_disponibles ?? [];
        $joursRep = $rep->jours_disponibles ?? [];
        $matchJours = array_intersect($joursDemande, $joursRep);
        $details['disponibilite'] = count($matchJours) > 0 ? 15 : 0;

        return $details;
    }
}