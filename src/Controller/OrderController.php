<?php

namespace App\Controller;

use App\Classes\Cart;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
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
        if (!$this->getUser()->getAddresses()->getValues()) {
            return $this->redirectToRoute('add_address');
        }

        $formOrder = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        return $this->render('order/index.html.twig', [
            'formOrder' => $formOrder->createView(),
            'cart' => $cart->getFull()
        ]);
    }

    #[Route('/commande/save', name: 'order_save', methods: ["POST"])]
    public function save(Cart $cart, Request $request, RequestStack $requestStack): Response
    {
        $formOrder = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        $formOrder->handleRequest($request);

        if ($formOrder->isSubmitted() && $formOrder->isValid()) {
            $dateImmutable = new \DateTime();
            $dateImmutable = \DateTimeImmutable::createFromMutable($dateImmutable);
            $carriers = $formOrder->get('carrier')->getData();
            $delivery = $formOrder->get('addresses')->getData();
            $delivery_content = $delivery->getFirstname() . ' ' . $delivery->getLastname();
            $delivery_content .= '<br/>' . $delivery->getPhone();

            if ($delivery->getCompany()) {
                $delivery_content .= '<br/>' . $delivery->getCompany();
            }

            $delivery_content .= '<br/>' . $delivery->getAddress();
            $delivery_content .= '<br/>' . $delivery->getPostal() . ' ' . $delivery->getCity();
            $delivery_content .= '<br/>' . $delivery->getCountry();

            //Enregistrer ma commande Order()
            $order = new Order();
            $order->setUser($this->getUser());
            $order->setCreatedAt($dateImmutable);
            $order->setCarrierName($carriers->getName());
            $order->setCarrierPrice($carriers->getPrice());
            $order->setDelivery($delivery_content);
            $order->setState(0);
            $reference = $dateImmutable->format('d-m-Y') . '--' . uniqid();
            $order->setReference($reference);
            $this->entityManager->persist($order);

            //Enregistrer mon entity OrderDetails
            foreach ($cart->getFull() as $product) {
                $orderDetails = new OrderDetails;
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($product['product']->getName());
                $orderDetails->setQuantity($product['quantity']);
                $orderDetails->setPrice($product['product']->getPrice());
                $orderDetails->setTotal($product['product']->getPrice() * $product['quantity']);
                $this->entityManager->persist($orderDetails);
            }

            $this->entityManager->flush();

            $session = $requestStack->getSession();
            $session->set('orderId', $order->getId());

            return $this->redirectToRoute('order_recap', array('cart' => $cart));

        }else {

            return $this->redirectToRoute('cart');
        }
    }

    #[Route('/commande/recap', name: 'order_recap')]
    public function orderrecap(Cart $cart, RequestStack $requestStack) :Response
    {
        $session = $requestStack->getSession();
        $orderId = $session->get('orderId');

        $order = $this->entityManager->getRepository(Order::class)->findOneById($orderId);
        $reference = $order->getReference();
       
        return $this->render('order/orderRecap.html.twig', [
            'cart' => $cart->getFull(),
            'order' => $order,
            'reference' => $reference
        ]);
    }
}
