<?php

namespace App\Repository;

use App\Entity\Lesson; // On importe l'entité Lesson
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository; // Classe de base pour les repositories
use Doctrine\Persistence\ManagerRegistry; // Permet d'accéder au gestionnaire d'entités

/**
 * @extends ServiceEntityRepository<Lesson>
 * Cette classe représente le repository de l'entité Lesson.
 * Elle permet d'interagir avec la base de données pour les objets Lesson.
 */
class LessonRepository extends ServiceEntityRepository
{
    // Le constructeur injecte le gestionnaire d'entités et le passe à la classe parente
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lesson::class);
    }

    /*
     * Exemple de méthode personnalisée (commentée pour l'instant)
     */

    /*
    // Méthode pour récupérer plusieurs lessons selon une valeur donnée
    public function findByExampleField($value): array
    {
        return $this->createQueryBuilder('l') // 'l' est un alias pour Lesson
            ->andWhere('l.exampleField = :val') // condition sur un champ fictif
            ->setParameter('val', $value) // valeur du paramètre
            ->orderBy('l.id', 'ASC') // tri par ID croissant
            ->setMaxResults(10) // limite à 10 résultats
            ->getQuery()
            ->getResult(); // exécute la requête et retourne les résultats
    }
    */

    /*
    // Méthode pour récupérer une seule lesson selon une valeur donnée
    public function findOneBySomeField($value): ?Lesson
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult(); // retourne un seul résultat ou null
    }
    */
}