<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $category = new Category;
        $category->setName('category1');
        $manager->persist($category);
        $manager->flush();
        
        for ($i=0; $i<10; $i++ ){

            $product = new Product;
            $product->setName('product'.$i);
            $product->setSlug('slug'.$i);
            $product->setIllustration('illustration'.$i);
            $product->setSubtitle('subtitle'.$i);
            $product->setDescription('description'.$i);
            $product->setPrice(100.99);
            $product->setCategory($category);
            $product->setIsBest('1');

            $manager->persist($product);            
        }

        $manager->flush();
    }
}
