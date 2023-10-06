<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelper;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route("/employer", name: "employer")]
    public function employer(UserRepository $userRepository): Response
    {
        

        return $this->render('user/employer.html.twig', [
            'users' => $userRepository->findAll()
        ]);
    }


    #[Route("/ajouter_user", name: "ajouter_user")]
    public function ajouter_employer(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $userPasswordHasher, MailerInterface $mailer, ResetPasswordHelper $resetPasswordHelper): Response
    {
        $user = new User;

      
        $form = $this->createForm(UserType::class);
        
        $form->handleRequest($request);

        

        //On ajoute l'employer
        if($form->isSubmitted() && $form->isValid()){


            $email = $form->get('email')->getData();
            $role = $form->get('roles')->getData();
            $nom = $form->get('nom')->getData();
            $prenom = $form->get('prenom')->getData();
            $badge = $form->get('badge')->getData();
            $age = $form->get('age')->getData();
            $contrat = $form->get('contrat')->getData();

            
            $user->setEmail($email);
            $user->setRoles([$role]);
            $user->setNom($nom);
            $user->setPrenom($prenom);
            $user->setBadge($badge);
            $user->setAge($age);
            $user->setContrat($contrat);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData() 
                )
            );
            // dd($user);
           
            $em->persist($user);

            $em->flush();
            
            $resetToken = $resetPasswordHelper->generateResetToken($user);

            $resetLink = $this->generateUrl('app_forgot_password_request', [
                'token' => $resetToken->getTokenValue()
            ], UrlGeneratorInterface::ABSOLUTE_URL);

             
            
            


            


            

            return $this->redirectToRoute('ajouter_user');

        }

        //On envoi l'email a l'employer
        

        
        return $this->render('user/ajouter_user.html.twig', [
            'formUser' => $form->createView()
        ]);
    }


    #[Route("/delete/{id}", name: "delete_user")]
    public function delete_user($id, UserRepository $userRepository, EntityManagerInterface $em): Response
    {

        
        $user = $userRepository->find($id);

        if(!$user){
            //On affiche les message d'erreur si l'utilisateur n'existe pas
            return new Response("L'utilisateur n'existe pas." . $id, 404);
        }

        
        //Si il existe on le supprime
        $em->remove($user);

        $em->flush();

        // return new Response("Utilisateur supprimer avec succès.", 200);

        return $this->redirectToRoute("employer", []);


    }
}
