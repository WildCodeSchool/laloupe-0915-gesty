<?php

namespace Scheduler\Component\Scheduler;

use Scheduler\Component\DateContainer\Day;
use Scheduler\Component\DateContainer\Period;
use Scheduler\Component\DateContainer\PeriodInterface;
use Symfony\Component\VarDumper\VarDumper;


class Scheduler
{
    /**
     * @var \Scheduler\Component\DateContainer\Period[]
     */
    private $yearPeriods = [];

    /**
     * @var \Scheduler\Component\DateContainer\Period[]
     */
    private $periodsDayOff = [];

    /**
     * @var array[]
     */
    private $weekUsualDaysOff = [];

    /**
     * @var \DateTime[]
     */
    private $datesDayOff = [];


    /*===============================================================
        Year period
     ===============================================================*/

    /**
     * @param PeriodInterface $period
     */
    public function addYearPeriod(PeriodInterface $period)
    {
        $lastPeriod = null;
        $lastIndex = count($this->yearPeriods);
        if ($lastIndex) {
            $lastPeriod = $this->yearPeriods[$lastIndex-1];

        }
        $this->yearPeriods[] = $period;
        
        if ($lastPeriod) {
            $this->addPeriodDayOffs(new Period($lastPeriod->getLastDate(), $period->getFirstDate()->sub(new \DateInterval('P1D')
            )));
        }
    }


    /**
     * @return \Scheduler\Component\DateContainer\Period[]
     */
    public function getYearPeriods()
    {
        return $this->yearPeriods;
    }

    /**
     * @return \Scheduler\Component\DateContainer\Period
     */
    public function getFirstYearPeriod()
    {
        if (empty($this->yearPeriods)) {
            throw new \LogicException('no reference period (Years,...) defined');
        }
        return $this->yearPeriods[0];
    }

    /**
     * @return \Scheduler\Component\DateContainer\Period
     */
    public function getLastYearPeriod()
    {
        if (empty($this->yearPeriods)) {
            throw new \LogicException('no reference period (Years,...) defined');
        }
        return $this->yearPeriods[count($this->yearPeriods)-1];
    }


    /*===============================================================
        Period days off
     ===============================================================*/

    /**
     * @param PeriodInterface $period
     */
    public function addPeriodDayOffs(PeriodInterface $period)
    {
        $this->periodsDayOff[] = $period;

        \usort($this->periodsDayOff, function(PeriodInterface $periodA, PeriodInterface $periodB) {
           return $periodA->getFirstDate() > $periodB->getFirstDate() ? +1 : -1;
        });
    }



    /*===============================================================
        Dates day off
     ===============================================================*/
    /**
     * @param \DateTimeInterface[] $dates
     */
    public function addDatesDayOff(
        array $dates
    )
    {
        $this->datesDayOff = \array_merge($this->datesDayOff, $dates);
    }

    /**
     * @return \DateTime[]
     */
    public function getDatesDaysOff()
    {
        return $this->datesDayOff;
    }


    /**
     * @param \DateTimeInterface $date
     * @param array $options with index to which the weekstat will be assigned.
     * @return bool
     */
    public function isDayOff(
        \DateTimeInterface $date,
        array $options
    )
    {
        if (\in_array($date, $this->datesDayOff)) {
            return true;
        }

        foreach ($this->periodsDayOff as $periodDayOff) {
            if ($periodDayOff->isIncluded($date)) {
                return true;
            }
        }
        if (isset($options['index'])) {
            $index = $options['index'];
            $d = new Day($date);
            if (\in_array($d->getWeekDay(), $this->weekUsualDaysOff[$index])) {
                return true;
            }
        }

        return false;
    }


    /*===============================================================
        Week Usual days off
     ===============================================================*/

    /**
     * @param array[] $dayConstants Day::const * array
     * @param array $options with index to which the weekstat will be assigned.
     */
    public function addWeekUsualDaysOff(
        array $dayConstants,
        array $options
    )
    {
        $this->weekUsualDaysOff[$options['index']] = $dayConstants;
    }


    /**
     * @param array $options with index to which the weekstat will be assigned.
     * @return \array[]
     */
    public function getWeekUsualDaysOff(array $options)
    {
        return $this->weekUsualDaysOff[$options['index']];
    }



    /*===============================================================
        Available periods
     ===============================================================*/

    /**
     * Return all available periods between days off
     *
     * @return \Scheduler\Component\DateContainer\Period[]
     */
    public function getAvailablePeriods()
    {
        $periods            = [];
        $oneDay             = new \DateInterval('P1D');
        $lastDayOffDate     = $this->getFirstYearPeriod()->getFirstDate();

        foreach ($this->periodsDayOff as $periodDayOff) {

            $periods[] = new Period(
                $lastDayOffDate,
                $periodDayOff->getFirstDate()
            );

            $lastDayOffPeriod = $periodDayOff;
            $lastDayOffDate = $lastDayOffPeriod->getLastDate()->add($oneDay);
        }

        $periods[] = new Period(
            $lastDayOffDate,
            $this->getLastYearPeriod()->getLastDate()
        );

        return $periods;
    }


    /**
     * @param \DateTimeInterface $date
     * @return \Scheduler\Component\DateContainer\Period
     */
    public function getFirstAvailablePeriod(
        array $periods,
        \DateTimeInterface $date
    )
    {
        foreach($periods as $period) {
            if ($period->isIncluded($date)) {
                return $period;
            }
        }
        foreach($periods as $period) {
            if ($period->getFirstDate() > $date) {
                return $period;
            }
        }
        return null;
    }

    /**
     * @param \DateTimeInterface $date
     * @return \DateTime the first date available
     */
    public function getFirstAvailableDate(
        \DateTimeInterface $date
    )
    {
        $oneDay = new \DateInterval('P1D');
        $firstDate = new \DateTime($date->format('Y-m-d'));

        // get the first year available period

        $period = $this->getFirstAvailablePeriod($this->yearPeriods, $firstDate);
        if ($period && $firstDate < $period->getFirstDate()) {
            $firstDate = $period->getFirstDate();
        }

        // get the first period after if the date is during a holiday

        $period = $this->getFirstAvailablePeriod($this->getAvailablePeriods(), $firstDate);
        if ($period != null && $firstDate < $period->getFirstDate()) {
            $firstDate = $period->getFirstDate();
        }

        // get the first date after if the date is a day off
        foreach ($this->datesDayOff as $dayOff) {
            if ($firstDate == $dayOff) {
                $firstDate = $firstDate->add($oneDay);
            }
        }

        return $firstDate;
    }

}
