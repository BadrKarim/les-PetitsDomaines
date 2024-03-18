<?php

namespace App\tests;

use App\Entity\Boutique;
use App\Entity\Header;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class HeaderTest extends KernelTestCase
{
    public function testSomething(): void
    {
        $kernel = self::bootKernel();

        $container = static::getContainer();
        
        $header = new Header;
        $header->setTitle('header #5');
        $header->setContent('#5');
        $header->setBtnTitle('boutonTitle #5');
        $header->setBtnUrl('boutonUrl #5');
        $header->setIllustration('illustration #5');

        $errors = $container->get('validator')->validate($header);

        $this->assertCount(0, $errors);
    }
}
