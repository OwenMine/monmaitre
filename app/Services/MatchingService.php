<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Demande;
use App\Models\Repetiteur;
use Illuminate\Support\Collection;

/**
 * Service de matching entre une demande et les répétiteurs.
 *
 * Calcule un score de pertinence (0-100) basé sur 4 critères :
 * - Matières en commun (40 pts)
 * - Zone géographique (25 pts)
 * - Niveau/classe (20 pts)
 * - Disponibilité (15 pts)
 */
class MatchingService
{
    /**
     * Suggère les meilleurs répétiteurs pour une demande donnée.
     *
     * @param Demande $demande La demande du parent
     * @param array $filtres Filtres supplémentaires (commune, matiere, experience_min)
     * @return Collection Collection triée par score décroissant
     */
    public function suggerer(Demande $demande, array $filtres = []): Collection
    {
        $query = Repetiteur::with('user')
            ->where('statut', 'profil_valide')
            ->whereHas('user', function ($q) {
                $q->where('status', 'actif');
            });

        // Filtres
        if (! empty($filtres['commune'])) {
            $query->where('communes', 'like', '%' . $filtres['commune'] . '%');
        }

        if (! empty($filtres['matiere'])) {
            $query->where('matieres', 'like', '%' . $filtres['matiere'] . '%');
        }

        if (! empty($filtres['experience_min'])) {
            $query->where('experience_annees', '>=', (int) $filtres['experience_min']);
        }

        $repetiteurs = $query->get();

        // Calculer le score pour chaque répétiteur
        $scored = $repetiteurs->map(function (Repetiteur $rep) use ($demande) {
            $details = $this->detailsScore($demande, $rep);
            $rep->score_details = $details;
            $rep->score = min(100, array_sum($details));
            return $rep;
        });

        return $scored->sortByDesc('score')->values();
    }

    /**
     * Normalise une chaîne : minuscules + suppression des accents.
     */
    protected function normaliser(string $valeur): string
    {
        $valeur = mb_strtolower(trim($valeur));

        return str_replace(
            ['é','è','ê','ë','à','â','ä','î','ï','ô','ö','ù','û','ü','ç','ñ','ÿ','œ'],
            ['e','e','e','e','a','a','a','i','i','o','o','u','u','u','c','n','y','oe'],
            $valeur
        );
    }

    /**
     * Normalise un tableau de valeurs (ou retourne un tableau vide).
     */
    protected function normaliserArray(?array $valeurs): array
    {
        if (empty($valeurs)) {
            return [];
        }

        return array_values(array_filter(array_map(
            fn ($v) => $this->normaliser((string) $v),
            $valeurs
        )));
    }

    /**
     * Retourne le détail du score pour affichage admin.
     *
     * @return array{matieres: int, zone: int, niveau: int, disponibilite: int}
     */
    protected function detailsScore(Demande $demande, Repetiteur $rep): array
    {
        $details = [];

        // 1. Matières en commun (max 40 pts)
        $matieresDemande = $this->normaliserArray($demande->matieres ?? []);
        $matieresRep     = $this->normaliserArray($rep->matieres ?? []);
        $matchMatieres   = array_intersect($matieresDemande, $matieresRep);
        $details['matieres'] = count($matchMatieres) > 0 ? 40 : 0;

        // 2. Commune du parent dans les zones du répétiteur (max 25 pts)
        $communeParent = $this->normaliser($demande->parent->commune ?? '');
        $communesRep   = $this->normaliserArray($rep->communes ?? []);
        $details['zone'] = ($communeParent && in_array($communeParent, $communesRep)) ? 25 : 0;

        // 3. Niveau / classe compatible (max 20 pts)
        $classeEleve = $this->normaliser($demande->eleve->classe ?? '');
        $classesRep  = $this->normaliserArray($rep->classes ?? []);
        $details['niveau'] = ($classeEleve && in_array($classeEleve, $classesRep)) ? 20 : 0;

        // 4. Disponibilité (max 15 pts)
        $joursDemande = $this->normaliserArray($demande->jours_disponibles ?? []);
        $joursRep     = $this->normaliserArray($rep->jours_disponibles ?? []);
        $matchJours   = array_intersect($joursDemande, $joursRep);
        $details['disponibilite'] = count($matchJours) > 0 ? 15 : 0;

        return $details;
    }
}