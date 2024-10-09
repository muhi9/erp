<?php

namespace App\Entity;

use App\Repository\MakeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MakeRepository::class)]
class Make
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name_bg = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getNameBg(): ?string
    {
        return $this->name_bg;
    }

    public function setNameBg(?string $name_bg): static
    {
        $this->name_bg = $name_bg;

        return $this;
    }
}
