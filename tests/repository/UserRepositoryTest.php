<?php

namespace App\tests\functional\repository;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    public function testCount()
    {
        $kernel = self::bootKernel();
        $user = self::$container->get(UserRepository::class)->count([]);
        $this->assertEquals(1, $user);

    }
}