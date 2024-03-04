<?php

namespace App\Controller;



use App\Entity\Order;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class StripeController extends AbstractController
{
    #[Route('/commande/stripe-session/{reference}', name: 'stripe_session')]
    public function index(EntityManagerInterface $entityManager, $reference)
    {

        Stripe::setApiKey('sk_test_51NsJ1YB9YDYnIaBceYeSmiAIQx8MrlK34daKJGDdv4UFAyJxqmoViFA20cnhtf0laDWRat2tH1oaZV4Kpu5LT34l00KFSupmqz');
        Stripe::setApiVersion('2023-10-16');
        // integration de stripe
        $products_stripe = [];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        $order = $entityManager->getRepository(Order::class)->findOneByReference($reference);
        //dd($order);
        // dd($order->getOrderDetails()->getValues());

        if (!$order){
            new JsonResponse(['error' => 'order']);
        }

        // parcourir les élément de cart
        foreach ($order->getOrderDetails()->getValues() as $orderDetails){
            //dd($orderDetails);
            $product = $entityManager->getRepository(Product::class)->findOneByName($orderDetails->getProduct());
            //dd($product);
            $products_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $product->getName(),
                        'images' => [$YOUR_DOMAIN."/illustrations/".$product->getIllustration()],
                    ],
                  'unit_amount' => $product->getPrice(),
                ],
                'quantity' => $orderDetails->getQuantity(),
            ];
            

            
        }

            $products_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $order->getCarrierName(),
                        'images' => [$YOUR_DOMAIN],
                    ],
                  'unit_amount' => $order->getCarrierPrice()
                ],
                'quantity' => 1,
            ];
        
           //dd($products_stripe);
        
        // clé API stripe

        // transmettre les contenues à facturer
        $checkout_session = Session::create([
        'payment_method_types' => ['card'],
        'customer_email' => $this->getUser()->getEmail(),
        'line_items' => $products_stripe,
        'mode' => 'payment',
        'success_url' => $YOUR_DOMAIN.'/commande/success/{CHECKOUT_SESSION_ID}',
        'cancel_url' => $YOUR_DOMAIN.'/commande/cancel/{CHECKOUT_SESSION_ID}',
        ]);
        //dd($order->setStripeSessionId($checkout_session->id));
        $order->setStripeSessionId($checkout_session->id);
        
        $entityManager->flush();
        //dd($checkout_session->id);
        //dd($checkout_session->id);
        //dd($checkout_session);

        $response = new JsonResponse(['id' => $checkout_session->id]);



        return $response;
    }
}
