<?php

namespace BaseBundle\Entity;
use BaseBundle\Entity\BaseNoms;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use BaseBundle\Traits\BlameStampableEntity;
use BaseBundle\Traits\SoftDeleteableEntity;

/**
 * BaseNomsExtra
 *
 * @ORM\Table(name="base_noms_extra")
 * @ORM\Entity(repositoryClass="BaseBundle\Repository\BaseNomsExtraRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class BaseNomsExtra
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
     * @var string
     *
     * @ORM\Column(name="baseKey", type="string", length=255)
     */
    private $baseKey;

    /**
     * @var array
     *
     * @ORM\Column(name="baseValue", type="text", nullable=true)
     */
    private $baseValue;
    /**
     * @ORM\ManyToOne(targetEntity="BaseNoms", inversedBy="extra")
     * @ORM\JoinColumn(name="basenom_id", referencedColumnName="id")
     */
    private $basenom;


    public function __construct(BaseNoms $basenom = null) {
        if ($basenom !== null)
            $this->setBasenom($basenom);
    }
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set baseKey
     *
     * @param integer $baseKey
     *
     * @return BaseNomsExtra
     */
    public function setBaseKey($baseKey)
    {
        $this->baseKey = $baseKey;

        return $this;
    }

    /**
     * Get baseKey
     *
     * @return int
     */
    public function getBaseKey()
    {
        return $this->baseKey;
    }

    /**
     * Set baseValue
     *
     * @param array $baseValue
     *
     * @return BaseNomsExtra
     */
    public function setBaseValue($baseValue)
    {
        $this->baseValue = $baseValue===''?null:$baseValue;

        return $this;
    }

    /**
     * Get baseValue
     *
     * @return array
     */
    public function getBaseValue()
    {
        return $this->baseValue;
    }

    public function setBasenom(BaseNoms $basenom)
    {
        $this->basenom = $basenom;

        return $this;
    }

    public function getBasenom()
    {
        return $this->basenom;
    }
}

