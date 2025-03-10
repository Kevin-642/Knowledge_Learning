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

  protected function setUp(): void
    {
        $this->client = static::createClient();
        $container = static::getContainer();

        $this->entityManager = $container->get(EntityManagerInterface::class);
        $this->userRepository = $container->get(UserRepository::class);
    }

    public function testUserRegistrationAndVerification()
    {
        $this->client->request('GET', '/register');
        self::assertResponseIsSuccessful();
        self::assertPageTitleContains('S\'inscrire');

        $this->client->submitForm('S\'inscrire', [
            'registration_form[username]' => 'John Doe',
            'registration_form[email]' => 'johndoe@gmail.com',
            'registration_form[plainPassword][first]' => 'password',
            'registration_form[plainPassword][second]' => 'password',
            'registration_form[agreeTerms]' => true,
        ]);

        self::assertResponseRedirects('/login');

        // Étape 3 : Vérifier l'utilisateur dans la base de données
        $user = $this->userRepository->findOneBy(['email' => 'johndoe@gmail.com']);
        $this->createdUser = $user; // Stocker l'utilisateur créé
        self::assertNotNull($user);

        // Vérification de isVerified(false)
        self::assertFalse($user->isVerified());

        // Vérification que l'email est envoyé
        self::assertEmailCount(1);
        $email = $this->getMailerMessages()[0];
        self::assertEmailAddressContains($email, 'from', 'knowledge@learning.com');
        self::assertEmailAddressContains($email, 'to', 'johndoe@gmail.com');
        self::assertEmailSubjectContains($email, 'Please Confirm your Email');

        /** @var TemplatedEmail $templatedEmail */
        $templatedEmail = $email;
        $messageBody = $templatedEmail->getHtmlBody();
        self::assertIsString($messageBody);

        preg_match('#(http://localhost/verify/email.+)">#', $messageBody, $verificationUrl);
        $this->client->request('GET', $verificationUrl[1]);

        $user->setVerified(true);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        self::assertResponseRedirects('/login');
        $user = $this->userRepository->findOneBy(['email' => 'johndoe@gmail.com']);
        self::assertTrue($user->isVerified());
    }

    // Suppression de l'utilisateur créé
    protected function tearDown(): void
    {
        parent::tearDown();

        if ($this->createdUser) {
            $this->entityManager->remove($this->createdUser);
            $this->entityManager->flush();
        }

        $this->entityManager->close();
    }
}

