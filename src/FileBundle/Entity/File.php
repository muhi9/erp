<?php

namespace FileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use BaseBundle\Entity\NomType;
use BaseBundle\Entity\BaseNomsExtra;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use UsersBundle\Entity\User;

use Symfony\Component\Validator\Context\ExecutionContextInterface;


/**
 * File
 *
 * @ORM\Table(name="file")
 * @ORM\Entity(repositoryClass="FileBundle\Repository\FileRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")

 * NO SOFT DEL ON PURPOSE!!!!
 * We set deletedAt + deletedBy in delete method in controller.
 * This is done on purpose, because this Entity is used mostly with collections...
 * because of the stupidity of implementation, each collection is earsed/inserted each time it's saved
 * becuase of this we get duplicate entries with just deletedAt updated... too much duplication for nothing!
 */
class File
{
    use \BaseBundle\Traits\BlameStampableEntity;
    use \BaseBundle\Traits\SoftDeleteableEntity;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Path can't be empty.")
     */
    private $path;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Disk name can't be empty.")
     */
    private $diskName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Name can't be empty.")
     {"FileBundle\Entity\File", "validate"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $customName;


    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Entity Class can't be empty.")
     */
    private $entityClass;

    /**
     * @var string
     *
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Entity Parent class id can't be empty.")
     */
    private $entityId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=30,nullable=true)
     */
    private $dummyField = 'dummy data';


    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Disk name can't be empty.")
     */
    private $mimeType;

    /**
     * @var BaseNoms
     *
     * @ORM\ManyToOne(targetEntity="BaseBundle\Entity\BaseNoms")
     */
    private $bnomType1;

    /**
     * @var BaseNoms
     *
     * @ORM\ManyToOne(targetEntity="BaseBundle\Entity\BaseNoms")
     */
    private $bnomType2;

    /**
     * @var BaseNoms
     *
     * @ORM\ManyToOne(targetEntity="BaseBundle\Entity\BaseNoms")
     */
    private $bnomType3;

    /**
     * @var BaseNoms
     *
     * @ORM\ManyToOne(targetEntity="BaseBundle\Entity\BaseNoms")
     */
    private $bnomType4;

