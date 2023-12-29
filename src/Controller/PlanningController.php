<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Planning;
use App\Service\PlanningService;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PlanningController extends AbstractController
{
    #[Route('/planning', name: 'generate_planning')]
    public function index(UserRepository $userRepository, PlanningService $planningService): Response
    {
        $employes = $userRepository->findAll();
        $planning = $planningService->genererPlanning($employes);
        

        //renvoi les données en tant que reponse Json
        return new JsonResponse($planning);
    }
}
