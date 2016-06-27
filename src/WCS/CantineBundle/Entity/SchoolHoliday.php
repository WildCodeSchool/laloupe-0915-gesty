<?php

namespace WCS\CantineBundle\Entity;

/**
 * SchoolHolidays
 */
class SchoolHoliday
{
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
    private $description;

    /**
     * @var \WCS\CantineBundle\Entity\SchoolYear
     */
    private $schoolYear;


    public function __toString()
    {
        return " Du "
            . $this->dateStart->format('d/m/Y')
            . " au "
            . $this->dateEnd->format('d/m/Y')
            . " : "
            . $this->description
            ;
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
     * @return SchoolHoliday
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
     * @return SchoolHoliday
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
     * Set description
     *
     * @param string $description
     *
     * @return SchoolHoliday
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param SchoolYear $schoolYear
     * @return SchoolHoliday
     */
    public function setSchoolYear($schoolYear)
    {
        $this->schoolYear = $schoolYear;
        return $this;
    }

    /**
     * @return SchoolYear
     */
    public function getSchoolYear()
    {
        return $this->schoolYear;
    }

}
