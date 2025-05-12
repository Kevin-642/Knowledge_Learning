<?php

namespace App\Repository;

use App\Entity\Cart; // On importe l'entité Cart
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository; // Classe de base pour les repositories
use Doctrine\Persistence\ManagerRegistry; // Permet d'accéder au gestionnaire d'entités (EntityManager)

/**
 * @extends ServiceEntityRepository<Cart>
 * Cette classe est un repository pour l'entité Cart.
 * Elle permet d'interagir avec la base de données pour les objets de type Cart.
 */
class CartRepository extends ServiceEntityRepository
{
    // Constructeur : on passe le registre des gestionnaires à la classe parente
    public function __construct(ManagerRegistry $registry)
    {
        // On initialise le repository avec l'entité Cart
        parent::__construct($registry, Cart::class);
    }

    /*
     * Méthodes personnalisées (exemples commentés ci-dessous)
     */

    /*
    // Exemple de méthode pour récupérer une liste de paniers selon un champ donné
    public function findByExampleField($value): array
    {
        return $this->createQueryBuilder('c') // 'c' est un alias pour Cart
            ->andWhere('c.exampleField = :val') // condition sur un champ fictif
            ->setParameter('val', $value) // valeur du paramètre
            ->orderBy('c.id', 'ASC') // tri par identifiant
            ->setMaxResults(10) // limite à 10 résultats
            ->getQuery()
            ->getResult(); // exécution de la requête
    }
    */

    /*
    // Exemple de méthode pour récupérer un seul panier selon un champ donné
    public function findOneBySomeField($value): ?Cart
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult(); // retourne un seul résultat ou null
    }
    */
}