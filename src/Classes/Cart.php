<?php
namespace App\Classes;

use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function add($id)
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);
        if (isset($cart[$id])) {
            //Si le produit existe déjà dans le panier, incrémente la quantité
            $cart[$id]++;
        }else {
            // Sinon, ajoute le produit au panier avec une quantité de 1
            $cart[$id] = 1;
        }

        // $cart = $session->get('cart', [
        //     'id' => $id,
        //     'quantity' => 1
        // ]);

        // if(!empty($cart[$id]))
        // {
        //     $cart[$id]++;
        // }else {
        //     $cart[$id] = 1;
        // }
            
        $session->set('cart', $cart);
        

    }

    public function get()
    {
        $session = $this->requestStack->getSession();
        return $session->get('cart', []);
    }

    public function remove()
    {
        $session = $this->requestStack->getSession();
        return $session->remove('cart');
    }
}

