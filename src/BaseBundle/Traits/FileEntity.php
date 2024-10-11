<?php

namespace BaseBundle\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


trait FileEntity
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fileName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $diskFileName;

    /**
     * @ORM\ManyToOne(targetEntity="BaseBundle\Entity\BaseNoms")
     * @ORM\JoinColumn(nullable=true)
     */
    private $fileType;

    /**
     * Set file name.
     *
     * @param string $fileName
     *
     * @return AircraftSeats
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get file name.
     *
     * @return string
     */
    public function getFileName($stripped = false)
    {
        return $stripped ? substr($this->fileName, 17) : $this->fileName;
    }

    /**
     * Set disk file name.
     *
     * @param string $diskFileName
     *
     * @return FileEntity
     */
    public function setDiskFileName($diskFileName)
    {
        $this->diskFileName = $diskFileName;

        return $this;
    }

    /**
     * Get disk file name.
     *
     * @return string
     */
    public function getDiskFileName()
    {
        return $this->diskFileName;
    }


    /**
     * Set file type.
     *
     * @param \BaseBundle\Entity\BaseNoms|null $fileType
     *
     * @return Aircraft
     */
    public function setFileType(\BaseBundle\Entity\BaseNoms $fileType = null)
    {
        $this->fileType = $fileType;

        return $this;
    }

    /**
     * Get file type.
     *
     * @return \BaseBundle\Entity\BaseNoms
     */
    public function getFileType()
    {
        return $this->fileType;
    }

}

