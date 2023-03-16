<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

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

        if (!$product) {
            throw $this->createNotFoundException("Le produit demandé n'existe pas !");
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
            // 'urlGenerator' => $urlGenerator
        ]);
    }

    #[Route('/admin/product/create', name: 'product_create')]
    public function create(FormFactoryInterface $factory, Request $request, SluggerInterface $slugger, EntityManagerInterface $em)
    {
        // dd($request);
        $builder = $factory->createBuilder(FormType::class, null, [
            'data_class' => Product::class
        ]);
        $builder->add('name', TextType::class, [
            'label' => 'Le nom du produit',
            'attr' => ['placeholder' => 'Tapez le nom de votre produit']
        ])
            ->add('shortDescription', TextareaType::class, [
                'label' => 'La description rapide du produit',
                'attr' => ['placeholder' => 'Tapez une brève decription de votre produit mais qui envoie du steack !']
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Le prix de vente de votre produit',
                'attr' => ['placeholder' => 'Tapez votre prix de vente de votre produit TTC et en €']
            ])
            ->add('mainPicture', UrlType::class, [
                'label' => 'L\'image de votre produit',
                'attr' => ['placeholder' => 'Sauvegarder l\'image de votre produit']
            ])
            ->add('category', EntityType::class, [
                'label' => 'La catégorie de votre produit',
                'attr' => [],
                'placeholder' => '-- Choisir la catégorie de votre produit --',
                'class' => Category::class,
                'choice_label' => function (Category $category) {
                    return strtoupper($category->getName());
                }

            ]);
        $form = $builder->getForm();

        $form->handleRequest($request);
        // $data = $form->getData();
        // dd($data);
        if (
            $form->isSubmitted() && $form->isValid()
        ) {
            $product = $form->getData();
            $product->setSlug(strtolower($slugger->slug($product->getName())));

            $em->persist($product);
            $em->flush();
            // dd($product);
        }

        $formView = $form->createView();

        // dd($form);

        return $this->render('product/create.html.twig', [
            'formView' => $formView
        ]);
    }
}
