<?php

namespace UsersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use BaseBundle\Traits\BlameStampableEntity;
use BaseBundle\Traits\SoftDeleteableEntity;

/**
 * UserBankAccount
 *
 * @ORM\Table(name="user_bank_account")
 * @ORM\Entity(repositoryClass="UsersBundle\Repository\UserBankAccountRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class UserBankAccount {
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
     * @Assert\NotBlank(message="user.form.bank.error.personalInfo_is_blank")
     */
    private $personalInfo;
     /**
     *
     * @ORM\ManyToOne(targetEntity="UsersBundle\Entity\UserPersonalInfo")
     * @ORM\JoinColumn(name="workplace", referencedColumnName="id", nullable=true) 
     * @Assert\NotBlank(message="user.form.bank.error.workplace_is_blank")
     */
    private $workplace;

    /**
     * @ORM\Column(type="string", length=200, nullable=false)
     * @Assert\NotBlank(message="user.form.bank.error.bank_is_blank")
     */
    private $bank;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     * @Assert\NotBlank(message="user.form.bank.error.iban_is_blank")
     */
    private $iban;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $bic;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $swift;

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
     * Set bank.
     *
     * @param string $bank
     *
     * @return UserBankAccount
     */
    public function setBank($bank)
    {
        $this->bank = $bank;

        return $this;
    }

    /**
     * Get bank.
     *
     * @return string
     */
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * Set iban.
     *
     * @param string $iban
     *
     * @return UserBankAccount
     */
    public function setIban($iban)
    {
        $this->iban = $iban;

        return $this;
    }

    /**
     * Get iban.
     *
     * @return string
     */
    public function getIban()
    {
        return $this->iban;
    }

    /**
     * Set bic.
     *
     * @param string|null $bic
     *
     * @return UserBankAccount
     */
    public function setBic($bic = null)
    {
        $this->bic = $bic;

        return $this;
    }

    /**
     * Get bic.
     *
     * @return string|null
     */
    public function getBic()
    {
        return $this->bic;
    }

    /**
     * Set swift.
     *
     * @param string|null $swift
     *
     * @return UserBankAccount
     */
    public function setSwift($swift = null)
    {
        $this->swift = $swift;

        return $this;
    }

    /**
     * Get swift.
     *
     * @return string|null
     */
    public function getSwift()
    {
        return $this->swift;
    }

    /**
     * Set personalInfo.
     *
     * @param \UsersBundle\Entity\UserPersonalInfo $personalInfo
     *
     * @return UserBankAccount
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
}
