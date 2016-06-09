<?php
namespace WCS\CalendrierBundle\Service;


class DateNow
{
    /**
     * Instanciate the object either with an empty date, ie, the DateNow will be the
     * current date defined in the system either with an arbitral date
     *
     * DateNow constructor.
     * @param string $dateStr date formatted with 'Y-m-d' or ''
     * @throws DateNowException if the date is not valid
     */
    public function __construct($dateStr='')
    {
        try {
            $this->dateNow = new \DateTimeImmutable($dateStr);
        }
        catch(\Exception $e) {
            throw new DateNowException($dateStr);
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
