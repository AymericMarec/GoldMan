<?php

namespace App\Entity;

use App\Repository\FavoriteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavoriteRepository::class)]
class Favorite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'articleUID')]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $userUID = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?article $articleUID = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserUID(): ?user
    {
        return $this->userUID;
    }

    public function setUserUID(?user $userUID): static
    {
        $this->userUID = $userUID;

        return $this;
    }

    public function getArticleUID(): ?article
    {
        return $this->articleUID;
    }

    public function setArticleUID(?article $articleUID): static
    {
        $this->articleUID = $articleUID;

        return $this;
    }
}
