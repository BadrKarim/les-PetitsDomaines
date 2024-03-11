<?php

namespace App\Controller;

use App\Entity\Boutique;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NotreConceptController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/notre-concept', name: 'notre_concept')]
    public function index(): Response
    {
        $boutique = $this->entityManager->getRepository(Boutique::class)->findAll();

        return $this->render('notre_concept/index.html.twig', [
            'boutiques' => $boutique
        ]);
    }
}
