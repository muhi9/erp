<?php

namespace ProductBundle\Entity;

use App\Repository\OEMRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * OEM
 *
 * @ORM\Table(name="oem")
 * @ORM\Entity(repositoryClass="ProductBundle\Repository\OEMRepository")
 */
class OEM
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
     * @ORM\ManyToOne(targetEntity="ProductBundle\Entity\Product")
     * @ORM\JoinColumn(name="product", referencedColumnName="id", nullable=true)
     */
    private $product;

    /**
     * @ORM\Column(length=255)
     * @var string|null
     */
    private $oem;

    /**
     * @ORM\Column(length=255)
     * @var string|null
     */
    private $oem_normalize;

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
     * @return string|null
     */
    public function getOem()
    {
        return $this->oem;
    }

    /**
     * @param string $oem
     * @return $this
     */
    public function setOem($oem)
    {
        $this->oem = $oem;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getOemNormalize()
    {
        return $this->oem_normalize;
    }

    /**
     * @param string $oem_normalize
     * @return $this
     */
    public function setOemNormalize($oem_normalize)
    {
        $this->oem_normalize = $oem_normalize;

        return $this;
    }
}