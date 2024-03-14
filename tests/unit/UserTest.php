<?php

namespace App\Tests\Unit;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    public function testUserIsValid(): void
    {
        $kernel = self::bootKernel();
        $container = static::getContainer();

        $user = new User;
        $user->setEmail('email');
        $user->setFirstname('firstname');
        $user->setLasname('lasname');
        $user->setPassword('password');

        $errors = $container->get('validator')->validate($user);

        $this->assertCount(0, $errors);
        
    }
}
