<?php
/**
 * Created by PhpStorm.
 * User: rod
 * Date: 11/05/16
 * Time: 00:48
 */

namespace WCS\CantineBundle\Controller;


class CantineCalendar
{
    public function prepareCalendar()
    {
        // Lancement de la fonction calendrier
        $calendrier = $this->generateCalendar(new \DateTime('2015-09-01'), new \DateTime('2016-07-31'));
        $limit = new \DateTime();

        // Liste des jours de la semaine
        $jours= array('Lun','Mar','Mer','Jeu','Ven','Sam','Dim');

        // Récupération des dates du calendrier

        // Récupère les jours fériés en base de données
        $dateNow = new \DateTime('Y');
        $dateString = date_format($dateNow, ('Y'));

        $em = $this->getDoctrine()->getManager();
        $feries = $em->getRepository('WCSCantineBundle:Feries')->findBy(array('annee' => $dateString));
        $feriesArray = [];
        for ($i = 0; $i < count($feries); $i++){
            $feriesArray[$i]['paques'] = $feries[$i]->getPaques();
            $feriesArray[$i]['pentecote'] = $feries[$i]->getPentecote();
            $feriesArray[$i]['ascension'] = $feries[$i]->getAscension();
            // pensez a rajouter le vendredi de l'ascension //
        }
        // fin //

        // Date du début et de fin des vacances de la Toussaint
        $toussaintStart = $this->container->get('calendar.holidays')->getToussaintStart();
        $toussaintStartDT = new \DateTime($toussaintStart);
        $toussaintStartFormat = date_format($toussaintStartDT, ('Y-m-d'));
        $toussaintEnd = $this->container->get('calendar.holidays')->getToussaintEnd();
        $toussaintEndDT = new \DateTime($toussaintEnd);
        $toussaintEndFormat = date_format($toussaintEndDT, ('Y-m-d'));

        // Date du début et de fin des vacances de Noël
        $noelStart = $this->container->get('calendar.holidays')->getNoelStart();
        $noelStartDT = new \DateTime($noelStart);
        $noelStartFormat = date_format($noelStartDT, ('Y-m-d'));
        $noelEnd = $this->container->get('calendar.holidays')->getNoelEnd();
        $noelEndDT = new \DateTime($noelEnd);
        $noelEndFormat = date_format($noelEndDT, ('Y-m-d'));

        // Date du début et de fin des vacances d'hiver
        $hiverStart = $this->container->get('calendar.holidays')->getHiverStart();
        $hiverStartDT = new \DateTime($hiverStart);
        $hiverStartFormat = date_format($hiverStartDT, ('Y-m-d'));
        $hiverEnd = $this->container->get('calendar.holidays')->getHiverEnd();
        $hiverEndDT = new \DateTime($hiverEnd);
        $hiverEndFormat = date_format($hiverEndDT, ('Y-m-d'));

        // Date du début et de fin des vacances de Printemps
        $printempsStart = $this->container->get('calendar.holidays')->getPrintempsStart();
        $printempsStartDT = new \DateTime($printempsStart);
        $printempsStartFormat = date_format($printempsStartDT, ('Y-m-d'));
        $printempsEnd = $this->container->get('calendar.holidays')->getPrintempsEnd();
        $printempsEndDT = new \DateTime($printempsEnd);
        $printempsEndFormat = date_format($printempsEndDT, ('Y-m-d'));

        $vacancesHiver = $this->getHolidays($hiverStartFormat, $hiverEndFormat);
        $vacancesNoel = $this->getHolidays($noelStartFormat, $noelEndFormat);
        $vacancesToussaint = $this->getHolidays($toussaintStartFormat, $toussaintEndFormat);
        $vacancesPrintemps = $this->getHolidays($printempsStartFormat, $printempsEndFormat);

        $icalVacancesEte = new \DateTime($this->container->get('calendar.holidays')->getYearEnd());
        $grandesVacances = date_format($icalVacancesEte, ('Y-m-d'));

        $vacancesEte = new \DateTime($this->container->get('calendar.holidays')->getYearEnd());
        $date = date_timestamp_get($limit) + 168*60*60;
        $finAnnee = date_timestamp_get($vacancesEte);

    }


    /**
     * Generate calendar
     */
    public function generateCalendar(\DateTime $start, \DateTime $end)
    {
        $return = array();
        $calendrier = $start;

        while ($calendrier <= $end) {
            $y = date_format($calendrier, ('Y'));
            $m = date_format($calendrier, ('m'));
            $d = date_format($calendrier, ('d'));
            $w = str_replace('0', '7', date_format($calendrier, ('w')));
            $return[$y][$m][$d] = $w;
            $calendrier->add(new \DateInterval('P1D'));
        }
        return $return;
    }


    /**
     * Generate range date
     */
    private function getHolidays($start, $end)
    {
        $array = [];
        $period = new \DatePeriod(new \DateTime($start), new \DateInterval('P1D'), new \DateTime($end));

        foreach ($period as $date) {
            $array[] = date_format($date, ('Y-m-d'));
        }
        return $array;
    }

}