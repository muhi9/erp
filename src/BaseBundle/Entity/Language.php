<?php

namespace BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use BaseBundle\Traits\BlameStampableEntity;
use BaseBundle\Traits\SoftDeleteableEntity;
/**
 * Language
 *
 * @ORM\Table(name="language")
 * @ORM\Entity(repositoryClass="BaseBundle\Repository\LanguageRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class Language
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
     * @ORM\Column(name="name", type="string", length=100)
     * @Assert\NotBlank(message="language.name.not_blank")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="name_local", type="string", length=100)
     * @Assert\NotBlank(message="language.name_local.not_blank")
     */
    private $nameLocal;

    /**
     * @var string
     *
     * @ORM\Column(name="ISO2", type="string", length=2)
     * @Assert\NotBlank(message="language.iso2.not_blank")
     */
    private $iSO2;

    /**
     * @var string
     *
     * @ORM\Column(name="ISO3", type="string", length=3)
     * @Assert\NotBlank(message="language.iso3.not_blank")
     */
    private $iSO3;

    /**
     * @var string
     *
     * @ORM\Column(name="encoding", type="string", length=15)
     * @Assert\NotBlank(message="language.encoding.not_blank")
     */
    private $encoding = 'en_US.UTF8';

    /**
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean")
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="flag", type="string", length=10, nullable=true)
     */
    private $flag;


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
     * Set name
     *
     * @param string $name
     *
     * @return Language
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set nameLocal
     *
     * @param string $nameLocal
     *
     * @return Language
     */
    public function setNameLocal($nameLocal)
    {
        $this->nameLocal = $nameLocal;

        return $this;
    }

    /**
     * Get nameLocal
     *
     * @return string
     */
    public function getNameLocal()
    {
        return $this->nameLocal;
    }

    /**
     * Set iSO2
     *
     * @param string $iSO2
     *
     * @return Language
     */
    public function setISO2($iSO2)
    {
        $this->iSO2 = $iSO2;

        return $this;
    }

    /**
     * Get iSO2
     *
     * @return string
     */
    public function getISO2()
    {
        return $this->iSO2;
    }

    /**
     * Set iSO3
     *
     * @param string $iSO3
     *
     * @return Language
     */
    public function setISO3($iSO3)
    {
        $this->iSO3 = $iSO3;

        return $this;
    }

    /**
     * Get iSO3
     *
     * @return string
     */
    public function getISO3()
    {
        return $this->iSO3;
    }

    /**
     * Set encoding
     *
     * @param string $encoding
     *
     * @return Language
     */
    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;

        return $this;
    }

    /**
     * Get encoding
     *
     * @return string
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Language
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return bool
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set flag
     *
     * @param string $flag
     *
     * @return Language
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;

        return $this;
    }

    /**
     * Get flag
     *
     * @return string
     */
    public function getFlag()
    {
        return $this->flag;
    }

    public function __toString() 
    {
        return $this->name.'-'.$this->iSO2;
    }
}

