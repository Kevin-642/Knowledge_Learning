<?php

namespace App\Repository;

use App\Entity\Purchase; // On importe l'entité Purchase (achat)
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository; // Classe de base pour les repositories
use Doctrine\Persistence\ManagerRegistry; // Permet d'accéder au gestionnaire d'entités

/**
 * @extends ServiceEntityRepository<Purchase>
 * Cette classe représente le repository de l'entité Purchase (achat).
 * Elle permet d'interagir avec la base de données pour les objets Purchase.
 */
class PurchaseRepository extends ServiceEntityRepository
{
    // Le constructeur injecte le gestionnaire d'entités et le passe à la classe parente
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Purchase::class);
    }

    /*
     * Exemple de méthode personnalisée (commentée pour l'instant)
     */

    /*
    // Méthode pour récupérer plusieurs achats selon une valeur donnée
    public function findByExampleField($value): array
    {
        return $this->createQueryBuilder('p') // 'p' est un alias pour Purchase
            ->andWhere('p.exampleField = :val') // condition sur un champ fictif
            ->setParameter('val', $value) // valeur du paramètre
            ->orderBy('p.id', 'ASC') // tri par ID croissant
            ->setMaxResults(10) // limite à 10 résultats
            ->getQuery()
            ->getResult(); // exécute la requête et retourne les résultats
    }
    */

    /*
    // Méthode pour récupérer un seul achat selon une valeur donnée
    public function findOneBySomeField($value): ?Purchase
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult(); // retourne un seul résultat ou null
    }
    */
}