<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;
    private ?User $createdUser = null;

    // Initialisation avant chaque test
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $container = static::getContainer();

        $this->entityManager = $container->get(EntityManagerInterface::class);
        $this->userRepository = $container->get(UserRepository::class);
    }

    public function testUserRegistrationAndVerification(): void
    {
        // 1. Accès à la page d'inscription
        $this->client->request('GET', '/register');
        self::assertResponseIsSuccessful();
        self::assertPageTitleContains('S\'inscrire');

        // 2. Soumission du formulaire d'inscription
        $this->client->submitForm('S\'inscrire', [
            'registration_form[username]' => 'John Doe',
            'registration_form[email]' => 'johndoe@gmail.com',
            'registration_form[plainPassword][first]' => 'password',
            'registration_form[plainPassword][second]' => 'password',
            'registration_form[agreeTerms]' => true,
        ]);

        self::assertResponseRedirects('/login');

        // 3. Vérification de la création de l'utilisateur
        $user = $this->userRepository->findOneBy(['email' => 'johndoe@gmail.com']);
        self::assertNotNull($user);
        self::assertFalse($user->isVerified());
        $this->createdUser = $user;

        // 4. Vérification de l'email envoyé
        self::assertEmailCount(1);
        $email = $this->getMailerMessages()[0];

        self::assertEmailAddressContains($email, 'from', 'knowledge@learning.com');
        self::assertEmailAddressContains($email, 'to', 'johndoe@gmail.com');
        self::assertEmailSubjectContains($email, 'Please Confirm your Email');

        $messageBody = $email->getHtmlBody();
        self::assertIsString($messageBody);

        // 5. Simulation du clic sur le lien de vérification
        preg_match('#(http://localhost/verify/email[^"]+)#', $messageBody, $matches);
        self::assertNotEmpty($matches, 'Le lien de vérification est introuvable dans l’email.');

        $this->client->request('GET', $matches[1]);
        self::assertResponseRedirects('/login');

        // 6. Mise à jour manuelle de l'état "vérifié"
        $user->setVerified(true);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // 7. Vérification que l'utilisateur est bien marqué comme vérifié
        $verifiedUser = $this->userRepository->findOneBy(['email' => 'johndoe@gmail.com']);
        self::assertTrue($verifiedUser->isVerified());
    }

    // Nettoyage après chaque test
    protected function tearDown(): void
    {
        if ($this->createdUser) {
            $this->entityManager->remove($this->createdUser);
            $this->entityManager->flush();
        }

        $this->entityManager->close();
        parent::tearDown();
    }
}