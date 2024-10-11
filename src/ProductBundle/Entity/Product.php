<?php

namespace ProductBundle\Entity;

use App\Repository\ProductsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="ProductBundle\Repository\ProductRepository")
 */
class Product
{
      /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="ProductBundle\Entity\Category)
     * @ORM\JoinColumn(name="category", referencedColumnName="id", nullable=true)
     */
    private $category;

    /**
     * @ORM\ManyToOne(cascade={"persist", "remove"})
     * @var Manufacture|null
     */
    private $manufacture;

    /**
     * @ORM\Column
     * @var int|null
     */
    private $measure;

    /**
     * @ORM\Column(length=255)
     * @var string|null
     */
    private $name;

    /**
     * @ORM\Column
     * @var array
     */
    private $images;

    /**
     * @ORM\Column
     * @var float|null
     */
    private $price;

    /**
     * @ORM\Column
     * @var float|null
     */
    private $discount;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime|null
     */
    private $discount_from;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime|null
     */
    private $discount_to;

    /**
     * @ORM\Column(length=255, nullable=true)
     * @var string|null
     */
    private $meta_description;

    /**
     * @ORM\Column(length=255, nullable=true)
     * @var string|null
     */
    private $meta_keywords;

    /**
     * @ORM\Column(length=255)
     * @var string|null
     */
    private $sku;

    /**
     * @ORM\Column(length=255)
     * @var string|null
     */
    private $url;

    /**
     * @ORM\Column
     * @var float|null
     */
    private $weight;

    /**
     * @ORM\Column(type="text")
     * @var string|null
     */
    private $note;

    /**
     * @ORM\Column(nullable=true)
     * @var bool|null
     */
    private $active;

    /**
     * @ORM\Column
     * @var float|null
     */
    private $quantity;

    /**
     * @ORM\Column(nullable=true)
     * @var bool|null
     */
    private $best_seller;

    /**
     * @ORM\Column
     * @var bool
     */
    private $show_web;

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Category|null
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Category|null $category
     * @return $this
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Manufacture|null
     */
    public function getManufacture()
    {
        return $this->manufacture;
    }

    /**
     * @param Manufacture|null $manufacture
     * @return $this
     */
    public function setManufacture($manufacture)
    {
        $this->manufacture = $manufacture;
    }

    /**
     * @return int|null
     */
    public function getMeasure()
    {
        return $this->measure;
    }

    /**
     * @param int $measure
     * @return $this
     */
    public function setMeasure($measure)
    {
        $this->measure = $measure;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return array
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param array $images
     * @return $this
     */
    public function setImages($images)
    {
        $this->images = $images;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @param float $discount
     * @return $this
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDiscountFrom()
    {
        return $this->discount_from;
    }

    /**
     * @param \DateTime $discount_from
     * @return $this
     */
    public function setDiscountFrom($discount_from)
    {
        $this->discount_from = $discount_from;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDiscountTo()
    {
        return $this->discount_to;
    }

    /**
     * @param \DateTime $discount_to
     * @return $this
     */
    public function setDiscountTo($discount_to)
    {
        $this->discount_to = $discount_to;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMetaDescription()
    {
        return $this->meta_description;
    }

    /**
     * @param string $meta_description
     * @return $this
     */
    public function setMetaDescription($meta_description)
    {
        $this->meta_description = $meta_description;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMetaKeywords()
    {
        return $this->meta_keywords;
    }

    /**
     * @param string $meta_keywords
     * @return $this
     */
    public function setMetaKeywords($meta_keywords)
    {
        $this->meta_keywords = $meta_keywords;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @param string $sku
     * @return $this
     */
    public function setSku($sku)
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param float $weight
     * @return $this
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param string $note
     * @return $this
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

        /**
     * @return bool|null
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return $this
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param float $quantity
     * @return $this
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function isBestSeller()
    {
        return $this->best_seller;
    }

    /**
     * @param bool $best_seller
     * @return $this
     */
    public function setBestSeller($best_seller)
    {
        $this->best_seller = $best_seller;

        return $this;
    }

    /**
     * @return bool
     */
    public function isShowWeb()
    {
        return $this->show_web;
    }

    /**
     * @param bool $show_web
     * @return $this
     */
    public function setShowWeb($show_web)
    {
        $this->show_web = $show_web;

        return $this;
    }
}