<?php

namespace App\Controller;

use App\Classes\MailJet;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CancelController extends AbstractController

{
    private $entityManager;
    private $mailJet;

    public function __construct(EntityManagerInterface $entityManager, MailJet $mailJet)
    {
        $this->entityManager = $entityManager;
        $this->mailJet = $mailJet;
    }

    #[Route('/commande/cancel/{stripeSessionId}', name: 'cancel_paiment')]
    public function index($stripeSessionId): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);
        dd($order);

        if (!$order || $order->getUser() != $this->getUser()){
            return $this->redirectToRoute('home');
        }

        // envoyer un mail à notre client pour lui confirmer la commande
        $this->mailJet->sendSuccessStripe($order->getUser()->getEmail(), $order->getUser()->getFirstname(), $order->getUser()->getLasname());
        
        return $this->render('stripe/cancel.html.twig',[
            'order' => $order
        ]);
    }
}
