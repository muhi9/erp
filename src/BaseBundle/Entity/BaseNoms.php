<?php

namespace BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use BaseBundle\Entity\NomType;
use BaseBundle\Entity\BaseNomsExtra;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use BaseBundle\Traits\BlameStampableEntity;
use BaseBundle\Traits\SoftDeleteableEntity;

/**
 * @ORM\Table(indexes={@ORM\Index(name="type_idx", columns={"type"}),@ORM\Index(name="order_idx", columns={"bnorder"})})
 * @ORM\Entity(repositoryClass="BaseBundle\Repository\BaseNomsRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @UniqueEntity(
 *     fields={"parent_id", "name", "type"},
 *     errorPath="type",
 *     message="Pair parent-name-type for {{ value }} value already exists.",
 *     repositoryMethod="checkDuplicate",
 *     ignoreNull=false
 * )
 */
class BaseNoms
{
    use BlameStampableEntity;
    use SoftDeleteableEntity;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="BaseNoms")
     * @ORM\Column(type="integer", nullable=true)
     */
    private $parent_id;

    /**
     * @ORM\OneToMany(targetEntity="BaseBundle\Entity\BaseNoms", mappedBy="parent")
     **/
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="BaseBundle\Entity\BaseNoms", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id"))
     */
    private $parent;

    /**
     * @ORM\ManyToMany(targetEntity="BaseBundle\Entity\BaseNoms")
     * @ORM\JoinTable(name="base_noms_links")
     **/
    private $links;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Name can not be empty.")
     */
    private $name;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $status = true;

    /**
     * @ORM\ManyToOne(targetEntity="BaseBundle\Entity\NomType")
     * @ORM\JoinColumn(name="type", referencedColumnName="name_key", nullable=false))
     * @Assert\NotBlank(message="Type can not be empty.")
     */
    private $type;

     /**
     * @ORM\Column(name="bnorder", type="integer")
     */

    private $order = 1;

    /**
     * @ORM\OneToMany(targetEntity="BaseNomsExtra",mappedBy="basenom", cascade={"all"}, orphanRemoval=true )
     */
    private $extra;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bnomKey;
    private $parentName;

    public function __construct() {
        $this->extra = new ArrayCollection();
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getExtra()
    {
        return $this->extra;
    }

    public function clearExtra()
    {
        $this->extra->clear();
    }

    public function getExtraValues() {
        return $this->extra->getValues();
    }
    public function getExtraArray() {
        $extra = [];

        foreach ($this->extra->getValues() as $key => $value) {
            $extra[$value->getBaseKey()] = $value->getBaseValue();
        }

        return $extra;
    }
    public function getExtraValue($key, $default = null) {
        $temp = $this->getExtraArray();
        return array_key_exists($key, $temp) ? $temp[$key] : $default;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getParentId()
    {
        return $this->parent_id;
    }

    public function setParentId($parent_id): ?self
    {
        $parent_id=!empty($parent_id)?(int)$parent_id:null;
        $this->parent_id = is_object($parent_id)?$parent_id->getId():$parent_id;

        return $this;
    }


    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(self $parent): self
    {
        $this->parent = $parent;
        $this->parent_id = $parent->getId();
        return $this;
    }



    public function getName(): ?string
    {
        return $this->name;
    }

    public function getTreeName(): ?string
    {
        //return join(' &raquo; ', $this->getTreeArray());
        return join(' » ', $this->getTreeArray());
    }

    public function getTreeArray(): array
    {
        $names = [$this->getName()];
        $maxLimit = 100;
        $limit = 0;
        $parent = $this;
        while ($limit<$maxLimit) {
            $parent = $parent->getParent();
            if ($parent == null) break;
            $names[] = $parent->getName();
            $limit++;
        }
        return array_reverse($names);
    }

    public function getTreeObjectArray(): array
    {
        $names = [$this];
        $maxLimit = 100;
        $limit = 0;
        $parent = $this;
        while ($limit<$maxLimit) {
            $parent = $parent->getParent();
            if ($parent == null) break;
            $names[] = $parent;
            $limit++;
        }
        return array_reverse($names);
    }

    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }
    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return BaseNoms
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    public function getType(): ?\BaseBundle\Entity\NomType
    {
        return $this->type;
    }

    public function setType(\BaseBundle\Entity\NomType $type): self
    {

        $this->type = $type;
        return $this;
    }

    public function __toString() {

        return $this->name;
    }

    /**
     * Add child.
     *
     * @param \BaseBundle\Entity\BaseNoms $child
     *
     * @return BaseNoms
     */
    public function addChild(\BaseBundle\Entity\BaseNoms $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child.
     *
     * @param \BaseBundle\Entity\BaseNoms $child
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeChild(\BaseBundle\Entity\BaseNoms $child)
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
     * Add extra.
     *
     * @param \BaseBundle\Entity\BaseNomsExtra $extra
     *
     * @return BaseNoms
     */
    public function addExtra(\BaseBundle\Entity\BaseNomsExtra $extra)
    {
        $this->extra[] = $extra;

        return $this;
    }

    /**
     * Remove extra.
     *
     * @param \BaseBundle\Entity\BaseNomsExtra $extra
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeExtra(\BaseBundle\Entity\BaseNomsExtra $extra)
    {
        return $this->extra->removeElement($extra);
    }

    /**
     * Add link.
     *
     * @param \BaseBundle\Entity\BaseNoms $link
     *
     * @return BaseNoms
     */
    public function addLink(\BaseBundle\Entity\BaseNoms $link)
    {
        $this->links[] = $link;

        return $this;
    }

    /**
     * Remove link.
     *
     * @param \BaseBundle\Entity\BaseNoms $link
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeLink(\BaseBundle\Entity\BaseNoms $link)
    {
        return $this->links->removeElement($link);
    }

    /**
     * Get links.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * Set bnomKey.
     *
     * @param string|null $bnomKey
     *
     * @return BaseNoms
     */
    public function setBnomKey($bnomKey = null)
    {
        if (empty($bnomKey)) {
            $this->bnomKey = $this->getBnomKeyAuto();
        } else
            $this->bnomKey = $bnomKey;

        return $this;
    }

    public function getBnomKeyAuto() {
        $path = $this->getTreeArray();
        foreach ($path as $k => $val) {
            $path[$k] = mb_strtolower($val);
        }
        return join('-', $path);
    }

    public function setBnomKeyAuto() {
        $this->setBnomKey($this->getBnomKeyAuto());
    }

    /**
     * Get bnomKey.
     *
     * @return string|null
     */
    public function getBnomKey()
    {
        return $this->bnomKey;
    }

    public function getParentName()
    {
        return $this->parent->getName();
    }
}
