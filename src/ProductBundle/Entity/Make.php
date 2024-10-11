<?php

namespace ProductBundle\Entity;

use App\Repository\MakeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Make
 *
 * @ORM\Table(name="make")
 * @ORM\Entity(repositoryClass="ProducteBundle\Repository\MakeRepository")
 */
class Make
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
     * @ORM\Column(length=255, nullable=true)
     * @var string|null
     */
    private $name_bg;

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
     * @return string|null
     */
    public function getNameBg()
    {
        return $this->name_bg;
    }

    public function setNameBg($name_bg = null)
    {
        $this->name_bg = $name_bg;

        return $this;
    }
}