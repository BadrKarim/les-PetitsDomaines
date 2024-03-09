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


class AccountRegisterController extends AbstractController
{
    private $entityManager;
    private $mailJet;

    public function __construct(EntityManagerInterface $entityManager, MailJet $mailJet)
    {
        $this->entityManager = $entityManager;
        $this->mailJet = $mailJet;
    }

    #[Route('/inscription', name: 'register')]
    public function index(Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $user = new User();
        $formRegister = $this->createForm(RegisterType::class, $user);

        $formRegister->handleRequest($request);

        if ($formRegister->isSubmitted() && $formRegister->isValid()){
            $user = $formRegister->getData();

            // recherche du mail existant
            $search_email = $this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());

            if (!$search_email){

                $password = $hasher->hashPassword($user, $user->getPassword());
                $user->setPassword($password);

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $this->mailJet->sendRegister($user->getEmail(), $user->getlasname(), $user->getFirstname());

                $this->addFlash('success', "Votre inscription s'est bien déroulée vous allez recevoir un email pour activer votre compte");
                
            }else {

                $this->addFlash('secondary', 'Votre email existe déjàs, vous pouvez vous connecter à votre compte');
            }

            return $this->redirectToRoute('login');
        }

        return $this->render('account/register.html.twig', [
            'formRegister' => $formRegister->createView()
        ]);
    }
}
