<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityControllerTest extends WebTestCase
{
    private KernelBrowser $client; // Client HTTP pour simuler les requêtes
    private EntityManagerInterface $entityManager; // Entité pour gérer la persistance des données
    private UserPasswordHasherInterface $passwordHasher; // Service pour hacher le mot de passe de l'utilisateur
  
    // Méthode appelée avant chaque test pour configurer l'environnement
    protected function setUp(): void
    {
        // Récupération des services nécessaires pour le test
        $this->client = static::createClient(); // Création du client de test
        $container = static::getContainer(); // Récupération du conteneur de services Symfony

        // Récupération de l'EntityManager pour interagir avec la base de données
        $this->entityManager = $container->get(EntityManagerInterface::class);
        // Récupération du service de hachage des mots de passe
        $this->passwordHasher = $container->get(UserPasswordHasherInterface::class);

        // Vérification si un utilisateur avec l'email 'johndoe@gmail.com' existe déjà dans la base
        $userRepository = $this->entityManager->getRepository(User::class);
        $existingUser = $userRepository->findOneBy(['email' => 'johndoe@gmail.com']);

        // Si l'utilisateur n'existe pas, on le crée pour le test
        if (!$existingUser) {
            $user = new User(); // Création d'un nouvel utilisateur
            $user->setUsername('John Doe'); // Nom d'utilisateur
            $user->setEmail('johndoe@gmail.com'); // Email de l'utilisateur
            $user->setRoles(['ROLE_USER']); // Attribuer le rôle utilisateur
            $user->setVerified(true); // Marquer l'utilisateur comme vérifié
            // Hachage du mot de passe pour l'utilisateur
            $user->setPassword($this->passwordHasher->hashPassword($user, 'admin0123'));

            // Persister et sauvegarder l'utilisateur dans la base de données
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
    }
    
    // Test de la connexion de l'utilisateur
    public function testLoginUser()
    {
        // Accès à la page de connexion
        $crawler = $this->client->request('GET', '/login');
        
        // Vérification que la réponse est réussie et que la page de connexion contient un formulaire
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form'); // Vérifie que le formulaire de connexion est bien présent

        // Soumission du formulaire de connexion avec les informations de l'utilisateur
        $form = $crawler->selectButton('Connexion')->form([
            'email' => 'johndoe@gmail.com', // Email de l'utilisateur
            'password' => 'admin0123', // Mot de passe
        ]);

        // Soumission du formulaire
        $this->client->submit($form);

        // Vérification que l'utilisateur est redirigé vers la page d'accueil après connexion
        $this->assertResponseRedirects('/'); // Vérification de la redirection

        // Suivi de la redirection
        $this->client->followRedirect(); // Suivi de la redirection pour confirmer l'accès à la page d'accueil
    }
}