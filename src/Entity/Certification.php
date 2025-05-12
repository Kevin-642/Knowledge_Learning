<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Repository\CertificationRepository;
use Doctrine\ORM\Mapping as ORM;

// Déclaration de la classe Certification en tant qu'entité Doctrine
#[ORM\Entity(repositoryClass: CertificationRepository::class)] // Spécifie que cette classe est une entité Doctrine avec son repository spécifique
#[ApiResource(
    operations:[
        new GetCollection(), // Opération pour récupérer une collection de certifications
        new Get(), // Opération pour récupérer une seule certification
        new Post() // Opération pour créer une nouvelle certification
    ]
)] // Permet de rendre cette entité disponible pour l'API avec les opérations spécifiées
class Certification
{
    #[ORM\Id] // Indique que la propriété suivante est l'identifiant principal de l'entité
    #[ORM\GeneratedValue] // Indique que la valeur de cet identifiant est générée automatiquement
    #[ORM\Column] // Indique que cette propriété est mappée à une colonne de la base de données
    private ?int $id_certification = null; // Identifiant de la certification, de type entier, initialisé à null

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'certifications')] // Relation Many-to-One avec l'entité User (une certification appartient à un utilisateur)
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id_user', nullable: false)] // Jointure avec la table User
    private ?User $user = null; // Propriété pour l'utilisateur qui a obtenu la certification

    #[ORM\Column] // Colonne pour la date d'obtention de la certification
    private ?\DateTimeImmutable $obtainedAt = null; // Date d'obtention de la certification, de type DateTimeImmutable

    #[ORM\ManyToOne(targetEntity:Lesson::class)] // Relation Many-to-One avec l'entité Lesson (une certification est liée à une leçon spécifique)
    #[ORM\JoinColumn(name:'id_lesson', referencedColumnName:'id_lesson',nullable: false)] // Jointure avec la table Lesson
    private ?Lesson $lesson = null; // Propriété pour la leçon associée à la certification

    // Getter pour l'ID de la certification
    public function getIdCertification(): ?int
    {
        return $this->id_certification;
    }

    // Getter pour l'utilisateur qui a obtenu la certification
    public function getUser(): ?User
    {
        return $this->user;
    }

    // Setter pour l'utilisateur qui a obtenu la certification
    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    // Getter pour la date d'obtention de la certification
    public function getObtainedAt(): ?\DateTimeImmutable
    {
        return $this->obtainedAt;
    }

    // Setter pour la date d'obtention de la certification
    public function setObtainedAt(\DateTimeImmutable $obtainedAt): static
    {
        $this->obtainedAt = $obtainedAt;

        return $this;
    }

    // Getter pour la leçon associée à la certification
    public function getLesson(): ?Lesson
    {
        return $this->lesson;
    }

    // Setter pour la leçon associée à la certification
    public function setLesson(?Lesson $lesson): static
    {
        $this->lesson = $lesson;

        return $this;
    }
}