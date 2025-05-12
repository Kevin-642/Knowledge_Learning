<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchaseRepository::class)] // L'entité "Purchase" avec son repository associé
#[ORM\HasLifecycleCallbacks] // Permet d'ajouter des méthodes de cycle de vie, comme avant la persistance (PrePersist)
#[ApiResource(
    operations:[
       new GetCollection(), // Permet d'obtenir une collection de "Purchase"
       new Get() // Permet d'obtenir une seule "Purchase" via son ID
    ]
)]
class Purchase
{
    #[ORM\Id] // Définit l'ID de l'entité, clé primaire
    #[ORM\GeneratedValue] // Génère automatiquement l'ID
    #[ORM\Column] // Colonne pour l'ID dans la base de données
    private ?int $id_purchase = null;

    #[ORM\ManyToOne(inversedBy: 'purchases')] // Relation ManyToOne avec l'entité "User" (un utilisateur peut avoir plusieurs achats)
    #[ORM\JoinColumn(name:'id_user', referencedColumnName:'id_user', nullable: false)] // Colonne qui référence l'ID de l'utilisateur
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Cursus::class)] // Relation ManyToOne avec l'entité "Cursus" (un achat peut être pour un cursus)
    #[ORM\JoinColumn(name: 'id_cursus', referencedColumnName: 'id_cursus', nullable: true)] // Colonne qui référence l'ID de "Cursus", nullable si l'achat ne porte pas sur un cursus
    private ?Cursus $cursus = null;

    #[ORM\ManyToOne(targetEntity: Lesson::class)] // Relation ManyToOne avec l'entité "Lesson" (un achat peut être pour une leçon)
    #[ORM\JoinColumn(name: 'id_lesson', referencedColumnName: 'id_lesson', nullable: true)] // Colonne qui référence l'ID de "Lesson", nullable si l'achat ne porte pas sur une leçon
    private ?Lesson $lesson = null;

    #[ORM\Column] // Colonne pour la date d'achat
    private ?\DateTimeImmutable $purchaseAt = null;

    // Getter et setter pour l'ID de l'achat
    public function getIdPurchase(): ?int
    {
        return $this->id_purchase;
    }

    // Getter et setter pour l'utilisateur qui a effectué l'achat
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    // Getter et setter pour le cursus acheté
    public function getCursus(): ?Cursus
    {
        return $this->cursus;
    }

    public function setCursus(?Cursus $cursus): static
    {
        $this->cursus = $cursus;

        return $this;
    }

    // Getter et setter pour la leçon achetée
    public function getLesson(): ?Lesson
    {
        return $this->lesson;
    }

    public function setLesson(?Lesson $lesson): static
    {
        $this->lesson = $lesson;

        return $this;
    }

    // Getter et setter pour la date d'achat
    public function getPurchaseAt(): ?\DateTimeImmutable
    {
        return $this->purchaseAt;
    }

    public function setPurchaseAt(\DateTimeImmutable $purchaseAt): static
    {
        $this->purchaseAt = $purchaseAt;

        return $this;
    }

    // Méthode appelée avant la persistance pour définir la date d'achat
    #[ORM\PrePersist]
    public function setPurchaseAtValue(): void
    {
        $this->purchaseAt = new \DateTimeImmutable(); // Initialise la date d'achat avant la persistance
    }
}