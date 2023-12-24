<?php
namespace App\Service;

use App\Entity\User;
use App\Entity\Vacance;
use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;

class NotificationService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    public function sendVacationRequestNotification(User $user, Vacance $vacance){
        $notification = new Notification();
        $notification->setMessage("L'employer " . $user->getNom() . " a demander des vacances qui iront du " . $vacance->getJourDeDebut()->format('d/m/Y') . " au " . $vacance->getJourDeFin()->format('d/m/Y'));

        
        
        $this->entityManager->persist($notification);
        $this->entityManager->flush();
        return $notification;
    }
}


?>

