<?php
namespace App\Classes;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
    private $requestStack;
    private $entityManager;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        $this->requestStack = $requestStack;
        $this->entityManager = $entityManager;
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

        $session->set('cart', $cart);
    }

    public function get()
    {
        //dd($cart->get());
        $session = $this->requestStack->getSession();

        return $session->get('cart', []);
    }

    public function remove()
    {
        $session = $this->requestStack->getSession();
        return $session->remove('cart');
    }

    public function delete($id)
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);

        // Vérifier si le produit existe dans le panier
        if (isset($cart[$id])) {
            // Supprimer le produit du panier
            unset($cart[$id]);
            // Mettre à jour le panier en session
            $session->set('cart', $cart);
        }

        return $cart;
    }

    public function decrease($id)
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);

        //vérifier si la quantité du produit est suppérieur à 1 et cart n'est pas null
        if (isset($cart[$id]) && $cart[$id] > 1) {
            $cart[$id]--;
            $session->set('cart', $cart);

        //Si la quantité du produit est strictement égale à 1 et cart n'est pas null supprimer le produit du panier
        } elseif (isset($cart[$id]) && $cart[$id] === 1) {
            unset($cart[$id]);
            $session->set('cart', $cart);
        }

        return $cart;
    }

    public function getFull()
    {
        $cartItems = $this->get();
        $cartComplete = [];
    
        // Vérifiez si le panier n'est pas vide
        if ($cartItems) {

            foreach ($cartItems as $id => $quantity) {
                // Recherchez le produit correspondant à l'ID dans la base de données
                $product = $this->entityManager->getRepository(Product::class)->find($id);

                if ($product) {
                    $cartComplete[] = [
                        'product' => $product,
                        'quantity' => $quantity
                    ];
                }else {
                    // Si le produit n'existe pas, supprimez-le du panier
                    $this->delete($id);
                }
            }
        }

        return $cartComplete;
    }
}

