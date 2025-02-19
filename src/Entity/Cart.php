<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Repository\CartRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\UniqueConstraint(name:'unique_cart_item', columns:['id_user', 'id_cursus', 'id_lesson'])]
#[ApiResource(
    operations:[
        new GetCollection(),
        new Get(),
        new Post(),
        new Delete()
    ]
)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_cart = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne (targetEntity:User::class)]
    #[ORM\JoinColumn(name: "id_user", referencedColumnName: "id_user",nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne (targetEntity:Cursus::class)]
    #[ORM\JoinColumn(name:'id_cursus', referencedColumnName:'id_cursus', nullable:true)]
    private ?Cursus $cursus = null;

    #[ORM\ManyToOne (targetEntity:Lesson::class)]
    #[ORM\JoinColumn(name:'id_lesson', referencedColumnName:'id_lesson', nullable:true)]
    private ?Lesson $lesson = null;

    public function getIdCart(): ?int
    {
        return $this->id_cart;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getCursus(): ?Cursus
    {
        return $this->cursus;
    }

    public function setCursus(?Cursus $cursus): static
    {
        $this->cursus = $cursus;

        return $this;
    }

    public function getLesson(): ?Lesson
    {
        return $this->lesson;
    }

    public function setLesson(?Lesson $lesson): static
    {
        $this->lesson = $lesson;

        return $this;
    }
}