<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;


class PlanningService {
    public function genererPlanning($employes){
        $planning = [];

        foreach($employes as $employe) {

            $planning[] = [
                'nom' => $employe->getNom(),
                'prenom' => $employe->getPrenom(),
                'heureDebut' => $employe->getHoraireDebut()->format('H:i'),
                'heureFin' => $employe->getHoraireFin()->format('H:i'),
            ];
        }

        return $planning;
    }
}
?>