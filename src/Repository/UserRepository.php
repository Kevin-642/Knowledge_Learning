<?php

namespace App\Repository;

use App\Entity\User; // On importe l'entité User
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository; // Classe de base pour le repository
use Doctrine\Persistence\ManagerRegistry; // Permet d'accéder au gestionnaire d'entités
use Symfony\Component\Security\Core\Exception\UnsupportedUserException; // Exception si l'utilisateur est incompatible
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface; // Interface pour les utilisateurs avec mot de passe
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface; // Interface pour l'upgrade des mots de passe

/**
 * @extends ServiceEntityRepository<User>
 * Repository pour l'entité User.
 * Permet de gérer les utilisateurs dans la base de données, notamment pour les opérations liées à l'authentification et à la gestion des mots de passe.
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    // Le constructeur initialise le repository avec le gestionnaire d'entités et l'entité User
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Méthode utilisée pour mettre à jour (rehacher) le mot de passe de l'utilisateur automatiquement au fil du temps.
     *
     * @param PasswordAuthenticatedUserInterface $user L'utilisateur dont le mot de passe est à mettre à jour
     * @param string $newHashedPassword Le nouveau mot de passe haché
     * @throws UnsupportedUserException Si l'utilisateur n'est pas une instance de User
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        // Vérifie si l'utilisateur est bien une instance de la classe User
        if (!$user instanceof User) {
            // Lance une exception si l'utilisateur est d'un autre type
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        // Met à jour le mot de passe de l'utilisateur avec le nouveau mot de passe haché
        $user->setPassword($newHashedPassword);

        // Persist l'utilisateur mis à jour et effectue un flush pour enregistrer les modifications dans la base de données
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    /*
     * Méthodes commentées pour rechercher des utilisateurs par un champ spécifique
     */
    /*
    // Méthode pour récupérer plusieurs objets User en fonction d'une valeur dans un champ fictif 'exampleField'
    public function findByExampleField($value): array
    {
        return $this->createQueryBuilder('u') // 'u' est un alias pour User
            ->andWhere('u.exampleField = :val') // Condition pour filtrer les résultats
            ->setParameter('val', $value) // Définir la valeur du paramètre
            ->orderBy('u.id', 'ASC') // Tri par ID croissant
            ->setMaxResults(10) // Limiter les résultats à 10
            ->getQuery() // Créer la requête
            ->getResult(); // Exécuter la requête et retourner les résultats
    }
    */

    /*
    // Méthode pour récupérer un seul utilisateur par une valeur donnée
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u') // 'u' est un alias pour User
            ->andWhere('u.exampleField = :val') // Condition pour filtrer par le champ 'exampleField'
            ->setParameter('val', $value) // Valeur du paramètre
            ->getQuery() // Créer la requête
            ->getOneOrNullResult(); // Retourne un seul résultat ou null si aucune correspondance
    }
    */
}