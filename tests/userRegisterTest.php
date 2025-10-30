<?php

namespace App\tests;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class userRegisterTest extends KernelTestCase
{

    public function testUserExistsAfterCreation(): void
    {

        self::bootKernel();
        $new_user_email="nicolas.vouillerot@gmail.com";
        $entityManager = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $user =new User();
        $user->setEmail($new_user_email);
        $user->setPassword('rienmaispluslong');
        $user->setUsername('testGuy');
        $user->setRoles(['ROLE_USER']);
        $entityManager->persist($user);
        $entityManager->flush();

        $foundUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $new_user_email])->getEmail();


        $this->assertSame($new_user_email, $foundUser);
    }
}
