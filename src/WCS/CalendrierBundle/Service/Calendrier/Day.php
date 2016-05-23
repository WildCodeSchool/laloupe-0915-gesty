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

use WCS\CalendrierBundle\Service\PeriodesAnneeScolaire\PeriodesAnneeScolaire;


class Day
{
    /**
     * Day constructor.
     * @param \DateTimeImmutable $day
     * @param PeriodesAnneeScolaire $periodesScolaire
     */
    public function __construct(\DateTimeImmutable $day, PeriodesAnneeScolaire $periodesScolaire)
    {
        $this->year         = $day->format('Y');
        $this->month        = $day->format('m');
        $this->day          = $day->format('d');
        $this->dayOfWeek    = str_replace('0', '7', $day->format('w'));

        // s'il s'agit d'un jour parmi les suivants : mer, sam ou dim, alors il s'agit d'un jour sans classe
        if ($this->dayOfWeek==3 || $this->dayOfWeek==6 || $this->dayOfWeek==7) {
            $this->isOff = true;
        }
        else {
            $this->isOff = $periodesScolaire->findEnClasseFrom($day)==null;
        }

        $this->isPast = ($day->format('Y-m-d') < $periodesScolaire->getDateToday());
    }

    /**
     * @return string
     */
    public function __toString()
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
        return $this->isOff;
    }

    /**
     * @param boolean $isOff
     */
    public function setIsOff($isOff)
    {
        $this->isOff = $isOff;
    }

    /**
     * @return boolean
     */
    public function isPast()
    {
        return $this->isPast;
    }

    /**
     * @param boolean $isPast
     */
    public function setIsPast($isPast)
    {
        $this->isPast = $isPast;
    }

    private $year;
    private $month;
    private $day;
    private $dayOfWeek;
    private $isOff;
    private $isPast;
};