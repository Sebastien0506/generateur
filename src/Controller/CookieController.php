<?php

namespace App\Controller;

use App\Entity\CookieDescription;
use App\Form\CookieDescriptionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\CookieDescriptionRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use ConnectHolland\CookieConsentBundle\Entity\CookieConsentLog;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CookieController extends AbstractController
{
    #[Route('/cookie', name: 'app_cookie')]
    public function index(): Response
    {
        return $this->render('cookie/index.html.twig', [
            'controller_name' => 'CookieController',
        ]);
    }

    #[Route('/accepted', name:'accepted_cookie')]
    public function acceptedCookie(Request $request, EntityManagerInterface $enttityManager){

        //On récupère les données du formulaire 
        $data = $request->request->all();

    //On crée une nouvelle instance de CookieConsentLog
        $cookieConsentLog = new CookieConsentLog();
    
        // On definit les propriétés de l'entité avec les données reçus
        $cookieConsentLog->setIpAddress($request->getClientIp());
        $cookieConsentLog->setCookieConsentKey($data['cookie_consent_key'] ?? '');
        $cookieConsentLog->setCookieName($data['cookie_name'] ?? '');
        $cookieConsentLog->setCookieValue($data['cookie_value'] ?? '');
        $cookieConsentLog->setTimestamp(new \DateTime());

        //On enregistre dans la base de données
        $enttityManager->persist($cookieConsentLog);
        $enttityManager->flush();

        //On renvoi une reponse au format Json
        return new JsonResponse(['status' => 'success']);

    }

    #[Route('/privacy-statement', name:'privacy-statement')]
    public function cookie_privacy(CookieDescriptionRepository $cookieDescriptionRepository)
    {
        // Ce code permet de récupérer la description des cookies et de l'afficher
        return $this->render('cookie/privacy_statement.html.twig', [
            'cookieDescriptions' => $cookieDescriptionRepository->findAll()
        ]);
    }

    #[Route('/cookie_description', name: 'cookie_description_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        //Ce code permet de créer une nouvelle description pour les cookie
        $cookieDescription = new CookieDescription();
        $form = $this->createForm(CookieDescriptionType::class, $cookieDescription);
        $form->handleRequest($request);

        //Permet de vérifier si le formulaire & a été soumis et si il est valide
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($cookieDescription);
            $entityManager->flush();

            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }
        //Renvoi vers la page pour afficher le formulaire
        return $this->render('cookie/newCookie.html.twig', [
            'formCookie' => $form->createView(),
            
        ]);
    }

    #[Route("changed_cookie", name:"changed_cookie_consent")]
    public function changedCookieConsent(): Response
    {
        return $this->render('cookie/changeCookieConsent.html.twig', []);
    }
}
