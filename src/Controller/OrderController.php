<?php

namespace App\Controller;

use App\Classes\Cart;
use App\Entity\Carrier;
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
        //dd($this->getUser()->getAddresses()->getValues());
        // si user n'a pas d'adresse
        if (!$this->getUser()->getAddresses()->getValues()) {

            return $this->redirectToRoute('add_address');
        }

        // createForm attend un deuxieme param lié à l'instance d'une entité
        // OrderType n'est pas lié à une instance
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
        //dd($request);

        if ($formOrder->isSubmitted() && $formOrder->isValid()) {
            //dd($formOrder->getData());

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
            $order->setIsPaid(0);
            $reference = $dateImmutable->format('d-m-Y') . '--' . uniqid();
            $order->setReference($reference);
            $this->entityManager->persist($order);

            //Enregistrer mon entity OrderDetails
            foreach ($cart->getFull() as $product) {
                //dd($product);
                $orderDetails = new OrderDetails;
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($product['product']->getName());
                $orderDetails->setQuantity($product['quantity']);
                $orderDetails->setPrice($product['product']->getPrice());
                $orderDetails->setTotal($product['product']->getPrice() * $product['quantity']);
                $this->entityManager->persist($orderDetails);
            }

            $this->entityManager->flush();
            //dd($order);

            //Declarer la session
            $session = $requestStack->getSession();
            //Mettre le order ID dans la session
            $session->set('orderId', $order->getId());

            //Redirection vers l'action orderrecap
            return $this->redirectToRoute('order_recap', array('cart' => $cart));

        }else {

            return $this->redirectToRoute('cart');
        }
    }

    #[Route('/commande/recap', name: 'order_recap')]
    public function orderrecap(Cart $cart, RequestStack $requestStack) :Response
    {
        //Declarer la session
        $session = $requestStack->getSession();
        //Obtenir l'id order crée et mis en session precedement
        $orderId = $session->get('orderId');

        //Charger le ordfer par son id depuis la BD
        $order = $this->entityManager->getRepository(Order::class)->findOneById($orderId);
        $reference = $order->getReference();
        //dd($order);

        //$products_in_order = $order->getOrderDetails()->getValues();
        //Instancier et remplir les propriétés de l'objet Carrier car dans la view c'est appelé ainsi : Objet.Propriété
        // $carrier = new Carrier();
        // $carrier->setPrice($order->getCarrierPrice());
        // $carrier->setName($order->getCarrierName());
       
        return $this->render('order/orderRecap.html.twig', [
            'cart' => $cart->getFull(),
            //'carrier' => $carrier,
            'order' => $order,
            'reference' => $reference
            //'products_in_order' => $products_in_order
            //'delivery' =>  $order->getDelivery(),
            //'reference' => $order->getReference(),
        ]);
    }
}
