<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Product;
use Liior\Faker\Prices;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
// use Doctrine\DBAL\Driver\IBMDB2\Exception\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr-FR');
        $faker->addProvider(new \Liior\Faker\Prices($faker));
        $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));

        for ($p = 0; $p < 100; $p++) {
            $product = new Product;
            $product
                ->setName($faker->productName)
                ->setPrice($faker->price(4000, 20000))
                ->setSlug($faker->slug());

            $manager->persist($product);
        }

        $manager->flush();
    }
}