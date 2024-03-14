<?php

namespace App\DataFixtures;

use App\Entity\Carrier;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CarrierFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++){
            
            $carrier = new Carrier();
            $carrier->setName('carrier'.$i);
            $carrier->setDescription("recevez votre colis ".$i." chez vous");
            $carrier->setPrice(10.99);

            $manager->persist($carrier);
        }

        $manager->flush();    
    }
}