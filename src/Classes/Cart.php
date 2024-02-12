<?php
namespace App\Classes;

//RequestStack a remplacé sessionInterface depuis la version 5 pour ces raison :
//-Session est un objet de données (par exemple, comme l’objet Request), il ne devrait donc pas y avoir de un service défini pour lui dans le conteneur ;
//-Les sessions ne font pas partie de la spécification HTTP (HTTP/1.1, HTTP/2 ou HTTP/3) car HTTP n’a pas d’état. C’est pourquoi il est étrange à manipuler dans le cadre du composant HttpFoundation.

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
        $cart = $session->get('cart', [
            'id' => $id,
            'quantity' => 1
        ]);

        if(!empty($cart[$id]))
        {
            $cart[$id]++;
        }else {
            $cart[$id] = 1;
        }
            
        

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
        return $session->remove('cart', []);
    }
}

