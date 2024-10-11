<?php

namespace BaseBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Gedmo\Mapping\Annotation as Gedmo;
use BaseBundle\Traits\BlameStampableEntity;
use BaseBundle\Traits\SoftDeleteableEntity;

/**
 * @ORM\Table(name="settings")
 * @ORM\Entity(repositoryClass="BaseBundle\Repository\SettingsRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class Settings
{

    use BlameStampableEntity;
    use SoftDeleteableEntity;
    
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Name cant be empty,")
     */
    private $name;
    /**
     * @ORM\Column(type="boolean")
     */
    private $status =1;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $settings;

    /**
     * @ORM\Column(name="is_deleted", type="boolean", nullable=true)
     */
    private $isdeleted = 0;

    public function __construct()
    {
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }
    

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function getSettings()
    {
        return json_decode($this->settings,true);
    }

    public function setSettings($settings)
    {
        $this->settings = json_encode($settings);

        return $this;
    }

 

    public function setIsdeleted($isdeleted) #: self
    {
        $this->isdeleted = $isdeleted;
        return $this;
    }
   
}
