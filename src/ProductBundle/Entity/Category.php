<?php

namespace ProductBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use ProductBundle\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass=ProductBundle\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="self", inversedBy="parent")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="self", mappedBy="category")
     */
    private $parent;

    public function __construct()
    {
        $this->parent = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return self|null
     */
    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory(self $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getParent()
    {
        return $this->parent;
    }

    public function addParent(self $parent)
    {
        if (!$this->parent->contains($parent)) {
            $this->parent->add($parent);
            $parent->setCategory($this);
        }

        return $this;
    }

    public function removeParent(self $parent)
    {
        if ($this->parent->removeElement($parent)) {
            // set the owning side to null (unless already changed)
            if ($parent->getCategory() === $this) {
                $parent->setCategory(null);
            }
        }

        return $this;
    }
}