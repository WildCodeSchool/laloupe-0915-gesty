<?php

namespace WCS\CantineBundle\Form\DataTransformer;


use WCS\CalendrierBundle\Service\Periode\Periode;
use WCS\CalendrierBundle\Service\Calendrier\Day;
use WCS\CantineBundle\Service\FeriesDayList;


class DaysOfWeeks
{
    const HEURE_MATIN   = ' 08:00:00';
    const HEURE_SOIR    = ' 17:00:00';

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


    public function __construct(Periode $periode, FeriesDayList $feriesDayList)
    {
        $this->periode  = $periode;
        $feriesArray    = $feriesDayList->findDatesWithin($periode);

        $currentDay     = $this->periode->getDebut();
        $end            = new \DateTimeImmutable($this->periode->getFin()->format('Y-m-d'));
        $oneDay         = new \DateInterval('P1D');

        while ($currentDay <= $end) {

            // enregistre les infos sur la journée dans le calendrier
            $d = new Day($currentDay);

            $index = array_search($currentDay, $feriesArray);
            if ($index===FALSE) {
                // les taps
                if ($d->isDayOfWeek(Day::WEEK_TUESDAY) || $d->isDayOfWeek(Day::WEEK_THURSDAY)) {
                    $this->list_jours_tap[$d->getDayOfWeek()][] = $currentDay->format('Y-m-d');
                }

                // les garderies
                $this->list_jours_garderie[$d->getDayOfWeek() . '-1'][] = $currentDay->format('Y-m-d') . self::HEURE_MATIN;

                if (false === $d->isDayOfWeek(Day::WEEK_WEDNESDAY)) {
                    $this->list_jours_garderie[$d->getDayOfWeek() . '-2'][] = $currentDay->format('Y-m-d') . self::HEURE_SOIR;
                }
            }

            // passe au jour suivant
            $currentDay = $currentDay->add($oneDay);
        }
    }

    /**
     * @param $taps
     * @return array
     */
    public function getTapSelectionToArray($taps)
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
     * @param $garderies
     * @return array
     */
    public function getGarderieSelectionToArray($garderies)
    {
        $tmp = array();
        foreach($garderies as $garderie) {
            foreach ($this->list_jours_garderie as $dayOfWeek => $datesheures) {

                $dateheure  = $garderie->getDateHeure()->format('Y-m-d H:i:s');

                if (in_array($dateheure, $datesheures)) {
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
}
