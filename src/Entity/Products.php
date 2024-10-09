<?php

namespace App\Entity;

use App\Repository\ProductsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductsRepository::class)]
class Products
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Category $category = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Manufacture $manufacture = null;

    #[ORM\Column]
    private ?int $measure = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private array $images = [];

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?float $discount = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $discount_from = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $discount_to = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $meta_description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $meta_keywords = null;

    #[ORM\Column(length: 255)]
    private ?string $sku = null;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\Column]
    private ?float $weight = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $note = null;

    #[ORM\Column(nullable: true)]
    private ?bool $active = null;

    #[ORM\Column]
    private ?float $quantity = null;

    #[ORM\Column(nullable: true)]
    private ?bool $best_seller = null;

    #[ORM\Column]
    private ?bool $show_web = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

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

    public function getMeasure(): ?int
    {
        return $this->measure;
    }

    public function setMeasure(int $measure): static
    {
        $this->measure = $measure;

        return $this;
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

    public function getImages(): array
    {
        return $this->images;
    }

    public function setImages(array $images): static
    {
        $this->images = $images;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    public function setDiscount(float $discount): static
    {
        $this->discount = $discount;

        return $this;
    }

    public function getDiscountFrom(): ?\DateTimeInterface
    {
        return $this->discount_from;
    }

    public function setDiscountFrom(\DateTimeInterface $discount_from): static
    {
        $this->discount_from = $discount_from;

        return $this;
    }

    public function getDiscountTo(): ?\DateTimeInterface
    {
        return $this->discount_to;
    }

    public function setDiscountTo(\DateTimeInterface $discount_to): static
    {
        $this->discount_to = $discount_to;

        return $this;
    }

    public function getMetaDescription(): ?string
    {
        return $this->meta_description;
    }

    public function setMetaDescription(?string $meta_description): static
    {
        $this->meta_description = $meta_description;

        return $this;
    }

    public function getMetaKeywords(): ?string
    {
        return $this->meta_keywords;
    }

    public function setMetaKeywords(?string $meta_keywords): static
    {
        $this->meta_keywords = $meta_keywords;

        return $this;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(string $sku): static
    {
        $this->sku = $sku;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(string $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function isBestSeller(): ?bool
    {
        return $this->best_seller;
    }

    public function setBestSeller(?bool $best_seller): static
    {
        $this->best_seller = $best_seller;

        return $this;
    }

    public function isShowWeb(): ?bool
    {
        return $this->show_web;
    }

    public function setShowWeb(bool $show_web): static
    {
        $this->show_web = $show_web;

        return $this;
    }
}
