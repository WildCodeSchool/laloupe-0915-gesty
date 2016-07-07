<?php

namespace WCS\CantineBundle\Service\GestyScheduler;


use Doctrine\Common\Collections\Collection;

use Scheduler\Component\DateContainer\Day;
use Scheduler\Component\DateContainer\Period;


class DaysOfWeeks
{
    private $list_jours_tap       = array();
    private $list_jours_garderie  = array();
    private $periode = null;

    /**
     * @return Period
     */
    public function getPeriode()
    {
        return $this->periode;
    }


    /**
     * @return array
     */
    public function getListJoursGarderie()
    {
        return $this->list_jours_garderie;
    }

    /**
     * @return array
     */
    public function getListJoursTap()
    {
        return $this->list_jours_tap;
    }


    /**
     * DaysOfWeeks constructor.
     * @param Period $periode
     * @param GestyScheduler $scheduler
     */
    public function __construct(
        Period $periode,
        GestyScheduler $scheduler
        )
    {
        $this->periode  = $periode;

        foreach ($this->periode->getDayIterator() as $currentDay) {

            $d = new Day($currentDay);

            if (!$scheduler->isDayOff($currentDay, ActivityType::TAP)) {
                $this->list_jours_tap[$d->getWeekDay()][] = $currentDay->format('Y-m-d');
            }
            if (!$scheduler->isDayOff($currentDay, ActivityType::GARDERIE_MORNING)) {
                $this->list_jours_garderie[$d->getWeekDay() . '-1'][] = $currentDay->format('Y-m-d');
            }
            if (!$scheduler->isDayOff($currentDay, ActivityType::GARDERIE_EVENING)) {
                $this->list_jours_garderie[$d->getWeekDay() . '-2'][] = $currentDay->format('Y-m-d');
            }
        }
    }

    /**
     * @param Collection $taps
     * @return array
     */
    public function getTapSelectionToArray(Collection $taps)
    {
        $tmp = array();

        foreach($taps as $tap) {
            if ($tap->getSubscribedByParent()) {
                foreach ($this->list_jours_tap as $dayOfWeek => $dates) {
                    $date = $tap->getDate()->format('Y-m-d');
                    if (in_array($date, $dates)) {
                        $tmp[$dayOfWeek] = 1;
                    }
                }
            }
        }

        $tmp2 = array();
        foreach ($tmp as $key => $value) {
            $tmp2[] = $key;
        }

        return $tmp2;
    }

    /**
     * @param Collection $garderies
     * @return array
     */
    public function getGarderieSelectionToArray(Collection $garderies)
    {
        $tmp = array();

        foreach($garderies as $garderie) {
            if ($garderie->getSubscribedByParent()) {
                foreach ($this->list_jours_garderie as $dayOfWeek => $dates) {

                    $date  = $garderie->getDate()->format('Y-m-d');
                    if (substr($dayOfWeek, -2)=='-1' && $garderie->isEnableMorning()) {
                        if (in_array($date, $dates)) {
                            $tmp[$dayOfWeek] = 1;
                        }
                    }
                    if (substr($dayOfWeek, -2)=='-2' && $garderie->isEnableEvening()) {
                        if (in_array($date, $dates)) {
                            $tmp[$dayOfWeek] = 1;
                        }
                    }

                }
            }
        }

        $tmp2 = array();
        foreach ($tmp as $key => $value) {
            $tmp2[] = $key;
        }
        return $tmp2;
    }
}
