<?php

namespace App\Controller;

use App\Classes\Cart;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/commande', name: 'order')]
    public function index(Cart $cart): Response
    {
        //dd($this->getUser()->getAddresses()->getValues());
        // si user n'a pas d'adresse redirige vers aad address
        if (!$this->getUser()->getAddresses()->getValues()) {
            return $this->redirectToRoute('app_account_add_address');
        }

        // si le panier est vide ne pas acceder à cette page
        // if (!$this->getUser()->getAddresses()->getValues()) {
        //     return $this->redirectToRoute('app_account_add_address');
        // }

        $formOrder = $this->createForm(OrderType::class, null, [
            // deuxiéme papram null du createForm, car OrederType n'est pas lié à une class
            // pour que mon form n'envoie que les adresse liés à un user
            'user' => $this->getUser()
        ]);

        return $this->render('order/index.html.twig', [
            'formOrder' => $formOrder->createView(),
            'cart' => $cart->getFull()
        ]);
    }

    #[Route('/commande/recap', name: 'recap_order', methods: "POST")]
    public function add(Cart $cart, Request $request): Response
    {

        
        $formOrder = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        $formOrder->handleRequest($request);


        if ($formOrder->isSubmitted() && $formOrder->isValid()) {

            $dateImmutable = new \DateTime();
            $dateImmutable = \DateTimeImmutable::createFromMutable($dateImmutable);
            $carriers = $formOrder->get('carrier')->getData();
            //dd($carriers);
            $delivery = $formOrder->get('addresses')->getData();
            //dd($delivery);
            $delivry_content = $delivery->getFirstname(). ' ' .$delivery->getLastname();
            $delivry_content .= '<br/>'.$delivery->getPhone();

            if ($delivery->getCompany()) {
                $delivry_content .= '<br/>'.$delivery->getCompany();
            }

            $delivry_content .= '<br/>' .$delivery->getAddress();
            $delivry_content .= '<br/>' .$delivery->getPostal(). ' ' .$delivery->getCity();
            $delivry_content .= '<br/>' .$delivery->getCountry();

            
            // enregistrer ma commande Order()
            $order = new Order();
            $order->setUser($this->getUser());
            $order->setCreatedAt($dateImmutable);
            $order->setCarrierName($carriers->getName());
            $order->setCarrierPrice($carriers->getPrice());
            $order->setDelivery($delivry_content);
            $order->setIsPaid(0);

            $this->entityManager->persist($order);

            // enregistrer mes produits OrderDetails()
            foreach ($cart->getFull() as $product) {
                //dd($product);
                $orderDetails = new OrderDetails();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($product['product']->getName());
                $orderDetails->setQuantity($product['quantity']);
                $orderDetails->setPrice($product['product']->getPrice());
                $orderDetails->setTotal($product['product']->getPrice() * $product['quantity']);
                $this->entityManager->persist($orderDetails);
            }

            //$this->entityManager->flush();
            
            return $this->render('order/add.html.twig', [
                'cart' => $cart->getFull(),
                'carrier' => $carriers,
                'delivery' => $delivry_content
            ]);
        }

        //return $this->redirectToRoute('cart');

    }
}
