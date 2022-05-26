<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\SearchType;
use App\Search\Search;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/nos-produits", name="products")
     */
    public function index(Search $search,EntityManagerInterface $entityManager, Request $request): Response
    {
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $products = $entityManager->getRepository(Product::class)->FindWithSearch($search);

        }else{
            $products = $entityManager->getRepository(Product::class)->findAll();
        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/produit/{slug}", name="product")
     */
    public function show(EntityManagerInterface $entityManager ,$slug): Response
    {
        $product = $entityManager->getRepository(Product::class)->findOneBySlug($slug);

        if (!$product){
            return $this->redirectToRoute('products');
        }
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }
}
