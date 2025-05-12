<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Repository\ResetPasswordRequestRepository;
use Doctrine\ORM\Mapping as ORM;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestInterface;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestTrait;

#[ORM\Entity(repositoryClass: ResetPasswordRequestRepository::class)]
// Cette entité est exposée en tant que ressource API.
// Elle permet uniquement l’opération POST pour créer une nouvelle demande de réinitialisation de mot de passe.
#[ApiResource(
    operations: [
        new Post()
    ]
)]
class ResetPasswordRequest implements ResetPasswordRequestInterface
{
    // Le trait fourni par le bundle SymfonyCasts permet d’avoir les propriétés et méthodes de base
    // pour une demande de réinitialisation (ex: date d’expiration, selector, hashedToken, etc.).
    use ResetPasswordRequestTrait;

    // Identifiant unique de la demande
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Relation ManyToOne avec l'entité User (un utilisateur peut avoir plusieurs demandes).
    // On précise que la colonne en base de données sera nommée "user_id",
    // et qu'elle fait référence à la colonne "id_user" dans la table des utilisateurs.
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id_user", nullable: false)]
    private ?User $user = null;

    /**
     * Le constructeur initialise une nouvelle demande de réinitialisation.
     * Il prend en paramètre :
     * - l'utilisateur concerné,
     * - la date d'expiration du lien,
     * - le "selector" (identifiant public),
     * - le "hashedToken" (jeton sécurisé pour authentifier la demande).
     */
    public function __construct(User $user, \DateTimeInterface $expiresAt, string $selector, string $hashedToken)
    {
        $this->user = $user;
        // Cette méthode du trait permet de définir les valeurs : expiresAt, selector, hashedToken, requestedAt.
        $this->initialize($expiresAt, $selector, $hashedToken);
    }

    // Getter pour l'identifiant
    public function getId(): ?int
    {
        return $this->id;
    }

    // Getter pour accéder à l’utilisateur lié à cette demande
    public function getUser(): User
    {
        return $this->user;
    }
}