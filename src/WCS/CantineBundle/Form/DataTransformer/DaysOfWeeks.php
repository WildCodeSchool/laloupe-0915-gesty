<?php

namespace WCS\CantineBundle\Form\DataTransformer;


use Doctrine\Common\Collections\Collection;
use WCS\CantineBundle\Entity\ActivityType;
use WCS\CantineBundle\Entity\Eleve;
use WCS\CalendrierBundle\Service\Periode\Periode;
use WCS\CalendrierBundle\Service\Calendrier\Day;
use WCS\CantineBundle\Service\AdditionalDayOffList;


class DaysOfWeeks
{
    private $list_jours_tap       = array();
    private $list_jours_garderie  = array();
    private $periode = null;

    /**
     * @return \WCS\CalendrierBundle\Service\Periode\Periode
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
     * @param Periode $periode
     * @param AdditionalDayOffList $dayOffList
     * @param Eleve $eleve (optional)
     */
    public function __construct(Periode $periode, AdditionalDayOffList $dayOffList, Eleve $eleve=null)
    {
        $this->periode  = $periode;
        $dayOffArray    = $dayOffList->findDatesWithin($periode, array("eleve"=>$eleve));

        $currentDay     = $this->periode->getDebut();
        $end            = new \DateTimeImmutable($this->periode->getFin()->format('Y-m-d'));
        $oneDay         = new \DateInterval('P1D');

        while ($currentDay <= $end) {

            // enregistre les infos sur la journée dans le calendrier
            $d = new Day($currentDay);

            $index = array_search($currentDay, $dayOffArray);
            if ($index===FALSE) {
                if (!ActivityType::isDayOff(ActivityType::TAP, $currentDay)) {
                    $this->list_jours_tap[$d->getDayOfWeek()][] = $currentDay->format('Y-m-d');
                }
                if (!ActivityType::isDayOff(ActivityType::GARDERIE_MORNING, $currentDay)) {
                    $this->list_jours_garderie[$d->getDayOfWeek() . '-1'][] = $currentDay->format('Y-m-d');
                }
                if (!ActivityType::isDayOff(ActivityType::GARDERIE_EVENING, $currentDay)) {
                    $this->list_jours_garderie[$d->getDayOfWeek() . '-2'][] = $currentDay->format('Y-m-d');
                }
            }

            // passe au jour suivant
            $currentDay = $currentDay->add($oneDay);
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
            foreach ($this->list_jours_tap as $dayOfWeek => $dates) {
                $date  = $tap->getDate()->format('Y-m-d');
                if (in_array($date, $dates)) {
                    $tmp[$dayOfWeek] = 1;
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

        $tmp2 = array();
        foreach ($tmp as $key => $value) {
            $tmp2[] = $key;
        }
        return $tmp2;
    }
}
