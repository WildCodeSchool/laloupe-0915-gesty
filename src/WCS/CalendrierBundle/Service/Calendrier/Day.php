<?php
/*===============================================================================================================

    Cette classe stocke toutes les informations utile pour UNE journée du calendrie scolaire
    - le numéro de jour dans le mois
    - le numéro du mois
    - l'année
    - le numéro du jour dans la semaine
    - est-ce que ce jour est un jour fermé (vacance, férié,...)
    - est-ce que ce jour est passé par rapport à une date de référence (ex: si on est en mai, et
       que le jour est en avril, il est considéré comme "passé"

===============================================================================================================*/

namespace WCS\CalendrierBundle\Service\Calendrier;

class Day
{
    const WEEK_MONDAY       = '1';
    const WEEK_TUESDAY      = '2';
    const WEEK_WEDNESDAY    = '3';
    const WEEK_THURSDAY     = '4';
    const WEEK_FRIDAY       = '5';
    const WEEK_SATURDAY     = '6';
    const WEEK_SUNDAY       = '7';

    /**
     * Day constructor.
     * @param \DateTimeImmutable $day
     */
    public function __construct(\DateTimeImmutable $day)
    {
        $this->year         = $day->format('Y');
        $this->month        = $day->format('m');
        $this->day          = $day->format('d');
        $this->dayOfWeek    = str_replace('0', self::WEEK_SUNDAY, $day->format('w'));
        $this->off        = false;
        $this->past       = false;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getDateString();
    }

    /**
     * @return string
     */
    public function getDateString()
    {
        return $this->year .'-'. $this->month .'-'. $this->day;
    }

    /**
     * @return string
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @return mixed
     */
    public function getDayOfWeek()
    {
        return $this->dayOfWeek;
    }

    /**
     * @return string
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @return string
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @return boolean
     */
    public function isOff()
    {
        return $this->off;
    }

    /**
     * @param boolean $isOff
     */
    public function setOff($isOff)
    {
        $this->off = $isOff;
    }

    /**
     * @return boolean
     */
    public function isPast()
    {
        return $this->past;
    }

    /**
     * @param boolean $isPast
     */
    public function setPast($isPast)
    {
        $this->past = $isPast;
    }

    /**
     * @param $dayOfWeek
     * @return bool
     */
    public function isDayOfWeek($dayOfWeek)
    {
        return $this->dayOfWeek == $dayOfWeek;
    }

    private $year;
    private $month;
    private $day;
    private $dayOfWeek;
    private $off;
    private $past;
};
