<?php

namespace WCS\CantineBundle\Entity;

/**
 * SchoolYear
 */
class SchoolYear
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $begin_date;

    /**
     * @var \DateTime
     */
    private $end_date;

    /**
     * @var string
     */
    private $url_icalendar_holidays;

    /**
     * @var string
     */
    private $filename_icalendar_holidays;


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
     * Set beginDate
     *
     * @param \DateTime $beginDate
     *
     * @return SchoolYear
     */
    public function setBeginDate($beginDate)
    {
        $this->begin_date = $beginDate;

        return $this;
    }

    /**
     * Get beginDate
     *
     * @return \DateTime
     */
    public function getBeginDate()
    {
        return $this->begin_date;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return SchoolYear
     */
    public function setEndDate($endDate)
    {
        $this->end_date = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->end_date;
    }

    /**
     * Set urlIcalendarHolidays
     *
     * @param string $urlIcalendarHolidays
     *
     * @return SchoolYear
     */
    public function setUrlIcalendarHolidays($urlIcalendarHolidays)
    {
        $this->url_icalendar_holidays = $urlIcalendarHolidays;

        return $this;
    }

    /**
     * Get urlIcalendarHolidays
     *
     * @return string
     */
    public function getUrlIcalendarHolidays()
    {
        return $this->url_icalendar_holidays;
    }

    /**
     * Set filenameIcalendarHolidays
     *
     * @param string $filenameIcalendarHolidays
     *
     * @return SchoolYear
     */
    public function setFilenameIcalendarHolidays($filenameIcalendarHolidays)
    {
        $this->filename_icalendar_holidays = $filenameIcalendarHolidays;

        return $this;
    }

    /**
     * Get filenameIcalendarHolidays
     *
     * @return string
     */
    public function getFilenameIcalendarHolidays()
    {
        return $this->filename_icalendar_holidays;
    }
}

