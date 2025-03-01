<?php

namespace App\Entity;

use App\Repository\StockRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StockRepository::class)]
class Stock
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'stock')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Article $articleID = null;

    #[ORM\Column]
    private ?int $nbStock = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArticleID(): ?Article
    {
        return $this->articleID;
    }

    public function setArticleID(Article $articleID): static
    {
        $this->articleID = $articleID;

        return $this;
    }

    public function getNbStock(): ?int
    {
        return $this->nbStock;
    }

    public function setNbStock(int $nbStock): static
    {
        $this->nbStock = $nbStock;

        return $this;
    }
}
