<?php

namespace App\Controller;

use App\Classes\Cart;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class StripeController extends AbstractController
{
    #[Route('/commande/stripe/{ reference }', name: 'stripe_session')]
    public function index(Cart $cart, EntityManagerInterface $entityManager, $reference)
    {
        // integration de stripe
        $products_stripe = [];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000/';

        $order = $entityManager->getRepository(Order::class)->findOneByReference($reference);
        //dd($order);
        //dd($order->getOrderDetails()->getValues());

        if (!$order){
            new JsonResponse(['error' => 'order']);
        }

        // parcourir les élément de cart
        foreach ($order->getOrderDetails()->getValues() as $product){
            //dd($product);
            $products_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $product['product']->getName(),
                        'images' => [$YOUR_DOMAIN."illustrations/".$product['product']->getIllustration()],
                    ],
                  'unit_amount' => $product['product']->getPrice(),
                ],
                'quantity' => $product['quantity'],
            ];

        }

        // clé API stripe
        Stripe::setApiKey('');

        // transmettre les contenues à facturer
        $checkout_session = Session::create([
        'line_items' => [
            $products_stripe
        ],
        'mode' => 'payment',
        'success_url' => $YOUR_DOMAIN.'/success',
        'cancel_url' => $YOUR_DOMAIN.'/cancel',
        ]);

        //dd($checkout_session->id);
        //dd($checkout_session);

        $response = new JsonResponse(['id' => $checkout_session->id]);

        return $response;
    }
}
