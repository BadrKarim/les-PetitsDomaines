<?php

namespace App\Controller;

use App\Classes\MailJet;
use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


class RegisterController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/inscription', name: 'register')]
    public function index(Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $notification = null;

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            //dd($password);

            // recherche du mail existant
            $search_email = $this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());

            if (!$search_email){

                $password = $hasher->hashPassword($user, $user->getPassword());
                $user->setPassword($password);
                //dd($password);

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $mail = new MailJet();
                $content = "Votre inscription s'est bien déroulée";
                $mail->send($user->getEmail(), $user->getFirstname(), 'Bienvenue sur Les Petis Domaines', $content);

                $notification = "Votre inscription s'est correctement déroulée, Vous pouvez dès à présent vous connecter à votre compte";

            }else {
                //throw $this->createNotFoundException('The product does not exist');

                $notification = "L'email que vous avez renseigné, existe déjà.";

            }

            return $this->redirectToRoute('login');
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}
