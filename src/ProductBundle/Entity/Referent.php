<?php

namespace ProductBundle\Entity;

use App\Repository\ReferentRepository;
use Doctrine\ORM\Mapping as ORM;
use BaseBundle\Traits\BlameStampableEntity;
use BaseBundle\Traits\SoftDeleteableEntity;


/**
 * Referent
 *
 * @ORM\Table(name="referent")
 * @ORM\Entity(repositoryClass="ProductBundle\Repository\ReferentRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class Referent
{
    use BlameStampableEntity;
    use SoftDeleteableEntity;

     /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="ProductBundle\Entity\Product)
     * @ORM\JoinColumn(name="product", referencedColumnName="id", nullable=true)
     */
    private $product;

    /**
     * @ORM\Column(length=255)
     * @var string|null
     */
    private $referent;

    /**
     * @ORM\Column(length=255)
     * @var string|null
     */
    private $referent_normalize;

    /**
     * @ORM\OneToOne(cascade={"persist", "remove"})
     * @var Manufacture|null
     */
    private $manufacture;

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
    public function getReferent()
    {
        return $this->referent;
    }

    /**
     * @param string $referent
     * @return $this
     */
    public function setReferent($referent)
    {
        $this->referent = $referent;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getReferentNormalize()
    {
        return $this->referent_normalize;
    }

    /**
     * @param string $referent_normalize
     * @return $this
     */
    public function setReferentNormalize($referent_normalize)
    {
        $this->referent_normalize = $referent_normalize;

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

        return $this;
    }
}