<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use ConnectHolland\CookieConsentBundle\Cookie\CookieChecker;
use ConnectHolland\CookieConsentBundle\Entity\CookieConsentLog;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SubmitCookieController extends AbstractController
{
    #[Route('/submit/cookie', name: 'app_submit_cookie', methods:['POST'])]
    public function submitCookie(Request $request, EntityManagerInterface $entityManager): Response
    {
        //On récupère les donnée du formulaire
      $data = $request->request->all();

      // On créer une nouvelle instance de CookieConsentLog
      $cookieValue = new CookieConsentLog();

      //On définit les propriété de l'entité avec les données reçues
      $cookieValue->setIpAddress($request->getClientIp());
      $cookieValue->setCookieConsentKey($data['cookie_consent_key'] ?? '');
      $cookieValue->setCookieName($data['cookie_name'] ?? '');
      $cookieValue->setCookieValue($data['cookie_value'] ?? '');
      $cookieValue->setTimestamp(new \DateTime());

      //On enregistre dans la base données
      $entityManager->persist($cookieValue);
      $entityManager->flush();


      //On renvoie une réponse au format Json
      return new JsonResponse(['status', 'success']);

    }

   

}
