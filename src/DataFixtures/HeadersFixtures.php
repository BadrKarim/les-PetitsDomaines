<?php

namespace App\DataFixtures;

use App\Entity\Header;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class HeaderFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++){

            $header = new Header();
            $header->setTitle('title'.$i);
            $header->setContent('votre contenu '.$i.' dans la page home');
            $header->setBtnTitle('boutonTitle '.$i);
            $header->setBtnUrl('boutonUrl '.$i);
            $header->setIllustration('illustration'.$i);

            $manager->persist($header);
        }

        $manager->flush();    
    }
}