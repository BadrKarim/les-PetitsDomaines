<?php

namespace App\DataFixtures;

use App\Entity\Boutique;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BoutiqueFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++){
            
            $boutique = new Boutique();
            $boutique->setName('boutique'.$i);
            $boutique->setAddress("1 rue maurice audin".$i);
            $boutique->setTelephone("010101010".$i);
            $boutique->setIllustration("illustration".$i);

            $manager->persist($boutique);
        }

        $manager->flush();    
    }
}