<?php

namespace ProductBundle\Entity;

use App\Repository\ProductModelRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 * ProductModel
 *
 * @ORM\Table(name="productModel")
 * @ORM\Entity(repositoryClass="ProductBundle\Repository\ProductModelRepository")
 */
class ProductModel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column
     * @var int|null
     */
    private $id;

     /**
     * @ORM\ManyToOne(targetEntity="ProductBundle\Entity\Product)
     * @ORM\JoinColumn(name="product", referencedColumnName="id", nullable=true)
     */
    private $product;

     /**
     * @ORM\ManyToOne(targetEntity="ProductBundle\Entity\Model)
     * @ORM\JoinColumn(name="model", referencedColumnName="id", nullable=true)
     */
    private $model;

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Products|null
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param Products|null $product
     * @return $this
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return Model|null
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param Model|null $model
     * @return $this
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }
}