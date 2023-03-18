<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\DataTransformer\CentimesTransformer;
use phpDocumentor\Reflection\PseudoTypes\False_;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TypeTextType::class, [
            'label' =>  'Le nom du produit',
            'attr' => ['placeholder' => 'Tapez le nom de votre produit'],
            'required' => False,
            'constraints' => new NotBlank(['message' => "Validation du formulaire: le nom du produit ne peut pas être vide !"])
        ])
            ->add('shortDescription', TextareaType::class, [
                'label' => 'La description rapide du produit',
                'attr' => ['placeholder' => 'Tapez une brève decription de votre produit mais qui envoie du steack !']
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Le prix de vente de votre produit',
                'attr' => ['placeholder' => 'Tapez votre prix de vente de votre produit TTC et en €'],
                'divisor' => 100,
                'required' => False,
                'constraints' => new NotBlank(['message' => "Validation du formulaire: le prix du produit ne peut pas être vide !"])
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

        // $builder->get('price')->addModelTransformer(new CentimesTransformer);

        // $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
        //     $product = $event->getData();

        //     if ($product->getPrice() !== null) {
        //         $product->setPrice($product->getPrice() * 100);
        //     }
        //     // dd($product);
        // });

        // $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
        //     $form = $event->getForm();

        //     /**@var Product*/
        //     $product = $event->getData();

        //     if (
        //         $product->getPrice() !== null
        //     ) {
        //         $product->setPrice($product->getPrice() / 100);
        //     }
        // dd($product);

        // if ($product->getId() === null) {
        //     $form->add('category', EntityType::class, [
        //     'label' => 'La catégorie de votre produit',
        //     'attr' => [],
        //     'placeholder' => '-- Choisir la catégorie de votre produit --',
        //     'class' => Category::class,
        //     'choice_label' => function (Category $category) {
        //         return strtoupper($category->getName());
        //     }
        // ]);
        // }


        // });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}