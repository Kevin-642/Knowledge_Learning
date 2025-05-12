<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

// Classe de fixtures pour insérer des données de test dans la base
class AppFixtures extends Fixture
{
    // Service pour hasher les mots de passe des utilisateurs
    private UserPasswordHasherInterface $passwordHasher;

    // Injection du hasher de mots de passe via le constructeur
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    // Méthode exécutée automatiquement lors de l’exécution de la commande "doctrine:fixtures:load"
    public function load(ObjectManager $manager): void
    {
        // Initialisation de Faker pour générer des données aléatoires (noms, emails, etc.)
        $faker = Factory::create();

        // Création d'un utilisateur administrateur
        $user = new User();
        $user->setUsername('John Doe') // Nom d'utilisateur
             ->setEmail('DirecteurKnowledge@gmail.com') // Email
             ->setRoles(['ROLE_ADMIN']) // Rôle administrateur
             ->setPassword($this->passwordHasher->hashPassword($user, 'admin2025')) // Mot de passe hashé
             ->setCreatedAt(new \DateTimeImmutable()) // Date de création
             ->setUpdatedAt(new \DateTimeImmutable()) // Date de mise à jour
             ->setVerified(true); // L'utilisateur est vérifié (email confirmé)

        $manager->persist($user); // On prépare l'enregistrement en base

        // Boucle pour créer 10 utilisateurs aléatoires
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setUsername($faker->userName()) // Nom généré aléatoirement
                 ->setEmail($faker->unique()->safeEmail()) // Email unique aléatoire
                 ->setRoles(['ROLE_USER']) // Rôle utilisateur simple
                 ->setPassword($this->passwordHasher->hashPassword($user, 'user2025')) // Mot de passe commun
                 ->setCreatedAt(new \DateTimeImmutable())
                 ->setUpdatedAt(new \DateTimeImmutable())
                 ->setVerified($faker->boolean()); // Vérification aléatoire

            $manager->persist($user); // Enregistrement différé
        }

        // Sauvegarde de tous les utilisateurs en base de données
        $manager->flush();
    }
}