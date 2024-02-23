<?php

namespace App\Controller;

use App\Classes\Cart;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/mon-panier', name: 'cart')]
    public function index(Cart $cart): Response
    {
        // Récupérer les éléments complets du panier
        $cartComplete = $cart->getFull();
        //$cart->get()

        return $this->render('cart/index.html.twig', [
            'cart' => $cartComplete 
        ]);
    }

    #[Route('/mon-panier/add/{id}', name: 'add_cart')]
    public function add(Cart $cart, $id): Response
    {
        //dd($id);
        // Ajouter un produit au panier
        $cart->add($id);

        return $this->redirectToRoute('cart');
    }

    #[Route('/mon-panier/remove', name: 'remove_cart')]
    public function remove(Cart $cart): Response
    {
        // Retirer tous les produits du panier
        $cart->remove();

        return $this->redirectToRoute('products');
    }

    #[Route('/mon-panier/delete/{id}', name: 'delete_cart')]
    public function delete(Cart $cart, $id): Response
    {
        // Retirer un produit du panier
        $cart->delete($id);

        return $this->redirectToRoute('cart');
    }

    #[Route('/mon-panier/decrease/{id}', name: 'decrease_cart')]
    public function decrease(Cart $cart, $id): Response
    {
        // Soustraire un produit du panier
        $cart->decrease($id);

        return $this->redirectToRoute('cart');
    }
}
