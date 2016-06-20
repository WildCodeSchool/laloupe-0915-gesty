<?php
namespace WCS\CalendrierBundle\Service;


//use WCS\CalendrierBundle\Service\Calendrier\ActivityType;
use WCS\CalendrierBundle\Service\ICSFileReader\ICSFileReader;
use WCS\CalendrierBundle\Service\PeriodesAnneeScolaire\PeriodesAnneeScolaire;
use WCS\CalendrierBundle\Service\Calendrier\Calendrier;

class Service
{
    /*==========================================================================================================
        Méthodes
    ==========================================================================================================*/

    /**
     * @return Periode\Periode
     */
    public function getAnneeScolaire()
    {
        return $this->getCalendrierRentreeScolaire()->getPeriodesScolaire()->getAnneeScolaire();
    }


    /**
     * @param $activityTypeConstant
     * @param \DateTimeInterface $dateDay
     * @return bool
     */
    public function isDayOff(\DateTimeInterface $dateDay, $options=array())
    {
        /*
        if ($this->daysOff->isOff($dateDay, $options)) {
            return true;
        }
        
        return ActivityType::isDayOff($activityTypeConstant, $dateDay);
        */
        return $this->daysOff->isOff($dateDay, $options);
    }

    /**
     * renvoit le calendrier scolaire pour l'année en cours.
     *
     * @return \WCS\CalendrierBundle\Service\Calendrier\Calendrier|null
     */
    public function getCalendrierRentreeScolaire()
    {
        if (!isset($this->cals[$this->annee_rentree_from_today])) {
            return null;
        }
        return $this->cals[$this->annee_rentree_from_today];
    }

    /**
     * renvoit les périodes vacance ou en classe scolaire pour l'année en cours.
     *
     * @return \WCS\CalendrierBundle\Service\PeriodesAnneeScolaire\PeriodesAnneeScolaire|null
     */
    public function getPeriodesAnneeRentreeScolaire()
    {
        $cal = $this->getCalendrierRentreeScolaire();
        if (!$cal) {
            return null;
        }
        return $cal->getPeriodesScolaire();
    }


    /**
     * @return integer renvoit le nombre de calendriers scolaires chargés
     */
    public function getNbAnneeScolaires()
    {
        return count($this->cals);
    }


    /**
     * Sélectionne la rentrée scolaire en cours
     * @param string $date_jour au format 'Y-m-d'
     * @throws \Exception
     */
    public function selectRentreeScolaireAvecDate($date_jour)
    {
        if (!is_string($date_jour)) {
            throw new \Exception("date_jour doit etre au format 'Y-m-d'");
        }

        // si l'année en cours correspond à la seconde partie de l'année scolaire
        // alors l'année de la rentrée de l'année précédente sera utilisée
        $this->date_du_jour = $date_jour;

        $tmp = \DateTime::createFromFormat('Y-m-d', $date_jour);
        $now_year = $tmp->format('Y');

        if ($date_jour >= "$now_year-01-01" && $date_jour <= "$now_year-07-31") {
            $this->annee_rentree_from_today = $now_year - 1;
        } else {
            $this->annee_rentree_from_today = $now_year;
        }

        foreach($this->cals as $cal) {
            $cal->setDateToday($date_jour);
        }
    }

    /**
     * @param string $annee_rentree au format YYYY
     * @throws \Exception
     */
    public function selectRentreeScolaire($annee_rentree)
    {
        if (1!==preg_match('/^\d{4}$/', $annee_rentree)) {
            throw new \Exception("L'année scolaire doit être au format YYYY. Annee saisie : ".$annee_rentree);
        }

        $date = new \DateTime();
        $annee_rentree += 1;
        $dateStr = $annee_rentree.'-'.$date->format("m-d");
        
        $this->selectRentreeScolaireAvecDate($dateStr);
    }



    /*==========================================================================================================
        Constructeur
        methodes privées
        et attributs
    ==========================================================================================================*/
    
    /**
     * Service constructor.
     *
     * Charge la liste des périodes scolaires depuis un fichier ICS
     *
     * @param $filepath
     * @param DateNow $dateNow
     * @param DaysOffInterface $daysOff
     */
    public function __construct(
        $icsFilepath,
        DateNow $dateNow,
        DaysOffInterface $daysOff
    )
    {
        $this->daysOff = $daysOff;
        $this->cals = array();

        $this->selectRentreeScolaireAvecDate( $dateNow->getDate()->format('Y-m-d') );
        
        $this->loadFromFile($icsFilepath);
    }

    /**
     * @param $icsFilepath
     */
    private function loadFromFile($icsFilepath)
    {
        // charge le fichier ICS
        $cal    = new ICSFileReader($icsFilepath);
        $events = $cal->getEvents();
        if (iterator_count($events)==0) {
            return;
        }

        // cherche l'index du 1er événement pour chaque année scolaire
        // à chaque fois que l'on a lu tous les évènements d'une année scolaire,
        // on l'ajoute à la liste des années scolaires, puis on passe à l'année suivante.
        $nbEventsRead   = 1;
        $annee          = $events->get(0)->getDebut()->format('Y');
        $tmp_periodes   = array();

        foreach($events as $event) {
            $tmp_periodes[] = $event;
            if (($nbEventsRead % PeriodesAnneeScolaire::NB_EVENTS_PAR_AN)==0) {
                $cal = new Calendrier(
                                                    new PeriodesAnneeScolaire($tmp_periodes, $this->date_du_jour),
                                                    $this->date_du_jour
                                                    );

                $feriesArray = $this->daysOff->findDatesWithin($cal->getPeriodesScolaire()->getAnneeScolaire());
                if ($feriesArray) {
                    $cal->addDaysOff($feriesArray);
                }

                $this->cals[$annee] = $cal;
                $tmp_periodes = array();
                $annee = $event->getDebut()->format('Y');
            }
            $nbEventsRead++;
        }
    }

    /**
     * @var string formatted with Y-m-d
     */
    private $date_du_jour;

    /**
     * @var array of Calendrier
     */
    private $cals;

    /**
     * @var string
     */
    private $annee_rentree_from_today;

    /**
     * @var DaysOffInterface
     */
    private $daysOff;
}
