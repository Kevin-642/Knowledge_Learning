<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ThemeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

// Cette classe représente l'entité "Theme" et sera exposée via API Platform.
#[ORM\Entity(repositoryClass: ThemeRepository::class)]
#[ApiResource()]
class Theme
{
    // Identifiant unique du thème
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_theme = null;

    // Nom du thème (ex: "Développement Web", "Mathématiques", etc.)
    #[ORM\Column(length: 255)]
    private ?string $name_theme = null;

    /**
     * Collection des cursus associés à ce thème.
     * Relation 1 thème → plusieurs cursus.
     * Le côté inverse est défini dans l'entité Cursus (avec 'theme' comme propriété propriétaire).
     */
    #[ORM\OneToMany(targetEntity: Cursus::class, mappedBy: 'theme')]
    private Collection $cursuses;

    // Le constructeur initialise la collection vide dès la création d’un objet Theme.
    public function __construct()
    {
        $this->cursuses = new ArrayCollection();
    }

    // Getter pour l’ID
    public function getIdTheme(): ?int
    {
        return $this->id_theme;
    }

    // Getter pour le nom du thème
    public function getNameTheme(): ?string
    {
        return $this->name_theme;
    }

    // Setter pour définir le nom du thème
    public function setNameTheme(string $name_theme): static
    {
        $this->name_theme = $name_theme;

        return $this;
    }

    /**
     * Retourne tous les cursus liés à ce thème.
     * @return Collection<int, Cursus>
     */
    public function getCursuses(): Collection
    {
        return $this->cursuses;
    }

    /**
     * Ajoute un cursus au thème (et met à jour le lien inverse dans l'entité Cursus).
     */
    public function addCursus(Cursus $cursus): static
    {
        if (!$this->cursuses->contains($cursus)) {
            $this->cursuses->add($cursus);
            $cursus->setTheme($this); // Lien inverse mis à jour
        }

        return $this;
    }

    /**
     * Supprime un cursus du thème (et coupe le lien inverse aussi).
     */
    public function removeCursus(Cursus $cursus): static
    {
        if ($this->cursuses->removeElement($cursus)) {
            // On vérifie que le cursus appartient bien à ce thème avant de couper le lien
            if ($cursus->getTheme() === $this) {
                $cursus->setTheme(null);
            }
        }

        return $this;
    }

    /**
     * Permet d'afficher automatiquement le nom du thème lorsqu'un objet Theme est utilisé comme chaîne.
     * Très utile dans des formulaires, des logs ou l'admin Symfony.
     */
    public function __toString(): string
    {
        return $this->getNameTheme();
    }
}