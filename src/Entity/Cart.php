<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Repository\CartRepository;
use Doctrine\ORM\Mapping as ORM;

// Déclaration de la classe Cart en tant qu'entité Doctrine
#[ORM\Entity(repositoryClass: CartRepository::class)] // Spécifie que cette classe est une entité Doctrine avec son repository spécifique
#[ORM\HasLifecycleCallbacks] // Permet d'ajouter des méthodes de gestion des événements du cycle de vie de l'entité (par exemple, pré-persist, post-update)
#[ORM\UniqueConstraint(name:'unique_cart_item', columns:['id_user', 'id_cursus', 'id_lesson'])] // Crée une contrainte d'unicité sur la combinaison des trois colonnes pour éviter les doublons
#[ApiResource(
    operations:[
        new GetCollection(), // Opération pour récupérer plusieurs éléments
        new Get(), // Opération pour récupérer un seul élément
        new Post(), // Opération pour créer un nouvel élément
        new Delete() // Opération pour supprimer un élément
    ]
)] // Permet de rendre cette entité disponible pour l'API avec les opérations spécifiées
class Cart
{
    #[ORM\Id] // Indique que la propriété suivante est l'identifiant principal de l'entité
    #[ORM\GeneratedValue] // Indique que la valeur de cet identifiant est générée automatiquement
    #[ORM\Column] // Indique que cette propriété est mappée à une colonne de la base de données
    private ?int $id_cart = null; // Identifiant du panier, de type entier, initialisé à null

    #[ORM\Column] // Colonne de la base de données pour la date de création
    private ?\DateTimeImmutable $createdAt = null; // Date de création du panier, de type DateTimeImmutable

    #[ORM\ManyToOne (targetEntity:User::class)] // Relation Many-to-One avec l'entité User (un panier appartient à un utilisateur)
    #[ORM\JoinColumn(name: "id_user", referencedColumnName: "id_user",nullable: false)] // Jointure avec la table User
    private ?User $user = null; // Propriété pour l'utilisateur du panier

    #[ORM\ManyToOne (targetEntity:Cursus::class)] // Relation Many-to-One avec l'entité Cursus (un panier peut avoir un cursus associé)
    #[ORM\JoinColumn(name:'id_cursus', referencedColumnName:'id_cursus', nullable:true)] // Jointure avec la table Cursus
    private ?Cursus $cursus = null; // Propriété pour le cursus associé au panier

    #[ORM\ManyToOne (targetEntity:Lesson::class)] // Relation Many-to-One avec l'entité Lesson (un panier peut avoir une leçon associée)
    #[ORM\JoinColumn(name:'id_lesson', referencedColumnName:'id_lesson', nullable:true)] // Jointure avec la table Lesson
    private ?Lesson $lesson = null; // Propriété pour la leçon associée au panier

    // Getter pour l'ID du panier
    public function getIdCart(): ?int
    {
        return $this->id_cart;
    }

    // Getter pour la date de création
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    // Setter pour la date de création
    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    // Getter pour l'utilisateur
    public function getUser(): ?User
    {
        return $this->user;
    }

    // Setter pour l'utilisateur
    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    // Getter pour le cursus
    public function getCursus(): ?Cursus
    {
        return $this->cursus;
    }

    // Setter pour le cursus
    public function setCursus(?Cursus $cursus): static
    {
        $this->cursus = $cursus;

        return $this;
    }

    // Getter pour la leçon
    public function getLesson(): ?Lesson
    {
        return $this->lesson;
    }

    // Setter pour la leçon
    public function setLesson(?Lesson $lesson): static
    {
        $this->lesson = $lesson;

        return $this;
    }
}