<?php
namespace BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use BaseBundle\Traits\BlameStampableEntity;
use BaseBundle\Traits\SoftDeleteableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;



/**
 * @ORM\Entity(repositoryClass="BaseBundle\Repository\NomTypeRepository")
 * @UniqueEntity("parentNameKey")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class NomType
{
    use BlameStampableEntity;
    use SoftDeleteableEntity;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Name cant be empty,")
     */
    private $name;

    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Name key cant be empty")
     * @Assert\Regex("/[a-z]{1,7}\.[a-z0-9]{2,93}/", message="Your key must be this format: xx.xxxxxx")
     */
    private $nameKey;

    /**
     * @ORM\ManyToOne(targetEntity="NomType")
     * @ORM\JoinColumn(name="parent_name_key1", referencedColumnName="name_key")
     */
    private $parentNameKey1;

    /**
     * @ORM\OneToMany(targetEntity="BaseBundle\Entity\NomType", mappedBy="parent")
     **/
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="BaseBundle\Entity\NomType", inversedBy="children")
     * @ORM\JoinColumn(name="parent_name_key1", referencedColumnName="name_key"))
     */
    private $parent;

    /**
     * @ORM\ManyToOne(targetEntity="NomType")
     * @ORM\Column(type="string", length=255, nullable=true, unique=true)
     */
    private $parentNameKey;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status = true;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $descr;
    /**
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $extraField;


    public function getId()
    {
        return $this->nameKey;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    public function getNameKey()
    {
        return $this->nameKey;
    }

    public function setNameKey(string $nameKey)
    {
        $this->nameKey = $nameKey;

        return $this;
    }
/*
    public function getParentNameKey()
    {
        return $this->parentNameKey;
    }
*/
    public function setparentNameKey(NomType $parentNameKey)
    {
        $this->parentNameKey = $parentNameKey;

        return $this;
    }

    public function getParentNameKey1()
    {
        return $this->parentNameKey1;
    }

    public function setparentNameKey1(NomType $parentNameKey1)
    {
        $this->parentNameKey1 = $parentNameKey1;

        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus(bool $status)
    {
        $this->status = $status;

        return $this;
    }

    public function getDescr()
    {
        return $this->descr;
    }

    public function setDescr($descr)
    {
        $this->descr = $descr;

        return $this;
    }

    public function getExtraField()
    {
        return $this->extraField;
    }

    public function setExtraField($extraField)
    {
        $this->extraField = $extraField;

        return $this;
    }


     function __toString() {
        $i=0;
        $keys[] = $this->nameKey;
        $pkk = $this->parentNameKey;
        while($pkk != null) {
            $keys[] = $this->parentNameKey;

            if (is_object($pkk)&&$pkk->parentNameKey != null){
                $pkk = $pkk->parentNameKey->parentNameKey;
            }else{
               $pkk =null;
            }


        }
        $keys = array_reverse($keys);

        return join ('=>', $keys);
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get parentNameKey.
     *
     * @return string|null
     */
    public function getParentNameKey()
    {
        return $this->parentNameKey;
    }

    /**
     * Add child.
     *
     * @param \BaseBundle\Entity\NomType $child
     *
     * @return NomType
     */
    public function addChild(\BaseBundle\Entity\NomType $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child.
     *
     * @param \BaseBundle\Entity\NomType $child
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeChild(\BaseBundle\Entity\NomType $child)
    {
        return $this->children->removeElement($child);
    }

    /**
     * Get children.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent.
     *
     * @param \BaseBundle\Entity\NomType|null $parent
     *
     * @return NomType
     */
    public function setParent(\BaseBundle\Entity\NomType $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent.
     *
     * @return \BaseBundle\Entity\NomType|null
     */
    public function getParent()
    {
        return $this->parent;
    }
}
