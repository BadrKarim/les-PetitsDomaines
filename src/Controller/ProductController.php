<?php

namespace App\Controller;

use App\Classes\Search;
use App\Entity\Product;
use App\Form\SearchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/nos-produits', name: 'app_products')]
    public function index(Request $request): Response
    {

        $search = new Search();
        $formSearch = $this->createForm(SearchType::class, $search);

        $formSearch->handleRequest($request);
        if($formSearch->isSubmitted() && $formSearch->isValid())
        {
            //dd($search);
            $products = $this->entityManager->getRepository(Product::class)->findWithSearch($search);
        }else {
            $products = $this->entityManager->getRepository(Product::class)->findAll();
        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'formSearch' => $formSearch->createView()
        ]);
    }
    
    #[Route('/produit/{slug}', name: 'app_product')]
    public function show($slug): Response
    {
        //dd($slug);
        $product = $this->entityManager->getRepository(Product::class)->findOneBySlug($slug);

        if (!$product) {
            return $this->redirectToRoute( 'products' );
        }

        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }
}
