<?php

namespace App\Controller;

use App\Form\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/commande', name: 'app_order')]
    public function index(): Response
    {
        //dd($this->getUser()->getAddresses()->getValues());
        if (!$this->getUser()->getAddresses()->getValues()) {
            return $this->redirectToRoute('app_account_add_address');
        }

        $formOrder = $this->createForm(OrderType::class, null, [
            // deuxiéme papram null du createForm, car OrederType n'est pas lié à une class
            // pour que mon form n'envoie que les adresse liés à un user
            'user' => $this->getUser()
        ]);
        return $this->render('order/index.html.twig', [
            'formOrder' => $formOrder->createView(),
        ]);
    }
}
