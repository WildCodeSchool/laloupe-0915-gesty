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
}
