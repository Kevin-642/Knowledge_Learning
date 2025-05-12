<?php

namespace App\Tests\Controller;

use App\Entity\Cart;
use App\Entity\Cursus;
use App\Entity\Lesson;
use App\Entity\Theme;
use App\Entity\User;
use App\Service\StripeService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\FixtureLoader;

class CheckoutControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;

    // Méthode exécutée avant chaque test pour préparer l'environnement
    protected function setUp(): void
    {
        // Création du client pour simuler les requêtes HTTP
        $this->client = static::createClient();
        
        // Récupération du conteneur de services pour accéder aux services de Symfony
        $container = static::getContainer();

        // Récupération du gestionnaire d'entités pour interagir avec la base de données
        $this->entityManager = $container->get(EntityManagerInterface::class);
        
        // Récupération du service de hachage des mots de passe (non utilisé dans ce test, mais utile dans d'autres tests)
        $this->passwordHasher = $container->get(UserPasswordHasherInterface::class);

        // Récupération de l'utilisateur de test à partir de son email dans la base de données
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => 'johndoe@gmail.com']);

        // Connexion de l'utilisateur dans le client (simule une authentification)
        $this->client->loginUser(user: $user);

        // Récupération de la leçon de test à partir de son nom
        $lessonRepository = $this->entityManager->getRepository(Lesson::class);
        $lesson = $lessonRepository->findOneBy(['name_lesson' => 'Leçon 1: Découverte de l\'instrument']);

        // Création d'un panier d'achat lié à l'utilisateur et à la leçon
        $cart = new Cart();
        $cart->setUser($user); // Lier le panier à l'utilisateur
        $cart->setLesson($lesson); // Lier le panier à la leçon
        $cart->setCreatedAt(new DateTimeImmutable()); // Enregistrer la date de création du panier

        // Persister le panier dans la base de données
        $this->entityManager->persist($cart);
        $this->entityManager->flush();
    }

    // Test pour vérifier la création d'une session de paiement avec Stripe
    public function testCreateCheckoutSession()
    {
        // Récupération du service Stripe
        $container = static::getContainer();
        $stripeService = $container->get(StripeService::class);

        // Récupération du panier de l'utilisateur pour la session de paiement
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => 'johndoe@gmail.com']);

        $cartRepository = $this->entityManager->getRepository(Cart::class);
        $cart = $cartRepository->findBy(['user' => $user]);

        // Définition des URLs de succès et d'annulation pour la session de paiement
        $successUrl = 'http://localhost/success';
        $cancelUrl = 'http://localhost/cancel';

        // Appel du service Stripe pour créer la session de paiement
        $session = $stripeService->createCheckoutSession($cart, $successUrl, $cancelUrl);

        // Vérification que la session Stripe a bien été créée
        $this->assertNotEmpty($session->id, 'La session Stripe doit avoir un ID.');
        $this->assertEquals($successUrl, $session->success_url, 'L\'URL de succès doit correspondre.');
        $this->assertEquals($cancelUrl, $session->cancel_url, 'L\'URL d\'annulation doit correspondre.');
    }
}