<?php

namespace App\Controller;

use App\Classes\Cart;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OrderValidateController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/commande/success/{stripeSessionId}', name: 'success_paiment')]
    public function index(Cart $cart, $stripeSessionId): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

        if (!$order || $order->getUser() != $this->getUser()){
            return $this->redirectToRoute('home');
        }

        //modifier mon statut isPaid
        if (!$order->isIsPaid()){
            $order->setIsPaid(1);
            $this->entityManager->flush();
            //dd($order->isIsPaid());
            // vider le session cart
            $cart->remove();
        }

        // envoyer un mail à notre client pour lui confirmer la commande

        
        //afficher les queleques informations de la commande de l'utilisitateur

        //dd($order);
        return $this->render('order_validate/index.html.twig', [
            'order' => $order
        ]);
    }
}
