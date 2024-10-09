<?php

namespace App\Entity;

use App\Repository\ReferentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReferentRepository::class)]
class Referent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Products $product = null;

    #[ORM\Column(length: 255)]
    private ?string $referent = null;

    #[ORM\Column(length: 255)]
    private ?string $referent_normalize = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Manufacture $manufacture = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Products
    {
        return $this->product;
    }

    public function setProduct(?Products $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getReferent(): ?string
    {
        return $this->referent;
    }

    public function setReferent(string $referent): static
    {
        $this->referent = $referent;

        return $this;
    }

    public function getReferentNormalize(): ?string
    {
        return $this->referent_normalize;
    }

    public function setReferentNormalize(string $referent_normalize): static
    {
        $this->referent_normalize = $referent_normalize;

        return $this;
    }

    public function getManufacture(): ?Manufacture
    {
        return $this->manufacture;
    }

    public function setManufacture(?Manufacture $manufacture): static
    {
        $this->manufacture = $manufacture;

        return $this;
    }
}
