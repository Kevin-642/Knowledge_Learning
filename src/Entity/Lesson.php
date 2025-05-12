<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\LessonRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LessonRepository::class)] // L'entité "Lesson" avec son repository associé
#[ORM\HasLifecycleCallbacks] // Les méthodes de callback de cycle de vie (avant persistance, avant mise à jour, etc.)
#[ApiResource()] // Expose cette entité via l'API REST avec API Platform
class Lesson
{
    #[ORM\Id] // Définit l'ID de l'entité, qui est la clé primaire
    #[ORM\GeneratedValue] // Génère automatiquement l'ID
    #[ORM\Column] // Colonne dans la base de données
    private ?int $id_lesson = null;

    #[ORM\Column(length: 255)] // Colonne pour le nom de la leçon, avec une longueur maximale de 255 caractères
    private ?string $name_lesson = null;

    #[ORM\Column] // Colonne pour le prix de la leçon
    private ?float $Price = null;

    #[ORM\Column(type: Types::TEXT)] // Colonne pour le contenu détaillé de la leçon
    private ?string $content = null;

    #[ORM\Column(length: 255, nullable: true)] // Colonne pour l'URL vidéo, nullable car facultatif
    private ?string $video_url = null;

    #[ORM\Column] // Colonne pour la date de création de la leçon
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column] // Colonne pour la date de mise à jour de la leçon
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'lesson')] // Relation ManyToOne avec l'entité "Cursus"
    #[ORM\JoinColumn(name: "id_cursus", referencedColumnName: "id_cursus")] // Colonne qui référence l'ID de "Cursus"
    private ?Cursus $cursus = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)] // Colonne pour la description de la leçon, nullable
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)] // Colonne pour l'image de certification, nullable
    private ?string $certificationImage = null;

    // Getter et setter pour l'ID de la leçon
    public function getIdLesson(): ?int
    {
        return $this->id_lesson;
    }

    // Getter et setter pour le nom de la leçon
    public function getNameLesson(): ?string
    {
        return $this->name_lesson;
    }

    public function setNameLesson(string $name_lesson): static
    {
        $this->name_lesson = $name_lesson;
        return $this;
    }

    // Getter et setter pour le prix de la leçon
    public function getPrice(): ?float
    {
        return $this->Price;
    }

    public function setPrice(float $Price): static
    {
        $this->Price = $Price;
        return $this;
    }

    // Getter et setter pour le contenu de la leçon
    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;
        return $this;
    }

    // Getter et setter pour l'URL vidéo de la leçon
    public function getVideoUrl(): ?string
    {
        return $this->video_url;
    }

    public function setVideoUrl(?string $video_url): static
    {
        $this->video_url = $video_url;
        return $this;
    }

    // Getter et setter pour la date de création de la leçon
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    // Getter et setter pour la date de mise à jour de la leçon
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    // Callback avant la persistance pour initialiser les dates
    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable(); // Initialise la date de création
        $this->updatedAt = new \DateTimeImmutable(); // Initialise la date de mise à jour
    }

    // Callback avant chaque mise à jour pour définir la nouvelle date de mise à jour
    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable(); // Met à jour la date de mise à jour
    }

    // Getter et setter pour la relation avec "Cursus"
    public function getCursus(): ?Cursus
    {
        return $this->cursus;
    }

    public function setCursus(?Cursus $cursus): static
    {
        $this->cursus = $cursus;
        return $this;
    }

    // Méthode magique __toString() pour retourner le nom de la leçon sous forme de chaîne
    public function __toString(): string
    {
        return $this->getNameLesson(); // Retourne le nom de la leçon
    }

    // Getter et setter pour la description de la leçon
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    // Getter et setter pour l'image de certification de la leçon
    public function getCertificationImage(): ?string
    {
        return $this->certificationImage;
    }

    public function setCertificationImage(?string $certificationImage): static
    {
        $this->certificationImage = $certificationImage;
        return $this;
    }
}