<?php

namespace App\Controller;

use App\Entity\Boutique;
use App\Entity\User;
use App\Form\UserType;
use App\Form\UserEditType;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use SebastianBergmann\CodeCoverage\Report\Html\Renderer;
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
    #[Route("/employer", name: "employer", methods:['GET'])]
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
            $debut = $form->get('dateDebut')->getData();
            $fin = $form->get('dateFin')->getData();
            $jour = $form->get('jourTravail')->getData();
            
            
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
            $user->setDateDebut($debut);
            $user->setDateFin($fin);
            $user->setJourTravail($jour);
            
            foreach($form->get('boutique')->getData() as $boutique){
                $user->addBoutique($boutique);
            }

            if($user->getContrat()->getTypeContrat() === 'cdi'){
                $user->setDateFin(null);
            }
           
            if($age === "no"){
                //Ce code permet de vérifier si l'employer qui est ajouté est mineur si il est mineur et que l'heure début se trouve avant 6h et l'heure de fin se trouve après 21h
                //un message s'affcihe disant que l'employer est mineur 
                $heureDebut = $form->get('horaireDebut')->getData();
                $heureFin = $form->get('horaireFin')->getData();

                if($heureDebut->format('H:i') < '06:00' || $heureFin->format('H:i') > '21:00'){
                    $this->addFlash('error', 'L\'employer est mineur il ne peut donc pas travailler avant 06h00 et après 21h00.');

                    return $this->render('user/ajouter_user.html.twig', [
                        'formUser' => $form->createView()
                    ]);
                }
            }
            $intervalle = $heureDebut->diff($heureFin);// Sers a calculer l'itervalle entre l'heure de début et de fin 

            //Convertir cette intervalle en heure total
            $heureTotales = $intervalle->h + ($intervalle->i / 60);

            if($heureTotales > 8){
                //Cette parti de code permet de s'assurer que le nombre d'heure de travail ne dépasse pas 8h
                $this->addFlash('error', 'La duré total de travail dépasse 8h.');

                return $this->render('user/ajouter_user.html.twig', [
                    'formUser' => $form->createView()
                ]);
            }
            $joursTravail = $user->getJourTravail($jour);

            $nombreJourTravail = count($joursTravail); //Cette fonction sers a compter le nombre de jour qui se trouve dans le tableau $jour
            
            if($nombreJourTravail > 5){
                //Cette parti du code permet de s'assurer que le nombre de jour ne dépasser pas 5 jour si sa dépasser un message s'affiche
                $this->addFlash('error', 'Le jour de travail total ne peut pas dépasser 5 jour');

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
    public function modifier_user(Request $request, User $user, EntityManagerInterface $em): Response
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
        $heureDebut = $form->get('horaireDebut')->getData();
        $heureFin = $form->get('horaireFin')->getData();
        $debut = $form->get('dateDebut')->getData();
        $fin = $form->get('dateFin')->getData(); 
        $jour = $form->get('jourTravail')->getData();
        


        $user->setEmail($email);
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setBadge($badge);
        $user->setAge($age);
        $user->setContrat($contrat);
        $user->setHoraireDebut($heureDebut);
        $user->setHoraireFin($heureFin);
        $user->setDateDebut($debut);
        $user->setDateFin($fin);
        $user->setJourTravail($jour);
        
        // dd($user);

        if($age === "no"){
            //Ce code permet de vérifier si l'employer qui est ajouté est mineur si il est mineur et que l'heure début se trouve avant 6h et l'heure de fin se trouve après 21h
            //un message s'affcihe disant que l'employer est mineur 
            $heureDebut = $form->get('horaireDebut')->getData();
            $heureFin = $form->get('horaireFin')->getData();

            if($heureDebut->format('H:i') < '06:00' || $heureFin->format('H:i') > '21:00'){
                $this->addFlash('error', 'L\'employer est mineur il ne peut donc pas travailler avant 06h00 et après 21h00.');

                return $this->render('user/modifier_user.html.twig', [
                    'form' => $form->createView()
                ]);
            }
        }
        $intervalle = $heureDebut->diff($heureFin);// Sers a calculer l'itervalle entre l'heure de début et de fin 

            //Convertir cette intervalle en heure total
            $heureTotales = $intervalle->h + ($intervalle->i / 60);

            if($heureTotales > 8){
                //Cette parti de code permet de s'assurer que le nombre d'heure de travail ne dépasse pas 8h
                $this->addFlash('error', 'La duré total de travail dépasse 8h.');

                return $this->render('user/modifier_user.html.twig', [
                    'form' => $form->createView()
                ]);
            }
            $joursTravail = $user->getJourTravail($jour);

            $nombreJourTravail = count($joursTravail); //Cette fonction sers a compter le nombre de jour qui se trouve dans le tableau $jour

            if($nombreJourTravail > 5){
                //Cette parti du code permet de s'assurer que le nombre de jour ne dépasser pas 5 jour si sa dépasser un message s'affiche
                $this->addFlash('error', 'Le jour de travail total ne peut pas dépasser 5 jour');

                return $this->render('user/modifier_user.html.twig', [
                    'form' => $form->createView()
                ]);
            }
        $em->persist($user);
        $em->flush();

        $this->addFlash('success', "Employer modifier avec succès");

        return $this->redirectToRoute('employer');
      }
      return $this->render("user/modifier_user.html.twig", [
        "user" => $form->createView()
      ]);
    }
    #[Route("/{id}", name: "delete_user", methods:['POST'])]
    public function delete(int $id, User $user, EntityManagerInterface $entityManager, Request $request): Response
    {

        
        
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('employer', [], Response::HTTP_SEE_OTHER);
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

    #[Route("/afficher/{id}", name: "afficher_user", methods: ['GET'])]
    public function afficher(User $user): Response
    {
       
        return $this->render('user/afficher_user.html.twig', [
            'user' => $user
        ]);
    }
}
