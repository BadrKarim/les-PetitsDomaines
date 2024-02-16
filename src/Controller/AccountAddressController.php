<?php

namespace App\Controller;

use App\Classes\Cart;
use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAddressController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/compte/adresses', name: 'app_account_address')]
    public function index(): Response
    {
        //dd($this->getUser());
        return $this->render('account/address.html.twig', [
            'controller_name' => 'AccountAddressController',
        ]);
    }

    #[Route('/compte/add/adresses', name: 'app_account_add_address')]
    public function add(Cart $cart, Request $request): Response
    {


        $address = new Address();
        $formAddress = $this->createForm(AddressType::class, $address);

        $formAddress->handleRequest($request);
    
        if ($formAddress->isSubmitted() && $formAddress->isValid()) {

            
            $address->setUser($this->getUser());
            
            $this->entityManager->persist($address);
            $this->entityManager->flush();
            //dd($address);
            if ($cart->get()) {
                return $this->redirectToRoute('app_order');
            }else {
                return $this->redirectToRoute('app_account_address');

            }

            
        }

        return $this->render('account/formAddress.html.twig', [
            'formAddress' => $formAddress->createView()
        ]);
    }

    #[Route('/compte/modifier-une-adresses/{id}', name: 'app_account_edit_address')]
    public function edit(Request $request, $id): Response
    {
        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);

        if (!$address || $address->getUser() != $this->getUser()) {
            return $this->redirectToRoute('app_account_address');
        }


        $formAddress = $this->createForm(AddressType::class, $address);
        $formAddress->handleRequest($request);
    
        if ($formAddress->isSubmitted() && $formAddress->isValid()) {
            $this->entityManager->flush();
            //dd($address);
            return $this->redirectToRoute('app_account_address');
            
        }

        return $this->render('account/formAddress.html.twig', [
            'formAddress' => $formAddress->createView()
        ]);
    }

    #[Route('/compte/supprimer-une-adresses/{id}', name: 'app_account_delete_address')]
    public function delete($id): Response
    {
        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);

        if ($address && $address->getUser() == $this->getUser()) {
            $this->entityManager->remove($address);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_account_address');
    }
    
}
