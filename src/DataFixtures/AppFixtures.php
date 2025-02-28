<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $user = new User();
            $user ->setUsername('Jane Doe')
                  ->setEmail('janedoe@gmail.com')
                  ->setRoles(['ROLE_ADMIN'])
                  ->setPassword($this->passwordHasher->hashPassword($user,'admin0123'))
                  ->setCreatedAt(new \DateTimeImmutable())
                  ->setUpdatedAt(new \DateTimeImmutable())
                  ->setVerified(true);

            $manager->persist($user);

        //boucle pour créer aleatoirement des utilisateurs
        for ($i = 0 ; $i <10 ; $i++){
            $user = new User();
            $user ->setUsername($faker->userName())
                  ->setEmail($faker->unique()->safeEmail())
                  ->setRoles(['ROLE_USER'])
                  ->setPassword($this->passwordHasher->hashPassword($user,'012345'))
                  ->setCreatedAt(new \DateTimeImmutable())
                  ->setUpdatedAt(new \DateTimeImmutable())
                  ->setVerified($faker->boolean());

            $manager->persist($user);
        }

        $manager->flush();
    }
}
