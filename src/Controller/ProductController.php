<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductController extends AbstractController
{
    #[Route('/{slug}', name: 'product_category')]
    public function category($slug, CategoryRepository $categoryRepository)
    {
        $category = $categoryRepository->findOneBy([
            'slug' => $slug
        ]);

        if (!$category) {
            throw $this->createNotFoundException("La catégorie demandée n'existe pas !");
        }


        return $this->render('product/category.html.twig', [
            'slug' => $slug,
            'category' => $category
        ]);
    }

    #[Route('/{category_slug}/{slug}', name: 'product_show')]
    public function show($slug, ProductRepository $productRepository, UrlGeneratorInterface $urlGenerator)
    {
        // dd($urlGenerator->generate('product_category', [
        //     'slug' => $slug
        // ]));
        $product = $productRepository->findOneBy([
            'slug' => $slug
        ]);

        // if (!$product) {
        //     throw $this->createNotFoundException("Le produit demandé n'existe pas !");
        // }

        return $this->render('product/show.html.twig', [
            'product' => $product,
            // 'urlGenerator' => $urlGenerator
        ]);
    }

    #[Route('/admin/product/{id}/edit', name: 'product_edit')]
    public function edit($id, ProductRepository $productRepository, Request $request, EntityManagerInterface $em, SluggerInterface $slug, ValidatorInterface $validator)
    {
        $product = new Product;
        // $resultat = $validator->validate($product);

        // if ($resultat->count() > 0) {
        //     dd("Il y a des erreurs", $resultat);
        // }
        //     dd("Tout va bien", $resultat);


        $product = $productRepository->find($id);

        $form = $this->createForm(ProductType::class, $product);

        // $form->setData($product);

        $form->handleRequest($request);

        if (
            $form->isSubmitted() && $form->isValid()
        ) {
            // dd($form->getData());
            // $product = $form->getData();
            $product->setSlug(strtolower($slug->slug($product->getName())));


            $em->flush();
            // dd($product);

            return $this->redirectToRoute('product_show', [
                'category_slug' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug()
            ]);
        }


        $formView = $form->createView();


        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'formView' => $formView

        ]);

        return $this->render('product/edit.html.twig');
    }

    #[Route('/admin/product/create', name: 'product_create')]
    public function create(Request $request, SluggerInterface $slugger, EntityManagerInterface $em)
    {
        // dd($request);
        $product = new Product;
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        // $data = $form->getData();
        // dd($data);
        if (
            $form->isSubmitted() && $form->isValid()
        ) {
            // $product = $form->getData();
            $product->setSlug(strtolower($slugger->slug($product->getName())));

            $em->persist($product);
            $em->flush();
            // dd($product);
            return $this->redirectToRoute('product_show', [
                'category_slug' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug()
            ]);
        }

        $formView = $form->createView();

        // dd($form);

        return $this->render('product/create.html.twig', [
            'formView' => $formView
        ]);
    }
}