<?php

namespace App\Controller;

use App\Classes\Cart;
use App\Classes\MailJet;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SuccessController extends AbstractController
{
    private $entityManager;
    private $mailJet;

    public function __construct(EntityManagerInterface $entityManager, MailJet $mailJet)
    {
        $this->entityManager = $entityManager;
        $this->mailJet = $mailJet;
    }

    #[Route('/commande/success/{stripeSessionId}', name: 'success_paiment')]
    public function index(Cart $cart, $stripeSessionId): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

        if (!$order || $order->getUser() != $this->getUser()){
            return $this->redirectToRoute('home');
        }

        //modifier mon statut state
        if ($order->getState() == 0){
            $order->setState(1);
            $this->entityManager->flush();

            // vider le session cart
            $cart->remove();
        }

        // envoyer un mail à notre client pour lui confirmer la commande
        $this->mailJet->sendSuccessStripe($order->getUser()->getEmail(), $order->getUser()->getFirstname(), $order->getUser()->getLasname());
        
        //afficher les queleques informations de la commande de l'utilisitateur

        //dd($order);
        return $this->render('stripe/success.html.twig', [
            'order' => $order
        ]);
    }
}
