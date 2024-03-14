<?php

namespace App\tests;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductTest extends KernelTestCase
{
    public function testSomething(): void
    {
        $kernel = self::bootKernel();

        $container = static::getContainer();

        $category = new Category;
        $category->setName('category1');

        $product = new Product;
        $product->setName('product #5');
        $product->setSlug('slug #5');
        $product->setIllustration('illustration #5');
        $product->setSubtitle('subtitle #5');
        $product->setDescription('description #5');
        $product->setPrice(100.99);
        $product->setCategory($category);
        $product->setIsBest('1');

        $errors = $container->get('validator')->validate($product);

        $this->assertCount(0, $errors);
    }
}
