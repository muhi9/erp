<?php

namespace UsersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use BaseBundle\Traits\BlameStampableEntity;
use BaseBundle\Traits\SoftDeleteableEntity;

/**
 * UserContact
 *
 * @ORM\Table(name="user_contact")
 * @ORM\Entity(repositoryClass="UsersBundle\Repository\UserContactRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class UserContact {
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
     * @ORM\ManyToOne(targetEntity="UsersBundle\Entity\UserPersonalInfo")
     * @ORM\JoinColumn(name="personalInfo", referencedColumnName="id", nullable=true) 
     * @Assert\NotBlank(message="user.form.contact.error.personalInfo_is_blank")
     */
    private $personalInfo;

    /**
     *
     * @ORM\ManyToOne(targetEntity="WorkPlaceBundle\Entity\WorkPlace")
     * @ORM\JoinColumn(name="workplace", referencedColumnName="id", nullable=true) 
     * @Assert\NotBlank(message="user.form.contact.error.workplace_is_blank")
     */
    private $workplace;

    /**
     * @ORM\ManyToOne(targetEntity="BaseBundle\Entity\BaseNoms")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="user.form.contact.error.contactType_is_blank")
     */
    private $contactType;

    /**
     * @ORM\ManyToOne(targetEntity="BaseBundle\Entity\BaseNoms")
     * @ORM\JoinColumn(nullable=true)
     */
    private $emergencyContactBnomPhoneType;

    /**
     * @ORM\ManyToOne(targetEntity="BaseBundle\Entity\BaseNoms")
     * @ORM\JoinColumn(nullable=true)
     */
    private $emergencyContactBnomEmailType;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $info1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $info2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $info3;

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
     * Set info1.
     *
     * @param string $info1
     *
     * @return UserContact
     */
    public function setInfo1($info1)
    {
        $this->info1 = $info1;

        return $this;
    }

    /**
     * Get info1.
     *
     * @return string
     */
    public function getInfo1()
    {
        return $this->info1;
    }

    /**
     * Set info2.
     *
     * @param string $info2
     *
     * @return UserContact
     */
    public function setInfo2($info2)
    {
        $this->info2 = $info2;

        return $this;
    }

    /**
     * Get info2.
     *
     * @return string
     */
    public function getInfo2()
    {
        return $this->info2;
    }

    /**
     * Set info3.
     *
     * @param string $info3
     *
     * @return UserContact
     */
    public function setInfo3($info3)
    {
        $this->info3 = $info3;

        return $this;
    }

    /**
     * Get info3.
     *
     * @return string
     */
    public function getInfo3()
    {
        return $this->info3;
    }

    /**
     * Set personalInfo.
     *
     * @param \UsersBundle\Entity\UserPersonalInfo $personalInfo
     *
     * @return UserContact
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
     * @return UserContact
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
     * Set emergencyContactBnomPhoneType.
     *
     * @param \BaseBundle\Entity\BaseNoms $emergencyContactBnomPhoneType
     *
     * @return UserContact
     */
    public function setEmergencyContactBnomPhoneType(\BaseBundle\Entity\BaseNoms $emergencyContactBnomPhoneType)
    {
        $this->emergencyContactBnomPhoneType = $emergencyContactBnomPhoneType;

        return $this;
    }

    /**
     * Get emergencyContactBnomPhoneType.
     *
     * @return \BaseBundle\Entity\BaseNoms
     */
    public function getEmergencyContactBnomPhoneType()
    {
        return $this->emergencyContactBnomPhoneType;
    }

    /**
     * Set emergencyContactBnomEmailType.
     *
     * @param \BaseBundle\Entity\BaseNoms $emergencyContactBnomEmailType
     *
     * @return UserContact
     */
    public function setEmergencyContactBnomEmailType(\BaseBundle\Entity\BaseNoms $emergencyContactBnomEmailType)
    {
        $this->emergencyContactBnomEmailType = $emergencyContactBnomEmailType;

        return $this;
    }

    /**
     * Get emergencyContactBnomEmailType.
     *
     * @return \BaseBundle\Entity\BaseNoms
     */
    public function getEmergencyContactBnomEmailType()
    {
        return $this->emergencyContactBnomEmailType;
    }
}