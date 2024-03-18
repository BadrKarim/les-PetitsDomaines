<?php

namespace App\tests;

use App\Entity\Boutique;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BoutiqueTest extends KernelTestCase
{
    public function testSomething(): void
    {
        $kernel = self::bootKernel();

        $container = static::getContainer();
        
        $boutique = new Boutique;
        $boutique->setName('boutique #5');
        $boutique->setAddress('#5');
        $boutique->setTelephone('subtitle #5');
        $boutique->setIllustration('illustration #5');

        $errors = $container->get('validator')->validate($boutique);

        $this->assertCount(0, $errors);
    }
}
