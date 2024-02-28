<?php

namespace App\Controller;

use App\Classes\MailJet;
use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ResetPasswordType;
use DateInterval;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class ResetPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('mot-de-passe-oublie', name: 'password_reset')]
    public function index(Request $request): Response
    {
        if ($this->getUser()){

            return $this->redirectToRoute('home');
        }

        if ($request->get('email')){

            $user = $this->entityManager->getRepository(User::class)->findOneByEmail($request->get('email'));

            if ($user){
                $dateImmutable = new DateTime();
                $dateImmutable = DateTimeImmutable::createFromMutable($dateImmutable);

                // enregistrer en base de donnée la demande de resetpassword
                $reset_password = new ResetPassword();
                $reset_password->setUser($user);
                $reset_password->setToken(uniqid());
                $reset_password->setCreatedAt($dateImmutable);
                $this->entityManager->persist($reset_password);
                $this->entityManager->flush();

                // envoyer un mail avec un lien
                $url = $this->generateUrl('password_reset_update', [
                    'token' => $reset_password->getToken()]);
                $content = "Bonjour ".$user->getFirstname()."<br cliquer sur le lien pour renouveler votre mot de passe <a href='".$url."'></a>";
                $mail = new MailJet();
                $mail->send($user->getEmail(), $user->getFirstname(). ' ' . $user->getLasname(), 'Réinitialiser votre mots de passe Les Petits Domaine', $content);
            
                //return $this->redirectToRoute('login');

                }else {
                    $this->addFlash('notice', 'Vous allez recevoir un mail');
                }
        }else {
            $this->addFlash('notice', 'Cette adresse mail est inconnu');
        }

        return $this->render('reset_password/index.html.twig');
    }

    #[Route('mot-de-passe-oublie/{token}', name: 'password_reset_update')]
    public function update($token, Request $request, UserPasswordHasherInterface $hasher) :Response
    {

        $reset_password = $this->entityManager->getRepository(ResetPassword::class)->findOneByToken($token);

        // verifier que le token existe en BD
        if (!$reset_password){

            return $this->redirectToRoute('password_reset');
        }

        // verifier que le createdAt est encore valide
        $dateImmutable = new DateTime();
        $dateImmutable = $dateImmutable->add(new DateInterval('PT1H'));

        $currentDate = new \DateTime();
        if ($currentDate > $reset_password->getCreatedAt()->add(new DateInterval('PT1H'))){
            //dd($reset_password->getCreatedAt()->add(new DateInterval('PT1H')));
            $this->addFlash('notice', 'Votre demande à expiré');
           return $this->redirectToRoute('password_reset');
        }

        // rendre une vue avec mot de passe et confirmez votre mot de passe
        $formResetPassword = $this->createForm(ResetPasswordType::class);
        $formResetPassword->handleRequest($request);

        if ($formResetPassword->isSubmitted() && $formResetPassword->isValid()){
                //encodage des mots de passes
                $new_pwd = $formResetPassword->get('new_password')->getData();
                //dd($new_pwd);
                $password = $hasher->hashPassword($reset_password->getUser(), $new_pwd);
                $reset_password->getUser()->setPassword($password);
                //flush en DB
                $this->entityManager->flush();
        }
        
        //redirection ver login
        $this->addFlash('notice', 'votre mots de passe a bien été modifié');
        return $this->redirectToRoute('login');


        return $this->render('reset_password/update.html.twig', [
            'formResetPassword' => $formResetPassword->createView()
        ]);




    }


}
