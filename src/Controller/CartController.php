<?php

namespace App\Controller;

use App\Classes\Cart;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{


    #[Route('/mon-panier', name: 'app_cart')]
    public function index(Cart $cart): Response
    {
        // Récupérer les éléments complets du panier
        $cartComplete = $cart->getFull();
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

    #[Route('/cart/delete/{id}', name: 'app_delete_to_cart')]
    public function delete(Cart $cart, $id): Response
    {
        // Retirez un produit du panier
        $cart->delete($id);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/decrease/{id}', name: 'app_decrease_to_cart')]
    public function decrease(Cart $cart, $id): Response
    {
        
        $cart->decrease($id);

        return $this->redirectToRoute('app_cart');
    }
}
