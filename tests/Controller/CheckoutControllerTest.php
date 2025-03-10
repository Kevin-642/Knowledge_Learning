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

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $container = static::getContainer();

        $this->entityManager = $container->get(EntityManagerInterface::class);
        $this->passwordHasher = $container->get(UserPasswordHasherInterface::class);

        // Récupérer l'utilisateur de test
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => 'johndoe@gmail.com']);

        // Authentification de l'utilisateur de test
        $this->client->loginUser(user: $user);

        // Récupérer la leçon de test
        $lessonRepository = $this->entityManager->getRepository(Lesson::class);
        $lesson = $lessonRepository->findOneBy(['name_lesson' => 'Leçon 1: Découverte de l\'instrument']);

        // Création d'un panier
        $cart = new Cart();
        $cart->setUser($user);
        $cart->setLesson($lesson);
        $cart->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($cart);
        $this->entityManager->flush();
    }


    public function testCreateCheckoutSession()
    {
        $container = static::getContainer();
        $stripeService = $container->get(StripeService::class);

        // Récupération du panier
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => 'johndoe@gmail.com']);

        $cartRepository = $this->entityManager->getRepository(Cart::class);
        $cart = $cartRepository->findBy(['user' => $user]);

        // Appel du service Stripe
        $successUrl = 'http://localhost/success';
        $cancelUrl = 'http://localhost/cancel';

        $session = $stripeService->createCheckoutSession($cart, $successUrl, $cancelUrl);

        // Assertions
        $this->assertNotEmpty($session->id, 'La session Stripe doit avoir un ID.');
        $this->assertEquals($successUrl, $session->success_url, 'L\'URL de succès doit correspondre.');
        $this->assertEquals($cancelUrl, $session->cancel_url, 'L\'URL d\'annulation doit correspondre.');
    }
}