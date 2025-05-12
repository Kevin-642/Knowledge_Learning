<?php

namespace App\Repository;

use App\Entity\Certification; // On importe l'entité Certification
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository; // Classe de base pour les repositories
use Doctrine\Persistence\ManagerRegistry; // Permet d'accéder au gestionnaire d'entités

/**
 * @extends ServiceEntityRepository<Certification>
 * Cette classe représente le repository de l'entité Certification.
 * Elle permet d'interagir avec la base de données pour les objets Certification.
 */
class CertificationRepository extends ServiceEntityRepository
{
    // Le constructeur injecte le gestionnaire d'entités et le passe à la classe parente
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Certification::class);
    }

    /*
     * Exemple de méthode personnalisée (commentée pour le moment)
     */

    /*
    // Méthode pour récupérer plusieurs certifications selon une valeur donnée
    public function findByExampleField($value): array
    {
        return $this->createQueryBuilder('c') // 'c' est un alias pour Certification
            ->andWhere('c.exampleField = :val') // condition sur un champ fictif
            ->setParameter('val', $value) // valeur du paramètre
            ->orderBy('c.id', 'ASC') // tri par ID croissant
            ->setMaxResults(10) // limite à 10 résultats
            ->getQuery()
            ->getResult(); // exécute la requête et retourne les résultats
    }
    */

    /*
    // Méthode pour récupérer une seule certification selon une valeur donnée
    public function findOneBySomeField($value): ?Certification
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult(); // retourne un seul résultat ou null
    }
    */
}