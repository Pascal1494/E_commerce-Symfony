<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function homepage(ProductRepository $productRepository): Response
    {
        $product = $productRepository->findAll();
        dump($product);




        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}