    /**
     * @ORM\OneToMany(targetEntity="FileBundle\Entity\File", mappedBy="parent")
     **/
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="FileBundle\Entity\File", inversedBy="children")
     * @ORM\JoinColumn(name="parent", referencedColumnName="id"))
     */
    private $parent;


    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="UsersBundle\Entity\User")
     * @ORM\JoinColumn(name="deleted_by", referencedColumnName="id")
     */
    protected $deletedBy;

    /**
     * @var \DateTime
     * @
     Assert\NotBlank(message="form.notempty")
     * @Assert\DateTime(message="form.notempty")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $issuedOn;

    /**
     * @var \DateTime
     * @
     Assert\NotBlank(message="form.notempty")
     * @Assert\DateTime(message="form.notempty")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $validFrom;

    /**
     * @var \DateTime
     * @
     Assert\NotBlank(message="form.notempty")
     * @Assert\DateTime(message="form.notempty")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $validTo;


    private $projectDir;

    private $pathClass = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set path.
     *
     * @param string $path
     *
     * @return File
     */
    public function setPath($path): File
    {
        if (null == $this->pathClass)
            throw new \Exception("First set the class so we can build proper path.");

        $pPath = $this->getProjectDir();
        // make dir in db relative. trim the projectDir
        // WARNING! When you make new File(); you MUST set project dir manually - postLoad listener won't fire to set it. Fires only when loaded from db
        if (!empty($pPath))
            $path = str_replace($pPath, '', $path);
        // add path class if missing
        if (null !== $this->entityClass)
            $this->pathClass = $this->genPathClass($this->entityClass);
        if (!strstr($path,$this->pathClass))
            $path .= '/' . $this->pathClass;
        // add YYYY-MM at the end of path + create dir if not there.
        if (!preg_match('/\d{4}-\d{2}$/',$path)) {
            $path .= '/' . date('Y-m');
            if(!file_exists($pPath . $path)) {
                if (false === mkdir(($pPath . $path), 0755, $recursive = true)) {
                    throw new \Exception('Unable to create ' . $pPath . $path);
                }
            }
        }

        $this->path = $path;
        return $this;
    }

    /**
     * Get path.
     *
     * @return string
     */
    public function getPath()
    {
        $pPath = $this->getProjectDir();
        return $pPath . $this->path;
    }


    public function getProjectDir() {
        return $this->projectDir;
    }

    public function setProjectDir($dir) {
        $this->projectDir = $dir;
        return $this;
    }

    /**
     * Set diskName.
     *
     * @param string $diskName
     *
     * @return File
     */
    public function setDiskName($diskName): File
    {
        $this->diskName = $diskName;
        return $this;
    }

    /**
     * Get diskName.
     *
     * @return string
     */
    public function getDiskName()
    {
        return $this->diskName;
    }

    /**
     * Set customName.
     *
     * @param string $customName
     *
     * @return File
     */
    public function setCustomName($customName = null): File
    {
        $this->customName = $customName;

        return $this;
    }

    /**
     * Get customName.
     *
     * @return string
     */
    public function getCustomName(): ?String
    {
        return $this->customName;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return File
     */
    public function setName($name): File
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set entityClass.
     *
     * @param string $entityClass
     *
     * @return File
     */
    public function setEntityClass($entityClass): File
    {
        $this->entityClass = $entityClass;
        $this->pathClass = DIRECTORY_SEPARATOR . mb_strtolower(str_replace('\\', '_', $entityClass));

        return $this;
    }

    /**
     * Get entityClass.
     *
     * @return string
     */
    public function getEntityClass()
    {
        if (null !== $this->entityClass)
            $this->pathClass = $this->genPathClass($this->entityClass);
        return $this->entityClass;
    }

    /**
     * Set mimeType.
     *
     * @param string $mimeType
     *
     * @return File
     */
    public function setMimeType($mimeType): File
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get mimeType.
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set bnomType1.
     *
     * @param \BaseBundle\Entity\BaseNoms|null $bnomType1
     *
     * @return File
     */
    public function setBnomType1(?\BaseBundle\Entity\BaseNoms $bnomType1): File
    {
        $this->bnomType1 = $bnomType1;

        return $this;
    }

    /**
     * Get bnomType1.
     *
     * @return \BaseBundle\Entity\BaseNoms|null
     */
    public function getBnomType1(): ?\BaseBundle\Entity\BaseNoms
    {
        return $this->bnomType1;
    }

    /**
     * Set bnomType2.
     *
     * @param \BaseBundle\Entity\BaseNoms|null $bnomType2
     *
     * @return File
     */
    public function setBnomType2(?\BaseBundle\Entity\BaseNoms $bnomType2): File
    {
        $this->bnomType2 = $bnomType2;

        return $this;
    }

    /**
     * Get bnomType2.
     *
     * @return \BaseBundle\Entity\BaseNoms|null
     */
    public function getBnomType2(): ?\BaseBundle\Entity\BaseNoms
    {
        return $this->bnomType2;
    }

    /**
     * Set bnomType3.
     *
     * @param \BaseBundle\Entity\BaseNoms|null $bnomType3
     *
     * @return File
     */
    public function setBnomType3(\BaseBundle\Entity\BaseNoms $bnomType3): File
    {
        $this->bnomType3 = $bnomType3;

        return $this;
    }

    /**
     * Get bnomType3.
     *
     * @return \BaseBundle\Entity\BaseNoms|null
     */
    public function getBnomType3(): ?\BaseBundle\Entity\BaseNoms
    {
        return $this->bnomType3;
    }

    /**
     * Set bnomType4.
     *
     * @param \BaseBundle\Entity\BaseNoms|null $bnomType4
     *
     * @return File
     */
    public function setBnomType4(?\BaseBundle\Entity\BaseNoms $bnomType4): File
    {
        $this->bnomType4 = $bnomType4;

        return $this;
    }

    /**
     * Get bnomType4.
     *
     * @return \BaseBundle\Entity\BaseNoms|null
     */
    public function getBnomType4(): ?\BaseBundle\Entity\BaseNoms
    {
        return $this->bnomType4;
    }


    public function setDeletedBy(User $by)
    {
        $this->deletedBy = $by;

        return $this;
    }

    /**
     * Returns createdBy.
     *
     * @return User
     */
    public function getDeletedBy()
    {
        return $this->deletedBy;
    }



    /**
     * Add child.
     *
     * @param \FileBundle\Entity\File $child
     *
     * @return File
     */
    public function addChild(\FileBundle\Entity\File $child): File
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child.
     *
     * @param \FileBundle\Entity\File $child
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeChild(\FileBundle\Entity\File $child)
    {
        return $this->children->removeElement($child);
    }

    /**
     * Get children.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent.
     *
     * @param \FileBundle\Entity\File|null $parent
     *
     * @return File
     */
    public function setParent(\FileBundle\Entity\File $parent = null): File
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent.
     *
     * @return \FileBundle\Entity\File|null
     */
    public function getParent(): File
    {
        return $this->parent;
    }

    private function genPathClass($className) {
         return DIRECTORY_SEPARATOR . mb_strtolower(str_replace('\\', '_', substr($className, 0, strpos($className, '::'))));
    }

    /**
     * Set entityId.
     *
     * @param \number $entityId
     *
     * @return File
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;

        return $this;
    }

    /**
     * Get entityId.
     *
     * @return \number
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * Set dummyField.
     *
     * @param string|null $dummyField
     *
     * @return File
     */
    public function setDummyField($dummyField = null)
    {
        $this->dummyField = $dummyField;

        return $this;
    }

    /**
     * Get dummyField.
     *
     * @return string|null
     */
    public function getDummyField()
    {
        return $this->dummyField;
    }

    /**
     * Set issuedOn.
     *
     * @param \DateTime|null $issuedOn
     *
     * @return File
     */
    public function setIssuedOn($issuedOn = null)
    {
        $this->issuedOn = $issuedOn;

        return $this;
    }

    /**
     * Get issuedOn.
     *
     * @return \DateTime|null
     */
    public function getIssuedOn()
    {
        return $this->issuedOn;
    }

    /**
     * Set validFrom.
     *
     * @param \DateTime|null $validFrom
     *
     * @return File
     */
    public function setValidFrom($validFrom = null)
    {
        $this->validFrom = $validFrom;

        return $this;
    }

    /**
     * Get validFrom.
     *
     * @return \DateTime|null
     */
    public function getValidFrom()
    {
        return $this->validFrom;
    }

    /**
     * Set validTo.
     *
     * @param \DateTime|null $validTo
     *
     * @return File
     */
    public function setValidTo($validTo = null)
    {
        $this->validTo = $validTo;

        return $this;
    }

    /**
     * Get validTo.
     *
     * @return \DateTime|null
     */
    public function getValidTo()
    {
        return $this->validTo;
    }

}
