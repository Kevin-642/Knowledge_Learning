<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

// Déclaration de l'entité User exposée via API Platform avec 3 opérations GET (collection et élément) + POST (création)
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    operations:[
        new GetCollection(),
        new Get(),
        new Post()
    ]
)]
// Contrainte d’unicité sur l’email (niveau base de données et validation Symfony)
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'Il existe déjà un compte avec cet e-mail')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    // Identifiant unique de l’utilisateur
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_user = null;

    // Email de l’utilisateur (doit être unique)
    #[ORM\Column(length: 180)]
    private ?string $email = null;

    // Tableau des rôles (ex : ROLE_USER, ROLE_ADMIN, etc.)
    #[ORM\Column]
    private array $roles = [];

    // Mot de passe haché
    #[ORM\Column]
    private ?string $password = null;

    // Nom d’utilisateur (affiché par exemple sur le site)
    #[ORM\Column(length: 255)]
    private ?string $username = null;

    // Date de création du compte
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    // Date de dernière mise à jour
    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    // Indique si l’adresse e-mail a été vérifiée
    #[ORM\Column]
    private bool $isVerified = false;

    // Liste des achats effectués par cet utilisateur
    #[ORM\OneToMany(targetEntity: Purchase::class, mappedBy: 'user')]
    private Collection $purchases;

    // Liste des certifications obtenues par cet utilisateur
    #[ORM\OneToMany(targetEntity: Certification::class, mappedBy: 'user')]
    private Collection $certifications;

    // Constructeur : initialise les dates et collections
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->purchases = new ArrayCollection();
        $this->certifications = new ArrayCollection();
    }

    // Getter pour l’ID
    public function getId(): ?int
    {
        return $this->id_user;
    }

    // Getter/Setter pour l’email
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    // Représentation textuelle de l’utilisateur dans le système de sécurité
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    // Getter pour les rôles, ajoute automatiquement ROLE_USER
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    // Setter pour les rôles
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    // Getter/Setter pour le mot de passe
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    // Efface les informations sensibles temporaires (ex : mot de passe en clair)
    public function eraseCredentials(): void
    {
        // $this->plainPassword = null;
    }

    // Getter/Setter pour le nom d'utilisateur
    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;
        return $this;
    }

    // Getter/Setter pour les dates de création et mise à jour
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    // Vérification de compte (ex: email vérifié via lien)
    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;
        return $this;
    }

    // GESTION DES RELATIONS AVEC PURCHASES

    /**
     * Retourne tous les achats de l'utilisateur
     */
    public function getPurchases(): Collection
    {
        return $this->purchases;
    }

    public function addPurchase(Purchase $purchase): static
    {
        if (!$this->purchases->contains($purchase)) {
            $this->purchases->add($purchase);
            $purchase->setUser($this);
        }

        return $this;
    }

    public function removePurchase(Purchase $purchase): static
    {
        if ($this->purchases->removeElement($purchase)) {
            if ($purchase->getUser() === $this) {
                $purchase->setUser(null);
            }
        }

        return $this;
    }

    // GESTION DES RELATIONS AVEC CERTIFICATIONS

    /**
     * Retourne toutes les certifications de l'utilisateur
     */
    public function getCertifications(): Collection
    {
        return $this->certifications;
    }

    public function addCertification(Certification $certification): static
    {
        if (!$this->certifications->contains($certification)) {
            $this->certifications->add($certification);
            $certification->setUser($this);
        }

        return $this;
    }

    public function removeCertification(Certification $certification): static
    {
        if ($this->certifications->removeElement($certification)) {
            if ($certification->getUser() === $this) {
                $certification->setUser(null);
            }
        }

        return $this;
    }
}