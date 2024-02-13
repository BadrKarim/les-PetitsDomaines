<?php

namespace App\Controller;

use App\Classes\Cart;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/mon-panier', name: 'app_cart')]
    public function index(Cart $cart): Response
    {
        //dd($cart->get());
        // Assurez-vous que la méthode get() de la classe Cart retourne toujours un tableau valide
        $cartItems = $cart->get();
        if ($cartItems === null) {
            $cartItems = [];
        }

        $cartComplete = [];
        foreach($cartItems as $id => $quantity){
            // Vérifiez si l'identifiant est valide
            //dd($id)
            $product = $this->entityManager->getRepository(Product::class)->findOneById($id);
            if ($product !== null) {
                $cartComplete[] = [
                    'product' => $product,
                    'quantity' => $quantity
                ];
            }
            // $cartComplete[] = [
            //     'product' => $this->entityManager->getRepository(Product::class)->findOneById($id),
            //     'quantity' => $quantity
            // ];
        }
        //dd($cartComplete);

        return $this->render('cart/index.html.twig', [
            'cart' => $cartComplete //$cart->get()
        ]);
    }

    #[Route('/cart/add/{id}', name: 'app_addCart')]
    public function add(Cart $cart, $id): Response
    {
        //dd($id);
        // Assurez-vous que l'identifiant passé à la méthode add() est valide
        // Ajoutez un produit au panier
        $cart->add($id);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/remove', name: 'app_remove_my_cart')]
    public function remove(Cart $cart): Response
    {
        // Retirez tous les produits du panier
        $cart->remove();

        return $this->redirectToRoute('app_products');
    }
}
