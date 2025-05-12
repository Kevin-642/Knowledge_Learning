<?php

namespace App\Repository;

use App\Entity\ResetPasswordRequest; // On importe l'entité ResetPasswordRequest (demande de réinitialisation de mot de passe)
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository; // Classe de base pour les repositories
use Doctrine\Persistence\ManagerRegistry; // Permet d'accéder au gestionnaire d'entités

/**
 * @extends ServiceEntityRepository<ResetPasswordRequest>
 * Cette classe représente le repository de l'entité ResetPasswordRequest.
 * Elle permet d'interagir avec la base de données pour les demandes de réinitialisation de mot de passe.
 */
class ResetPasswordRequestRepository extends ServiceEntityRepository
{
    // Le constructeur injecte le gestionnaire d'entités et le passe à la classe parente
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResetPasswordRequest::class);
    }

    /*
     * Exemple de méthode personnalisée (commentée pour l'instant)
     */

    /*
    // Méthode pour récupérer plusieurs demandes de réinitialisation de mot de passe selon une valeur donnée
    public function findByExampleField($value): array
    {
        return $this->createQueryBuilder('r') // 'r' est un alias pour ResetPasswordRequest
            ->andWhere('r.exampleField = :val') // condition sur un champ fictif
            ->setParameter('val', $value) // valeur du paramètre
            ->orderBy('r.id', 'ASC') // tri par ID croissant
            ->setMaxResults(10) // limite à 10 résultats
            ->getQuery()
            ->getResult(); // exécute la requête et retourne les résultats
    }
    */

    /*
    // Méthode pour récupérer une seule demande de réinitialisation de mot de passe selon une valeur donnée
    public function findOneBySomeField($value): ?ResetPasswordRequest
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult(); // retourne un seul résultat ou null
    }
    */
}