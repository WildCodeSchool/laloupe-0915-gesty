<?php
namespace WCS\CalendrierBundle\Service;


use WCS\CalendrierBundle\Service\ICSFileReader\ICSFileReader;
use WCS\CalendrierBundle\Service\PeriodesAnneeScolaire\PeriodesAnneeScolaire;
use WCS\CalendrierBundle\Service\Calendrier\Calendrier;

class Service
{
    /*==========================================================================================================
        Méthodes
    ==========================================================================================================*/

    /**
     * renvoit le calendrier scolaire pour une année donnée.
     * Par défaut renvoit le calendrier de l'année en cours.
     *
     * @param $annee_rentree (facultatif) l'année de la rentrée scolaire
     * @return \WCS\CalendrierBundle\Service\PeriodesAnneeScolaire\PeriodesAnneeScolaire|null
     */
    public function getCalendrierRentreeScolaire($annee_rentree='')
    {
        return $this->getListAnnee('calendriers', $annee_rentree);
    }

    /**
     * renvoit le calendrier scolaire pour une année donnée.
     * Par défaut renvoit le calendrier de l'année en cours.
     *
     * @param $annee_rentree (facultatif) l'année de la rentrée scolaire
     * @return \WCS\CalendrierBundle\Service\PeriodesAnneeScolaire\PeriodesAnneeScolaire|null
     */
    public function getPeriodesAnneeRentreeScolaire($annee_rentree='')
    {
        return $this->getListAnnee('periodesScolaires', $annee_rentree);
    }

    /**
     * @return integer renvoit le nombre de calendriers scolaires chargés
     */
    public function getNbAnneeScolaires()
    {
        return count($this->periodesScolaires);
    }
    

    /*==========================================================================================================
        Constructeur
        et attributs
    ==========================================================================================================*/
    
    /**
     * Service constructor.
     *
     * Charge la liste des périodes scolaires depuis un fichier ICS
     *
     * @param $filepath
     * @param string date du jour au format "Y-m-d"
     */
    public function __construct($icsFilepath, $date_today=null)
    {
        // si l'année en cours correspond à la seconde partie de l'année scolaire
        // alors l'année de la rentrée de l'année précédente sera utilisée
        // par la méthode getCalendrierRentree si aucun paramètre n'est passé
        if (empty($date_today)) {
            $date_today = date('Y-m-d');
        }

        $tmp   = \DateTime::createFromFormat('Y-m-d', $date_today);
        $now_year   = $tmp->format('Y');

        if ($date_today>="$now_year-01-01" && $date_today<="$now_year-07-31") {
            $this->annee_rentree_from_today = $now_year - 1;
        }
        else {
            $this->annee_rentree_from_today = $now_year;
        }

        // charge le fichier ICS
        $cal    = new ICSFileReader($icsFilepath);
        $events = $cal->getEvents();

        // cherche l'index du 1er événement pour chaque année scolaire
        // à chaque fois que l'on a lu tous les évènements d'une année scolaire,
        // on l'ajoute à la liste des années scolaires, puis on passe à l'année suivante.
        $nbEventsRead   = 1;
        $annee          = $events->get(0)->getDebut()->format('Y');
        $tmp_periodes   = array();

        foreach($events as $event) {
            $tmp_periodes[] = $event;
            if (($nbEventsRead % PeriodesAnneeScolaire::NB_EVENTS_PAR_AN)==0) {
                $this->periodesScolaires[$annee]    = new PeriodesAnneeScolaire($tmp_periodes, $date_today);
                $this->calendriers[$annee]          = new Calendrier($this->periodesScolaires[$annee]);
                $tmp_periodes = array();
                $annee = $event->getDebut()->format('Y');
            }
            $nbEventsRead++;
        }
    }

    /**
     * renvoit le calendrier scolaire ou les periodes année scolaires pour une année donnée.
     * Par défaut renvoit le calendrier ou la periode annéee scolaire de l'année en cours.
     *
     * @param $attributeName soit "periodesScolaires" soit "calendriers"
     * @param $annee_rentree (facultatif) l'année de la rentrée scolaire
     * @return mixed|null
     */
    private function getListAnnee($attributeName, $annee_rentree='')
    {
        if (empty($annee_rentree)) {
            $annee_rentree = $this->annee_rentree_from_today;
        }

        if (!isset($this->{$attributeName}[$annee_rentree])) {
            return null;
        }
        return $this->{$attributeName}[$annee_rentree];
    }

    /**
     * @var array of PeriodesAnneeScolaire
     */
    private $periodesScolaires;

    /**
     * @var array de Calendrier
     */
    private $calendriers;

    /**
     * @var string
     */
    private $annee_rentree_from_today;
}