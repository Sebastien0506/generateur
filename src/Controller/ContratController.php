<?php

namespace App\Controller;

use App\Entity\Contrat;
use App\Form\ContratType;
use App\Repository\ContratRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/contrat')]
class ContratController extends AbstractController
{
    #[Route('/contrat', name: 'contrat', methods: ['GET'])]
     public function contrat(ContratRepository $contratRepository): Response
     {
        return $this-> render('contrat/contrat.html.twig', [
            "contrats" => $contratRepository->findAll()
        ]);
     }
     
     #[Route('/ajouter_contrat', name: 'ajouter_contrat', methods: ['GET', 'POST'])]
     public function ajouter_contrat(Request $request, ContratRepository $contratRepository, EntityManagerInterface $em): Response
     {
        $contrat = new Contrat;
        
        $form = $this->createForm(ContratType::class, $contrat);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em->persist($contrat);

            $em->flush();


            $this-> addFlash('success', 'Le contrat a été ajouter avec succès.');

            return $this->redirectToRoute('ajouter_contrat');
        }
        return $this->render('contrat/ajouter_contrat.html.twig', [
            'formContrat' => $form->createView()
        ]);
     } 
}

