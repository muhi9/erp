<?php

namespace WorkPlaceBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Employee
 *
 * @ORM\Table(name="employee")
 * @ORM\Entity(repositoryClass="WorkPlaceBundle\Repository\EmployeeRepository")
 */
class Employee
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
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="middleName", type="string", length=255)
     */
    private $middleName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="identificationNumber", type="string", length=255)
     */
    private $identificationNumber;

    /**
     * @ORM\ManyToOne(targetEntity="BaseBundle\Entity\BaseNoms")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $posion;
    
    /**
    * @ORM\Column(type="boolean")
    */
    private $active = true;

     /**
     *
     * @ORM\ManyToOne(targetEntity="WorkPlaceBundle\Entity\WorkPlace", inversedBy="employees")
     * @ORM\JoinColumn(name="workplace", referencedColumnName="id", nullable=true)
     * @Assert\NotBlank(message="workplace.form.error.employees.workplace_is_blank")
     */
    private $workplace;



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
     * Set firstName.
     *
     * @param string $firstName
     *
     * @return Employee
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set middleName.
     *
     * @param string $middleName
     *
     * @return Employee
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;

        return $this;
    }

    /**
     * Get middleName.
     *
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * Set lastName.
     *
     * @param string $lastName
     *
     * @return Employee
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set identificationNumber.
     *
     * @param string $identificationNumber
     *
     * @return Employee
     */
    public function setIdentificationNumber($identificationNumber)
    {
        $this->identificationNumber = $identificationNumber;

        return $this;
    }

    /**
     * Get identificationNumber.
     *
     * @return string
     */
    public function getIdentificationNumber()
    {
        return $this->identificationNumber;
    }

    /**
     * Set posion.
     *
     * @param string $posion
     *
     * @return Employee
     */
    public function setPosion($posion)
    {
        $this->posion = $posion;

        return $this;
    }

    /**
     * Get posion.
     *
     * @return string
     */
    public function getPosion()
    {
        return $this->posion;
    }

    /**
     * Set active.
     *
     * @param string $active
     *
     * @return Employee
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active.
     *
     * @return string
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set workplace.
     *
     * @param string $workplace
     *
     * @return Employee
     */
    public function setWorkplace($workplace)
    {
        $this->workplace = $workplace;

        return $this;
    }

    /**
     * Get workplace.
     *
     * @return string
     */
    public function getWorkplace()
    {
        return $this->workplace;
    }

    /**
     * Set company.
     *
     * @param string $company
     *
     * @return Employee
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company.
     *
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }
}
