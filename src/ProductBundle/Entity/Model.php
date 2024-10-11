<?php

namespace ProductBundle\Entity;

use App\Repository\ModelRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Model
 *
 * @ORM\Table(name="model")
 * @ORM\Entity(repositoryClass="ProductBundle\Repository\ModelRepository")
 */
class Model
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
     * @ORM\ManyToOne(targetEntity="ProductBundle\Entity\Make")
     * @ORM\JoinColumn(name="make", referencedColumnName="id", nullable=true)
     */
    private $make;

    /**
     * @ORM\Column(length=255)
     * @var string|null
     */
    private $vehichle_type;

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

    /**
     * @return Make|null
     */
    public function getMake()
    {
        return $this->make;
    }

    /**
     * @param Make|null $make
     * @return $this
     */
    public function setMake($make)
    {
        $this->make = $make;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getVehichleType()
    {
        return $this->vehichle_type;
    }

    /**
     * @param string $vehichle_type
     * @return $this
     */
    public function setVehichleType($vehichle_type)
    {
        $this->vehichle_type = $vehichle_type;

        return $this;
    }
}