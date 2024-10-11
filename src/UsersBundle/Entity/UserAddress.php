<?php

namespace UsersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use BaseBundle\Traits\BlameStampableEntity;
use BaseBundle\Traits\SoftDeleteableEntity;

/**
 * UserAddress
 *
 * @ORM\Table(name="user_address")
 * @ORM\Entity(repositoryClass="UsersBundle\Repository\UserAddressRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class UserAddress {
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
     *
     * @ORM\ManyToOne(targetEntity="UsersBundle\Entity\UserPersonalInfo", inversedBy="addresse")
     * @ORM\JoinColumn(name="personalInfo", referencedColumnName="id", nullable=true)
     * @Assert\NotBlank(message="users.form.error.address.personalInfo_is_blank")
     */
    private $personalInfo;

      /**
     *
     * @ORM\ManyToOne(targetEntity="UsersBundle\Entity\UserPersonalInfo", inversedBy="addresse")
     * @ORM\JoinColumn(name="workplace", referencedColumnName="id", nullable=true)
     * @Assert\NotBlank(message="users.form.error.address.workplace_is_blank")
     */
    private $workplaces;

    /**
     * @ORM\ManyToOne(targetEntity="BaseBundle\Entity\BaseNoms")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="users.form.error.address.contactType_is_blank")
     */
    private $contactType;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="users.form.error.address.address1_is_blank")
     */
    private $address1;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="users.form.error.address.address1Phonetic_is_blank")
     */
    private $address1Phonetic;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address2Phonetic;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="users.form.error.address.place_is_blank")
     */
    private $place;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="users.form.error.address.placePhonetic_is_blank")
     */
    private $placePhonetic;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $postcode;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="users.form.error.address.postcode_is_blank")
     */
    private $municipality;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="users.form.error.address.municipality_is_blank")
     */
    private $municipalityPhonetic;

    /**
     * @ORM\ManyToOne(targetEntity="BaseBundle\Entity\Country")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=false)
     * @Assert\NotBlank(message="users.form.error.address.country_is_blank")
     */
    private $country;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set address1.
     *
     * @param string $address1
     *
     * @return UserAddress
     */
    public function setAddress1($address1)
    {
        $this->address1 = $address1;

        return $this;
    }

    /**
     * Get address1.
     *
     * @return string
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * Set address2.
     *
     * @param string|null $address2
     *
     * @return UserAddress
     */
    public function setAddress2($address2 = null)
    {
        $this->address2 = $address2;

        return $this;
    }

    /**
     * Get address2.
     *
     * @return string|null
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * Set place.
     *
     * @param string $place
     *
     * @return UserAddress
     */
    public function setPlace($place)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Get place.
     *
     * @return string
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Set postcode.
     *
     * @param string $postcode
     *
     * @return UserAddress
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;

        return $this;
    }

    /**
     * Get postcode.
     *
     * @return string
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * Set municipality.
     *
     * @param string $municipality
     *
     * @return UserAddress
     */
    public function setMunicipality($municipality)
    {
        $this->municipality = $municipality;

        return $this;
    }

    /**
     * Get municipality.
     *
     * @return string
     */
    public function getMunicipality()
    {
        return $this->municipality;
    }

    /**
     * Set personalInfo.
     *
     * @param \UsersBundle\Entity\UserPersonalInfo $personalInfo
     *
     * @return UserAddress
     */
    public function setPersonalInfo(\UsersBundle\Entity\UserPersonalInfo $personalInfo)
    {
        $this->personalInfo = $personalInfo;

        return $this;
    }

    /**
     * Get personalInfo.
     *
     * @return \UsersBundle\Entity\UserPersonalInfo
     */
    public function getPersonalInfo()
    {
        return $this->personalInfo;
    }

    /**
     * Set contactType.
     *
     * @param \BaseBundle\Entity\BaseNoms $contactType
     *
     * @return UserAddress
     */
    public function setContactType(\BaseBundle\Entity\BaseNoms $contactType)
    {
        $this->contactType = $contactType;

        return $this;
    }

    /**
     * Get contactType.
     *
     * @return \BaseBundle\Entity\BaseNoms
     */
    public function getContactType()
    {
        return $this->contactType;
    }

    /**
     * Set country.
     *
     * @param \BaseBundle\Entity\Country $country
     *
     * @return UserAddress
     */
    public function setCountry(\BaseBundle\Entity\Country $country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country.
     *
     * @return \BaseBundle\Entity\Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set address1Phonetic.
     *
     * @param string $address1Phonetic
     *
     * @return UserAddress
     */
    public function setAddress1Phonetic($address1Phonetic)
    {
        $this->address1Phonetic = $address1Phonetic;

        return $this;
    }

    /**
     * Get address1Phonetic.
     *
     * @return string
     */
    public function getAddress1Phonetic()
    {
        return $this->address1Phonetic;
    }

    /**
     * Set address2Phonetic.
     *
     * @param string|null $address2Phonetic
     *
     * @return UserAddress
     */
    public function setAddress2Phonetic($address2Phonetic = null)
    {
        $this->address2Phonetic = $address2Phonetic;

        return $this;
    }

    /**
     * Get address2Phonetic.
     *
     * @return string|null
     */
    public function getAddress2Phonetic()
    {
        return $this->address2Phonetic;
    }

    /**
     * Set placePhonetic.
     *
     * @param string $placePhonetic
     *
     * @return UserAddress
     */
    public function setPlacePhonetic($placePhonetic)
    {
        $this->placePhonetic = $placePhonetic;

        return $this;
    }

    /**
     * Get placePhonetic.
     *
     * @return string
     */
    public function getPlacePhonetic()
    {
        return $this->placePhonetic;
    }

    /**
     * Set municipalityPhonetic.
     *
     * @param string $municipalityPhonetic
     *
     * @return UserAddress
     */
    public function setMunicipalityPhonetic($municipalityPhonetic)
    {
        $this->municipalityPhonetic = $municipalityPhonetic;

        return $this;
    }

    /**
     * Get municipalityPhonetic.
     *
     * @return string
     */
    public function getMunicipalityPhonetic()
    {
        return $this->municipalityPhonetic;
    }
}
