<?php

namespace WorkPlaceBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * WorkPlace
 *
 * @ORM\Table(name="work_place")
 * @ORM\Entity(repositoryClass="WorkPlaceBundle\Repository\WorkPlaceRepository")
 */
class WorkPlace
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
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    
    /**
     * @ORM\OneToMany(targetEntity="UsersBundle\Entity\UserAddress", mappedBy="workplace", orphanRemoval=true, cascade={"persist"}))
     * @Assert\Valid()
     */
    private $addresse;

    /**
     * @ORM\OneToMany(targetEntity="UsersBundle\Entity\UserContact", mappedBy="workplace", orphanRemoval=true, cascade={"persist"})
     * @Assert\Valid()
     */
    private $phone;

    /**
     * @ORM\OneToMany(targetEntity="UsersBundle\Entity\UserContact", mappedBy="workplace", orphanRemoval=true, cascade={"persist"})
     * @Assert\Valid()
     */
    private $mail;

    /**
     * @ORM\OneToMany(targetEntity="UsersBundle\Entity\UserContact", mappedBy="workplace", orphanRemoval=true, cascade={"persist"})
     * @Assert\Valid()
     */
    private $url;

    /**
     * @ORM\OneToMany(targetEntity="UsersBundle\Entity\UserContact", mappedBy="workplace", orphanRemoval=true, cascade={"persist"})
     * @Assert\Valid()
     */
    private $soc;

    /**
     * @ORM\OneToMany(targetEntity="UsersBundle\Entity\UserContact", mappedBy="workplace", orphanRemoval=true, cascade={"persist"})
     * @Assert\Valid()
     */
    private $im;

 

     /**
     * @ORM\ManyToOne(targetEntity="UsersBundle\Entity\UserPersonalInfo")
     * @ORM\JoinColumn(nullable=true)
     */
    private $company;

      /**
     * @ORM\OneToMany(targetEntity="WorkPlaceBundle\Entity\Employee", mappedBy="workplace", orphanRemoval=true, cascade={"persist"})
     * @Assert\Valid()
     */
    private $employees;

     /**
     * @ORM\OneToMany(targetEntity="UsersBundle\Entity\UserBankAccount", mappedBy="workplace", orphanRemoval=true, cascade={"persist"})
     * @Assert\Valid()
     */
    private $bank;
    
    /**
     * @var string
     *
     * @ORM\Column(name="invoice_prefix", type="string", length=255)
     */
    private $invoicePrefix;

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
     * Set name.
     *
     * @param string|null $name
     *
     * @return WorkPlace
     */
    public function setName($name = null)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set address.
     *
     * @param string $address
     *
     * @return WorkPlace
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address.
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set contact.
     *
     * @param string $contact
     *
     * @return WorkPlace
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact.
     *
     * @return string
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set parent.
     *
     * @param string $parent
     *
     * @return WorkPlace
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent.
     *
     * @return string
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set employees.
     *
     * @param string $employees
     *
     * @return WorkPlace
     */
    public function setEmployees($employees)
    {
        $this->employees = $employees;

        return $this;
    }

    /**
     * Get employees.
     *
     * @return string
     */
    public function getEmployees()
    {
        return $this->employees;
    }

    /**
     * Set invoicePrefix.
     *
     * @param string $invoicePrefix
     *
     * @return WorkPlace
     */
    public function setInvoicePrefix($invoicePrefix)
    {
        $this->invoicePrefix = $invoicePrefix;

        return $this;
    }

    /**
     * Get invoicePrefix.
     *
     * @return string
     */
    public function getInvoicePrefix()
    {
        return $this->invoicePrefix;
    }

    /**
     * Set bank.
     *
     * @param string $bank
     *
     * @return WorkPlace
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
}
