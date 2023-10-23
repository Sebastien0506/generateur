<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserEditType;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelper;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;


#[Route('/user')]
class UserController extends AbstractController
{
    #[Route("/employer", name: "employer")]
    public function employer(UserRepository $userRepository): Response
    {
        //Ce code permet de recuperer l'ensemble des employer pour l'administrateur

        return $this->render('user/employer.html.twig', [
            'users' => $userRepository->findAll()
        ]);
    }


    #[Route("/ajouter_user", name: "ajouter_user")]
    public function ajouter_employer(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $userPasswordHasher, MailerInterface $mailer): Response
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
            $heureDebut = $form->get('horaireDebut')->getData();
            $heureFin = $form->get('horaireFin')->getData();

            // dd($heureDebut, $heureFin);
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
            $user->setHoraireDebut($heureDebut);
            $user->setHoraireFin($heureFin);
            if($age === "no"){
                $heureDebut = $form->get('horaireDebut')->getData();
                $heureFin = $form->get('horaireFin')->getData();

                if($heureDebut->format('H:i') < '06:00' || $heureFin->format('H:i') > '21:00'){
                    $this->addFlash('error', 'L\'employer est mineur il ne peut donc pas travailler avant 06h00 et après 21h00.');

                    return $this->render('user/ajouter_user.html.twig', [
                        'formUser' => $form->createView()
                    ]);
                }
            }
            $intervalle = $heureDebut->diff($heureFin);
// dd($intervalle);
            //Convertir cette intervalle en heure total
            $heureTotales = $intervalle->h + ($intervalle->i / 60);
// dd($heureTotales);
            if($heureTotales > 8){
                $this->addFlash('error', 'La duré total de travail dépasse 8h.');

                return $this->render('user/ajouter_user.html.twig', [
                    'formUser' => $form->createView()
                ]);
            }
            
            $em->persist($user);

            $em->flush();
            
            $link = $this->generateUrl('app_forgot_password_request', [], UrlGeneratorInterface::ABSOLUTE_URL);
            $email = (new Email())
            ->from('hello@example.com')
            ->to('you@example.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Ajoue d\'employer')
            ->text('Sending emails is fun again!')
            ->html('<p>Vous venez d\'être ajouté au générateur de planning. Veuillez cliquer sur le lien pour réinitialisez votre mot de passe <a href="' . $link .'">lien</a></p>');

        $mailer->send($email);
             
            return $this->redirectToRoute('ajouter_user');

        }
        $this->addFlash('success', "L'employer a été ajouté avec succès.");
        return $this->render('user/ajouter_user.html.twig', [
            'formUser' => $form->createView()
        ]);
    }

    #[Route("/modifier/{id}", name:"modifier_user")]
    public function modifier_user(int $id, Request $request, User $user, EntityManagerInterface $em): Response
    {
      
    
   
    if(!$user){
        throw $this->createNotFoundException(('Employer non trouvé'));
    }

      $form = $this->createForm(UserEditType::class, $user);

      $form->handleRequest($request);

      if($form->isSubmitted() && $form->isValid())
      {
        $email = $form->get('email')->getData();
        $nom = $form->get('nom')->getData();
        $prenom = $form->get('prenom')->getData();
        $badge = $form->get('badge')->getData();
        $age = $form->get('age')->getData();
        $contrat = $form->get('contrat')->getData();

        $user->setEmail($email);
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setBadge($badge);
        $user->setAge($age);
        $user->setContrat($contrat);

        $em->persist($user);
        $em->flush();

        $this->addFlash('success', "Employer modifier avec succès");

        return $this->redirectToRoute('employer');
      }
      return $this->render("user/modifier_user.html.twig", [
        "form" => $form->createView()
      ]);
    }
    #[Route("/delete/{id}", name: "delete_user")]
    public function delete_user($id, UserRepository $userRepository, EntityManagerInterface $em): Response
    {

        
        $user = $userRepository->find($id);

        if(!$user){
            //On affiche les message d'erreur si l'utilisateur n'existe pas
            return new Response("L'utilisateur n'existe pas.");
        }

        
        //Si il existe on le supprime
        $em->remove($user);

        $em->flush();

        // return new Response("Utilisateur supprimer avec succès.", 200);

        return $this->redirectToRoute("employer", []);
    }

    #[Route("/user", name: "user")]
    public function user(): Response
    {
        return $this->render('profil/profile.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
    #[Route("/information", name: "information_user")]
    public function information_user():Response
    {
        //Ce code permet de recuperer l'utilisateur connecter et d'afficher tout ces information
        $user = $this->getUser();
       
        return $this->render('profil/information.html.twig', [
            'user' => $user
        ]);
    }
}
