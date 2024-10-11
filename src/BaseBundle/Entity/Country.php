<?php

namespace BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use BaseBundle\Traits\BlameStampableEntity;
use BaseBundle\Traits\SoftDeleteableEntity;

/**
 * Country
 *
 * @ORM\Table(name="country")
 * @ORM\Entity(repositoryClass="BaseBundle\Repository\CountryRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @UniqueEntity(fields={"iso2l"}, message="form.iso2l.unique")
 * @UniqueEntity(fields={"iso3l"}, message="form.iso3l.unique")
 */
class Country
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @Assert\NotBlank(message="form.country.name.notempty")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="capital", type="string", length=255, nullable=true)
     */
     private $capital;

    /**
     * @var string
     *
     * @ORM\Column(name="tz", type="string", length=255, nullable=true)
     */
     private $tz;
     private $timezone; //return string name + tz 
     
     /**
     * @var string
     *
     * @ORM\Column(name="currency", type="string", length=255, nullable=true)
     */
     private $curr;

     /**
     * @var string
     *
     * @ORM\Column(name="phonecode", type="string", length=255, nullable=true)
     * @Assert\Regex("/[+]*[0-9]/", message="The value {{ value }} is not a valid.")
     */
     private $phonecode;

    /**
     * @var string
     *
     * @ORM\Column(name="iso_2l", type="string", length=2, unique=true)
     * @Assert\NotBlank(message="form.country.iso2l.notempty")
     * @Assert\Length(
     *      min = 2,
     *      max = 2,
     *      minMessage = "form.country.iso2l.length",
     *      maxMessage = "form.country.iso2l.length"
     * )
     * 
     */
    private $iso2l;

    /**
     * @var string
     *
     * @ORM\Column(name="iso_3l", type="string", length=3, unique=true)
     * @Assert\NotBlank(message="form.country.iso3l.notempty")
     * @Assert\Length(
     *      min = 3,
     *      max = 3,
     *      minMessage = "form.country.iso3l.length",
     *      maxMessage = "form.country.iso3l.length"
     * )
     */
    private $iso3l;

    /**
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean")
     */
    private $status = true;

    /**
     * @var string
     *
     * @ORM\Column(name="nativeName", type="string", length=255, nullable=true)
     */
    private $nativeName;


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
     * @return Country
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
     * Set name
     *
     * @param string $capital
     *
     * @return Country
     */
    public function setCapital($capital)
    {
        $this->capital = $capital;

        return $this;
    }

    /**
     * Get capital
     *
     * @return string
     */
    public function getCapital()
    {
        return $this->capital;
    }

    /**
     * Set name
     *
     * @param string $tz
     *
     * @return Country
     */
    public function setTz($tz)
    {
        $this->tz = $tz;

        return $this;
    }

    /**
     * Get tz(time zone)
     *
     * @return string
     */
    public function getTz()
    {
        return $this->tz;
    }

    /**
     * Get time zone
     *
     * @return string
     */
    public function getTimezone()
    {
        return $this->name . ' ' . $this->tz;
    }

    /**
     * Set name
     *
     * @param string $curr
     *
     * @return Country
     */
    public function setCurr($curr)
    {
        $this->curr = $curr;

        return $this;
    }

    /**
     * Get curr( currency)
     *
     * @return string
     */
    public function getCurr()
    {
        return $this->curr;
    }


    /**
     * Set name
     *
     * @param string $phonecode
     *
     * @return Country
     */
    public function setPhonecode($phonecode)
    {
        $this->phonecode = $phonecode;

        return $this;
    }

    /**
     * Get phonecode
     *
     * @return string
     */
    public function getPhonecode()
    {
        return $this->phonecode;
    }







    /**
     * Set iso2l
     *
     * @param string $iso2l
     *
     * @return Country
     */
    public function setIso2l($iso2l)
    {
        $this->iso2l = $iso2l;

        return $this;
    }

    /**
     * Get iso2l
     *
     * @return string
     */
    public function getIso2l()
    {
        return $this->iso2l;
    }

    /**
     * Set iso3l
     *
     * @param string $iso3l
     *
     * @return Country
     */
    public function setIso3l($iso3l)
    {
        $this->iso3l = $iso3l;

        return $this;
    }

    /**
     * Get iso3l
     *
     * @return string
     */
    public function getIso3l()
    {
        return $this->iso3l;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Country
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
     * Set nativeName
     *
     * @param string $nativeName
     *
     * @return Country
     */
    public function setNativeName($nativeName)
    {
        $this->nativeName = $nativeName;

        return $this;
    }

    /**
     * Get nativeName
     *
     * @return string
     */
    public function getNativeName()
    {
        return $this->nativeName;
    }
    public function __toString() {
        return $this->getName().''.(!empty($this->getNativeName())?'('.$this->getNativeName().')':'');
    }
}

