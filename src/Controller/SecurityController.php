<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/connexion', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils, TranslatorInterface $translator): Response
    {
        // obtenir l’erreur de connexion s’il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();

        // atteindre le message d'erreur et le traduire
        if ($error !== null ){
            $errorMessage = $translator->trans($error->getMessageKey(), $error->getMessageData(), 'security');
            $this->addFlash('danger', $errorMessage);
        }
        
        // Dernier nom d’utilisateur saisi par l’utilisateur dans notre cas le mail
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername]);
    }

    #[Route(path: '/deconnexion', name: 'logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
