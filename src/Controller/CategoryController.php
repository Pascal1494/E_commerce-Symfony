<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryController extends AbstractController
{
    #[Route('/admin/category/create', name: 'category_create')]
    public function create(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {

        // dd($request);
        $category = new Category;
        $form = $this->createForm(CategoryType::class, $category);
        // dd($category);

        $form->handleRequest($request);

        if (
            $form->isSubmitted() && $form->isValid()
        ) {
            $category->setSlug(strtolower($slugger->slug($category->getName())));

            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }
        $formView = $form->createView();

        return $this->render('category/create.html.twig', [
            'formView' => $formView

        ]);
    }

    #[Route('/admin/category/{id}/edit', name: 'category_edit')]
    public function edit($id, CategoryRepository $categoryRepository, Request $request, EntityManagerInterface $em, SluggerInterface $slug): Response
    {
        $category = $categoryRepository->find($id);

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if (
            $form->isSubmitted() && $form->isValid()
        ) {
            // $product = $form->getData();
            $category->setSlug(strtolower($slug->slug($category->getName())));


            $em->flush();
            // dd($product);

            return $this->redirectToRoute('homepage');
        }

        $formView = $form->createView();


        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'formView' => $formView

        ]);
    }
}
