<?php

namespace UsersBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use BaseBundle\Traits\BlameStampableEntity;
use BaseBundle\Traits\SoftDeleteableEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="UsersBundle\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, errorPath="email", message="users.form.uniq_mail")
 * @UniqueEntity(fields={"username"}, errorPath="username", message="users.form.uniq_username")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class User extends BaseUser
{
    use BlameStampableEntity;
    use SoftDeleteableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\OneToOne(targetEntity="UserPersonalInfo",  mappedBy="user")
     */
    private $info;




    public function __construct()
    {
        parent::__construct();
        // your own logic
        //$this->roles = array('ROLE_OPERATOR', 'ROLE_NATIONAL_ADMIN', 'ROLE_NATIONAL_ADMIN', 'ROLE_REGIONAL_ADMIN', 'ROLE_DIRECTOR');

    }

    public function getId()
    {
        return $this->id;
    }

    public function getInfo()
    {
        return $this->info;
    }

    public function setInfo($UserPersonalInfo)
    {
        $this->info = $UserPersonalInfo;
        return $this;
    }

    public function getFullName($official = false)
    {
        if ($this->info !== null) {
            return  $this->info->getFullName($official);
        } else {
            return $this->getUsername();
        }
    }


    public function setRoles(array $roles)
    {
        $this->roles = array();
        foreach ($roles as $role) {
            $this->addRole($role);
        }

        return $this;
    }
    public function getRoles()
    {
        if(is_array($this->roles)) {
            $this->roles  = in_array('ROLE_SUPER_ADMIN', $this->roles) && !in_array('ROLE_ADMIN', $this->roles)?array_merge($this->roles, ['ROLE_ADMIN']):$this->roles;  
        }else{
            $this->roles  = ['ROLE_CLIENT'];
        }
        return $this->roles;
    }
}
