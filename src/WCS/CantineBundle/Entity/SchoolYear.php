<?php

namespace WCS\CantineBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * SchoolYear
 */
class SchoolYear
{
    /**
     * @var string
     */
    private $absolutePath;

    /**
     * @var \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    private $file;

    /**
     * @param string $absolutePath
     */
    public function setUploadAbsolutePath($absolutePath)
    {
        $this->absolutePath = $absolutePath;
    }

    /**
     * @return string
     */
    public function getAbsolutePath()
    {
        return $this->absolutePath;
    }

    /**
     * Sets file.
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Manages the copying of the file to the relevant place on the server
     */
    public function upload()
    {
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }

        // we use the original file name here but you should
        // sanitize it at least to avoid any security issues

        // set the path property to the filename where you've saved the file
        $this->filenameIcalendar = uniqid().'.'.$this->getFile()->guessExtension();

        // move takes the target directory and target filename as params
        $this->getFile()->move(
            $this->absolutePath,
            $this->filenameIcalendar
        );
        
        // clean up the file property as you won't need it anymore
        $this->setFile(null);
    }

    /**
     * Lifecycle callback to upload the file to the server
     */
    public function lifecycleFileUpload()
    {
        $this->upload();
    }

    /**
     * Updates the hash value to force the preUpdate and postUpdate events to fire
     */
    public function refreshUpdated()
    {
        $this->setUpdated(new \DateTime());
    }

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $dateStart;

    /**
     * @var \DateTime
     */
    private $dateEnd;
    
    /**
     * @var string
     */
    private $filenameIcalendar = '';

    /**
     * @var \DateTime
     */
    private $updated;

    /**
     * @var ArrayCollection
     */
    private $schoolHolidays;

    /**
     * SchoolYear constructor.
     */
    public function __construct()
    {
        $this->urlIcalendar = '';
        $this->filenameIcalendar = '';
        $this->schoolHolidays = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dateStart
     *
     * @param \DateTimeInterface  $dateStart
     *
     * @return SchoolYear
     */
    public function setDateStart(\DateTimeInterface $dateStart)
    {
        $this->dateStart = new \DateTime($dateStart->format('Y-m-d'));

        return $this;
    }

    /**
     * Get dateStart
     *
     * @return \DateTime
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * Set dateEnd
     *
     * @param \DateTimeInterface  $dateEnd
     *
     * @return SchoolYear
     */
    public function setDateEnd(\DateTimeInterface $dateEnd)
    {
        $this->dateEnd = new \DateTime($dateEnd->format('Y-m-d'));

        return $this;
    }

    /**
     * Get dateEnd
     *
     * @return \DateTime
     */
    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    /**
     * Set filenameIcalendar
     *
     * @param string $filenameIcalendar
     *
     * @return SchoolYear
     */
    public function setFilenameIcalendar($filenameIcalendar)
    {
        $this->filenameIcalendar = $filenameIcalendar;

        return $this;
    }

    /**
     * Get filenameIcalendar
     *
     * @return string
     */
    public function getFilenameIcalendar()
    {
        return $this->filenameIcalendar;
    }

    /**
     * @return string the absolute and complete path of the icalendar file
     */
    public function getIcalendarPath()
    {
        return $this->absolutePath.'/'.$this->filenameIcalendar;
    }

    /**
     * @param \DateTime $updated
     * @return $this
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Add Holiday
     *
     * @param \WCS\CantineBundle\Entity\SchoolHoliday $holidays
     *
     * @return SchoolYear
     */
    public function addSchoolHoliday(SchoolHoliday $holiday)
    {
        $holiday->setSchoolYear($this);
        $this->schoolHolidays[] = $holiday;

        return $this;
    }

    /**
     * Remove Holiday
     *
     * @param \WCS\CantineBundle\Entity\SchoolHoliday $holidays
     */
    public function removeSchoolHoliday(SchoolHoliday $holiday)
    {
        $this->schoolHolidays->removeElement($holiday);
    }


    /**
     * Remove All Holiday
     *
     * @param \WCS\CantineBundle\Entity\SchoolHoliday $holidays
     */
    public function removeAllSchoolHolidays()
    {
        unset($this->schoolHolidays);
        $this->schoolHolidays = new ArrayCollection();
    }

    /**
     * Get Holidays
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSchoolHolidays()
    {
        return $this->schoolHolidays;
    }

}

