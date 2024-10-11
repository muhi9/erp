<?php

namespace ProductBundle\Entity;

use App\Repository\ManufactureRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Manufacture
 *
 * @ORM\Table(name="manufacture")
 * @ORM\Entity(repositoryClass="ProductBundle\Repository\ManufactureRepository")
 */
class Manufacture
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
     * @ORM\Column(length=255)
     * @var string|null
     */
    private $name;

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

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}