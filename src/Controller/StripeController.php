<?php

namespace App\Controller;

use App\Classes\Cart;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class StripeController extends AbstractController
{
    #[Route('/commande/stripe', name: 'stripe_session')]
    public function index(Cart $cart)
    {
        // integration de stripe
        $products_stripe = [];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000/';

        // parcourir les élément de cart
        foreach ($cart->getFull() as $product){

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
        Stripe::setApiKey('sk_test_51NsJ1YB9YDYnIaBceYeSmiAIQx8MrlK34daKJGDdv4UFAyJxqmoViFA20cnhtf0laDWRat2tH1oaZV4Kpu5LT34l00KFSupmqz');

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
