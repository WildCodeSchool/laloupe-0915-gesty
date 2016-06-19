<?php
namespace SchoolCalendar\Component\Scheduler;


class DateNow
{
    /**
     * Instanciate the object either with an empty date, ie, the DateNow will be the
     * current date defined in the system either with an arbitral date
     *
     * DateNow constructor.
     * @param string $dateStr date formatted with 'Y-m-d' or ''
     * @throws \InvalidArgumentException if the date is not valid
     */
    public function __construct($dateStr='')
    {
        try {
            $this->dateNow = new \DateTimeImmutable($dateStr);
        }
        catch(\Exception $e) {
            throw new \InvalidArgumentException(__CLASS__ ." is called with an invalid date (".$dateStr."). 
            Check the argument passed in the instanciation or in the service.xml file");
        }
    }

    /**
     * @return \DateTimeImmutable date courante
     */
    public function getDate()
    {
        return $this->dateNow;
    }

    /**
     * @param string $format format de la date à récupérer. Formats identiques à date() de php (voir doc php)
     * @return string date courante au format donné.
     */
    public function getDateStr($format='Y-m-d')
    {
        return $this->dateNow->format($format);
    }

    /**
     * @var \DateTimeImmutable
     */
    private $dateNow;
}
