<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CursusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

// Déclaration de la classe Cursus en tant qu'entité Doctrine
#[ORM\Entity(repositoryClass: CursusRepository::class)] // Spécifie que cette classe est une entité Doctrine avec son repository spécifique
#[ORM\HasLifecycleCallbacks] // Permet d'utiliser des méthodes de cycle de vie (comme PrePersist et PreUpdate)
#[ApiResource()] // Permet d'exposer cette entité via l'API (avec les opérations par défaut)
class Cursus
{
    #[ORM\Id] // Indique que la propriété suivante est l'identifiant principal de l'entité
    #[ORM\GeneratedValue] // Indique que la valeur de cet identifiant est générée automatiquement
    #[ORM\Column] // Indique que cette propriété est mappée à une colonne de la base de données
    private ?int $id_cursus = null; // Identifiant de l'entité Cursus

    #[ORM\Column(length: 255)] // Colonne de type string avec une longueur maximale de 255 caractères
    private ?string $Name_cursus = null; // Nom du cursus (par exemple, "Développement web")

    #[ORM\Column] // Colonne pour le prix du cursus
    private ?float $Price = null; // Prix du cursus, de type float

    #[ORM\Column] // Colonne pour la date de création du cursus
    private ?\DateTimeImmutable $createdAt = null; // Date de création du cursus

    #[ORM\Column] // Colonne pour la date de la dernière mise à jour du cursus
    private ?\DateTimeImmutable $updatedAt = null; // Date de la dernière mise à jour du cursus

    #[ORM\ManyToOne(inversedBy: 'cursuses')] // Relation Many-to-One avec l'entité Theme (un cursus appartient à un thème)
    #[ORM\JoinColumn(name: "id_theme", referencedColumnName: "id_theme", nullable: false)] // Jointure avec la table Theme
    private ?Theme $theme = null; // Propriété pour le thème du cursus

    /**
     * @var Collection<int, lesson> // Déclaration de la relation One-to-Many avec l'entité Lesson
     */
    #[ORM\OneToMany(targetEntity: Lesson::class, mappedBy: 'cursus')] // Relation One-to-Many avec l'entité Lesson (un cursus peut avoir plusieurs leçons)
    private Collection $lesson; // Collection de leçons associées à ce cursus

    #[ORM\Column(length: 255, nullable: true)] // Colonne pour l'image du cursus, nullable (peut être vide)
    private ?string $images = null; // URL ou chemin de l'image associée au cursus

    #[ORM\Column(type: Types::TEXT, nullable: true)] // Colonne pour la description du cursus, de type texte (plus long que 255 caractères)
    private ?string $description = null; // Description du cursus

    // Constructeur qui initialise la collection de leçons
    public function __construct()
    {
        $this->lesson = new ArrayCollection();
    }

    // Getter pour l'ID du cursus
    public function getIdCursus(): ?int
    {
        return $this->id_cursus;
    }

    // Getter pour le nom du cursus
    public function getNameCursus(): ?string
    {
        return $this->Name_cursus;
    }

    // Setter pour le nom du cursus
    public function setNameCursus(string $Name_cursus): static
    {
        $this->Name_cursus = $Name_cursus;

        return $this;
    }

    // Getter pour le prix du cursus
    public function getPrice(): ?float
    {
        return $this->Price;
    }

    // Setter pour le prix du cursus
    public function setPrice(float $Price): static
    {
        $this->Price = $Price;

        return $this;
    }

    // Getter pour la date de création du cursus
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    // Setter pour la date de création du cursus
    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    // Getter pour la date de la dernière mise à jour du cursus
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    // Setter pour la date de la dernière mise à jour du cursus
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    // Méthode appelée avant la création en base de données pour définir la date de création et de mise à jour
    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    // Méthode appelée avant chaque mise à jour en base de données pour définir la date de mise à jour
    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    // Getter pour le thème du cursus
    public function getTheme(): ?Theme
    {
        return $this->theme;
    }

    // Setter pour le thème du cursus
    public function setTheme(?Theme $theme): static
    {
        $this->theme = $theme;

        return $this;
    }

    // Getter pour la collection de leçons associées au cursus
    /**
     * @return Collection<int, lesson>
     */
    public function getLesson(): Collection
    {
        return $this->lesson;
    }

    // Ajouter une leçon à la collection
    public function addLesson(lesson $lesson): static
    {
        if (!$this->lesson->contains($lesson)) {
            $this->lesson->add($lesson);
            $lesson->setCursus($this);
        }

        return $this;
    }

    // Retirer une leçon de la collection
    public function removeLesson(lesson $lesson): static
    {
        if ($this->lesson->removeElement($lesson)) {
            // Mettre la propriété cursus de la leçon à null
            if ($lesson->getCursus() === $this) {
                $lesson->setCursus(null);
            }
        }

        return $this;
    }

    // Méthode permettant d'afficher le nom du cursus lors de l'appel de cette entité
    public function __toString(): string
    {
        return $this->getNameCursus();
    }

    // Getter pour l'image du cursus
    public function getImages(): ?string
    {
        return $this->images;
    }

    // Setter pour l'image du cursus
    public function setImages(?string $images): static
    {
        $this->images = $images;

        return $this;
    }

    // Getter pour la description du cursus
    public function getDescription(): ?string
    {
        return $this->description;
    }

    // Setter pour la description du cursus
    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
}