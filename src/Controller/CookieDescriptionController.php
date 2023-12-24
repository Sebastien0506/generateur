<?php

namespace App\Controller;

use App\Entity\CookieDescription;
use App\Form\CookieDescriptionType;
use App\Repository\CookieDescriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cookie/description')]
class CookieDescriptionController extends AbstractController
{
    #[Route('/', name: 'app_cookie_description_index', methods: ['GET'])]
    public function index(CookieDescriptionRepository $cookieDescriptionRepository): Response
    {
        return $this->render('cookie_description/index.html.twig', [
            'cookie_descriptions' => $cookieDescriptionRepository->findAll(),
        ]);
    }

    // #[Route('/new', name: 'app_cookie_description_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $cookieDescription = new CookieDescription();
    //     $form = $this->createForm(CookieDescriptionType::class, $cookieDescription);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->persist($cookieDescription);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_cookie_description_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('cookie_description/new.html.twig', [
    //         'cookie_description' => $cookieDescription,
    //         'form' => $form,
    //     ]);
    // }

    #[Route('/{id}', name: 'app_cookie_description_show', methods: ['GET'])]
    public function show(CookieDescription $cookieDescription): Response
    {
        return $this->render('cookie_description/show.html.twig', [
            'cookie_description' => $cookieDescription,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cookie_description_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CookieDescription $cookieDescription, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CookieDescriptionType::class, $cookieDescription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_cookie_description_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cookie_description/edit.html.twig', [
            'cookie_description' => $cookieDescription,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cookie_description_delete', methods: ['POST'])]
    public function delete(Request $request, CookieDescription $cookieDescription, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cookieDescription->getId(), $request->request->get('_token'))) {
            $entityManager->remove($cookieDescription);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cookie_description_index', [], Response::HTTP_SEE_OTHER);
    }
}
