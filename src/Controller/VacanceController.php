<?php

namespace App\Controller;

use App\Entity\Vacance;
use App\Form\VacanceType;
use App\Service\NotificationService;
use App\Repository\VacanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/vacance')]
class VacanceController extends AbstractController
{
    #[Route('/', name: 'app_vacance_index', methods: ['GET'])]
    public function index(VacanceRepository $vacanceRepository): Response
    {
        return $this->render('vacance/index.html.twig', [
            'vacances' => $vacanceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_vacance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, NotificationService $notificationService): Response
    {
        $vacance = new Vacance();
        $status = 'en attente';
        $user = $this->getUser();
        
        
        
        $form = $this->createForm(VacanceType::class, $vacance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $vacance->setUser($user);
            $vacance->setStatus($status);
            //Création de la notification
            $notification = $notificationService->sendVacationRequestNotification($user, $vacance);
            $vacance->setNotification($notification);
            // dd($vacance);
            
            $entityManager->persist($vacance);
            $entityManager->flush();

            return $this->redirectToRoute('app_vacance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('vacance/new.html.twig', [
            'vacance' => $vacance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vacance_show', methods: ['GET'])]
    public function show(Vacance $vacance): Response
    {
        return $this->render('vacance/show.html.twig', [
            'vacance' => $vacance,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_vacance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Vacance $vacance, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VacanceType::class, $vacance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_vacance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('vacance/edit.html.twig', [
            'vacance' => $vacance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vacance_delete', methods: ['POST'])]
    public function delete(Request $request, Vacance $vacance, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vacance->getId(), $request->request->get('_token'))) {
            $entityManager->remove($vacance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_vacance_index', [], Response::HTTP_SEE_OTHER);
    }
}
