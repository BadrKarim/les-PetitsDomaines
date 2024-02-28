<?php

namespace App\Controller;

use App\Classes\MailJet;
use App\Entity\ResetPassword;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
            //dd($request->get('email'));
            $user = $this->entityManager->getRepository(User::class)->findOneByEmail($request->get('email'));
            //dd($user);

            if ($user){
                $dateImmutable = new \DateTime();
                $dateImmutable = \DateTimeImmutable::createFromMutable($dateImmutable);

                // enregistrer en base de donnée la demande de resetpassword
                $reset_password = new ResetPassword();
                $reset_password->setUser($user);
                $reset_password->setToken(uniqid());
                $reset_password->setCreatedAt($dateImmutable);
                $this->entityManager->persist($reset_password);
                $this->entityManager->flush();

                // envoyer un mail avec un lien 
                $content = "Bonjour ".$user->getFirstename()."<br";
                $mail = new MailJet();
                $mail->send($user->getEmail(), $user->getFirstename(). ' ' . $user->getLasname(), 'Réinitialiser votre mots de passe Les Petits Domaine', $content);
            }
        }

        return $this->render('reset_password/index.html.twig');
    }

    #[Route('mot-de-passe-oublie/{token}', name: 'password_reset_update')]
    public function update($token)
    {
        dd($token);
    }


}
