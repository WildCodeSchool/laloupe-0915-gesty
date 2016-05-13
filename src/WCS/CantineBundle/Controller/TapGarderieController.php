<?php
/**
 * Created by PhpStorm.
 * User: manu
 * Date: 10/05/16
 * Time: 11:09
 */

namespace WCS\CantineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use \WCS\CantineBundle\Entity\Eleve;

class TapGarderieController extends Controller
{
    private function recupererPeriodes ()
    {
        $periodes = array();


      /*

        $dateNow = new \DateTime('Y');
        $dateString = date_format($dateNow, ('Y')); // Formate la date d'aujourd'hui en sélectionnant que l'année


        // Récupère les jours fériés en base de données
        $feries = $em->getRepository('WCSCantineBundle:Feries')->findBy(array('annee' => $dateString));
        // Boucle sur l'entité $feries pour la transformer en un array
        $feriesArray = [];
        for ($i = 0; $i < count($feries); $i++){
            $feriesArray[$i]['paques'] = $feries[$i]->getPaques();
            $feriesArray[$i]['pentecote'] = $feries[$i]->getPentecote();
            $feriesArray[$i]['ascension'] = $feries[$i]->getAscension();
            $feriesArray[$i]['vendrediascension'] = $feries[$i]->getVendrediAscension();

        }
        // Fin //


        // Lancement de la fonction calendrier
        $calendrier = $this->generateCalendar(new \DateTime('2015-09-01'), new \DateTime('2016-07-31'));
        $limit = new \DateTime();

        // Liste des jours de la semaine
        $jours= array('Lun','Mar','Mer','Jeu','Ven','Sam','Dim');

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

        // Récupération de toutes les dates entre deux dates
        $vacancesHiver = $this->getHolidays($hiverStartFormat, $hiverEndFormat);
        $vacancesNoel = $this->getHolidays($noelStartFormat, $noelEndFormat);
        $vacancesToussaint = $this->getHolidays($toussaintStartFormat, $toussaintEndFormat);
        $vacancesPrintemps = $this->getHolidays($printempsStartFormat, $printempsEndFormat);

        // Récupération de la date de fin d'année dans l'ICAL
        $icalVacancesEte = new \DateTime($this->container->get('calendar.holidays')->getYearEnd());
        $grandesVacances = date_format($icalVacancesEte, ('Y-m-d'));

        $vacancesEte = new \DateTime($this->container->get('calendar.holidays')->getYearEnd());
        $date = date_timestamp_get($limit) + 168*60*60;
        $finAnnee = date_timestamp_get($vacancesEte);*/
        //tableau associatif
        return $periodes;
    }

    public function inscrireAction($id_eleve)
    {
        // récupère une instance de Doctrine
        $em = $this->getDoctrine()->getManager();

        // récupère la liste des enfants
        $eleve = $em->getRepository("WCSCantineBundle:Eleve")->findOneBy(array("id"=>$id_eleve));

        $periodes = $this->recupererPeriodes();
        // algo de bouclages pour recuperer la periode




        return $this->render(
            'WCSCantineBundle:TapGarderie:inscription.html.twig',
            array("eleve" => $eleve)
        );






    }


  

}