<?php

namespace App\Controller;

use App\Entity\Vacance;
use App\Entity\Notification;
use Doctrine\ORM\EntityManager;
use App\Repository\NotificationRepository;
use App\Repository\VacanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NotificationController extends AbstractController
{
    #[Route('/notification', name: 'app_notification')]
    public function index(NotificationRepository $notificationRepository): Response
    {
        $pendingNotification = $notificationRepository->findByStatus('en attente');
        return $this->render('notification/index.html.twig', [
            'notifications' => $pendingNotification,
        ]);
    }
    #[Route("/vacation/accept/{id}", name:"accept_vacation")]
    public function acceptVacation(int $id, EntityManagerInterface $entityManager)
    {
        $vacation = $entityManager->getRepository(Vacance::class)->find($id);
        // dd($vacation);
        if($vacation){
            $vacation->setStatus('accepted');
            $entityManager->persist($vacation);
            $entityManager->flush();
            
        }
        
        
        $this->addFlash('success', 'Vacance accepter avec succès.');
        // dd($vacation);
        return $this->redirectToRoute('app_notification');
    }
    #[Route("/delete/{id}", name:"delete_vacation", methods: ["POST"])]
    public function deleteVacation(int $id, EntityManagerInterface $entityManager, Request $request): Response
    {
        //Trouver la demande de vacance par son id
        $vacance = $entityManager->getRepository(Vacance::class)->find($id);
        // dd($vacance);
        //Si la demande de vacance n'existe pas on renvoi un message d'erreur 
        if(!$vacance){
            throw $this->createNotFoundException('Demande de vacance pas trouver');
        }

        //On vérifi le jeton CSRF
        if($this->isCsrfTokenValid('delete'.$vacance->getId(), $request->request->get('_token'))){
            //On trouve la notification associé a la demande de vacance 
            $notification = $vacance->getNotification();
            
            //On supprime la notification et la demande de la base de donnée
            $entityManager->remove($vacance);
            if($notification){
                $entityManager->remove($notification);
            }
            $entityManager->flush();

            //On redirige vers la page de notification
            return $this->redirectToRoute('app_notification'); 
        }

        $this->addFlash('success', 'Demande de vacance refuser avec succès.');
        return $this->redirectToRoute('app_notification', [], Response::HTTP_SEE_OTHER);

        //Si le jeton CSRF n'est pas valide
        throw $this->createAccessDeniedException('Invalid CSRF token');
    }
}
