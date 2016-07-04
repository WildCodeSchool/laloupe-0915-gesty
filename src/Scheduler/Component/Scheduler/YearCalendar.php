<?php
namespace Scheduler\Component\Scheduler;

use Scheduler\Component\DateContainer\Day;
use Scheduler\Component\DateContainer\Period;
use Scheduler\Component\DateContainer\PeriodInterface;


class YearCalendar
{
    /*==========================================================================================================
        Méthodes
    ==========================================================================================================*/

    /**
     * @param string|integer $month numéro du mois
     * @return \Scheduler\Component\DateContainer\Day[] tableau indexé de jours
     */
    public function getDays($month)
    {
        return $this->days[$month];
    }

    /**
     * @return Period
     */
    public function getYearPeriod()
    {
        return $this->yearPeriod;
    }


    /*==========================================================================================================
        Constructeur
        methodes privées
        et attributs
    ==========================================================================================================*/

    /**
     * Calendrier constructor.
     *
     * Génère chaque jours du calendrier pour une période scolaire donnée
     *
     * @param PeriodInterface $period
     * @param function - $closureIsDayOff
     */
    public function __construct(
        PeriodInterface $yearPeriod,
        $closureIsDayOff
    )
    {
        $this->yearPeriod = $yearPeriod;
        $this->days = array();

        $period = new Period(
            $this->yearPeriod->getFirstDate(),
            new \DateTimeImmutable( $this->yearPeriod->getLastDate()->format('Y-m').'-31')
        );

        foreach($period->getDayIterator() as $currentDay) {
            $d = new Day($currentDay);

            $d->setOff($closureIsDayOff($d));

            if (!$yearPeriod->isIncluded($d->getDate())) {
                $d->setOff(true);
            }

            $this->days[ $d->getMonth() ][ $d->getDay() ] = $d;
        }

    }

    /**
     * @var \Scheduler\Component\DateContainer\Day[][]
     */
    private $days;

    /**
     * @var Period of reference
     */
    private $yearPeriod;

}
