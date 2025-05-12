<?php

namespace App\Repository;

use App\Entity\Theme; // On importe l'entité Theme
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository; // Classe de base pour le repository
use Doctrine\Persistence\ManagerRegistry; // Permet d'accéder au gestionnaire d'entités

/**
 * @extends ServiceEntityRepository<Theme>
 * Repository de l'entité Theme.
 * Permet d'interagir avec la base de données pour récupérer, ajouter ou supprimer des objets Theme.
 */
class ThemeRepository extends ServiceEntityRepository
{
    // Le constructeur initialise le repository avec le gestionnaire d'entités et l'entité Theme
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Theme::class);
    }

    /*
     * Méthode personnalisée (commentée pour l'instant)
     */
    /*
    // Méthode pour récupérer plusieurs objets Theme selon une valeur donnée dans un champ fictif 'exampleField'
    public function findByExampleField($value): array
    {
        return $this->createQueryBuilder('t') // 't' est un alias pour Theme
            ->andWhere('t.exampleField = :val') // Condition pour filtrer les résultats
            ->setParameter('val', $value) // Définir la valeur du paramètre
            ->orderBy('t.id', 'ASC') // Tri par ID croissant
            ->setMaxResults(10) // Limiter les résultats à 10
            ->getQuery() // Créer la requête
            ->getResult(); // Exécuter la requête et retourner les résultats
    }
    */

    /*
    // Méthode pour récupérer un seul objet Theme par une valeur donnée
    public function findOneBySomeField($value): ?Theme
    {
        return $this->createQueryBuilder('t') // 't' est un alias pour Theme
            ->andWhere('t.exampleField = :val') // Condition pour filtrer par le champ 'exampleField'
            ->setParameter('val', $value) // Valeur du paramètre
            ->getQuery() // Créer la requête
            ->getOneOrNullResult(); // Retourne un seul résultat ou null si aucune correspondance
    }
    */
}