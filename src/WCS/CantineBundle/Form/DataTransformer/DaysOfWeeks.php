<?php

namespace WCS\CantineBundle\Form\DataTransformer;


use WCS\CalendrierBundle\Service\Periode\Periode;
use WCS\CalendrierBundle\Service\Calendrier\Day;


class DaysOfWeeks
{
    private $list_jours_tap       = array();
    private $list_jours_garderie  = array();


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


    public function __construct(Periode $periode)
    {
        $currentDay     = $periode->getDebut();
        $end            = new \DateTimeImmutable($periode->getFin()->format('Y-m').'-31');
        $oneDay         = new \DateInterval('P1D');

        while ($currentDay <= $end) {

            // enregistre les infos sur la journée dans le calendrier
            $d = new Day($currentDay);

            $this->list_jours_garderie[$d->getDayOfWeek().'-1'][] = $currentDay->format('Y-m-d').' 08:00:00';

            if ($d->getDayOfWeek()==2 || $d->getDayOfWeek()==4) {
                $this->list_jours_tap[$d->getDayOfWeek()][] = $currentDay->format('Y-m-d');
            }

            if ($d->getDayOfWeek()!=3 ) {
                $this->list_jours_garderie[ $d->getDayOfWeek().'-2' ][] = $currentDay->format('Y-m-d') . ' 17:00:00';
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
                if (in_array($tap->getDate()->format('Y-m-d'), $dates)) {
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
