<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Product;
use App\Entity\Category;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $category = new Category();
            $category 
            ->setName("drugs");

            $manager->persist($category);
            
            $product = new Product();
            $product
                ->setTitle('Product ' . $i)
                ->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua')
                ->setPrice(mt_rand(10, 600))
                ->setCategory($category)
                ->setMediaUri("#");

            $manager->persist($product);
        }

        $manager->flush();
    }
}
