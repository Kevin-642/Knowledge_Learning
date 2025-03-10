<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;
  
    protected function setUp():void
    {
        //Récupération des services necessaires
        $this->client = static::createClient();
        $container = static::getContainer();

        $this->entityManager = $container->get(EntityManagerInterface::class);
        $this->passwordHasher = $container->get(UserPasswordHasherInterface::class);

        //Verification si l'utilisateur existe déjà
        $userRepository = $this->entityManager->getRepository(User::class);
        $existingUser = $userRepository->findOneBy(['email'=>'johndoe@gmail.com']);

        //création de l'utilisateur si il n'existe pas
        if(!$existingUser){
            $user = new User();
            $user -> setUsername('John Doe');
            $user -> setEmail('johndoe@gmail.com');
            $user -> setRoles(['ROLE_USER']);
            $user -> setVerified(true);
            $user -> setPassword($this->passwordHasher->hashPassword($user,'admin0123'));

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
    }
    
    public function testLoginUser()
    {
        //Accès a la page de connexion

        $crawler = $this->client->request('GET', '/login');
        //verification du chargement de la page et si un formulaire est présent dessus
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');

        //soumission du formulaire d'inscription
        $form = $crawler->selectButton('Connexion')->form([
            'email'=>'johndoe@gmail.com',
            'password'=>'admin0123',
        ]);

        $this->client->submit($form);

        //Verification de la redirection après connexion
        $this->assertResponseRedirects('/');

        $this->client->followRedirect();
    }

}