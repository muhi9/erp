<?php

namespace App\Entity;

use App\Repository\OEMRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OEMRepository::class)]
class OEM
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Products $product = null;

    #[ORM\Column(length: 255)]
    private ?string $oem = null;

    #[ORM\Column(length: 255)]
    private ?string $oem_normalize = null;

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

    public function getOem(): ?string
    {
        return $this->oem;
    }

    public function setOem(string $oem): static
    {
        $this->oem = $oem;

        return $this;
    }

    public function getOemNormalize(): ?string
    {
        return $this->oem_normalize;
    }

    public function setOemNormalize(string $oem_normalize): static
    {
        $this->oem_normalize = $oem_normalize;

        return $this;
    }
}
