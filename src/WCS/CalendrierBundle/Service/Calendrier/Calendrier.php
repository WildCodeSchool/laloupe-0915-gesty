<?php
/*===============================================================================================================

    Le calendrier permet d'accéder à chaque jour de l'année scolaire par mois.

    EXEMPLE

        // "serviceCalendrier" est une instance de Service
        // (récupérable en tant que service depuis un controlleur)

        $cal = $serviceCalendrier->getCalendrierRentreeScolaire();

        // récupère les jours du mois de février

        $jours_dans_fevrier = $cal->getDays('02');

        // récupère le 21 février, "journee" sera une instance de "Day"

        $journee = $jours_dans_fevrier[21];

        // récupère les informations utiles

        $est_jour_ferme = $journee->isOff();
        $jour           = $journee->day();
        $mois           = $journee->month();

        // affiche la journée au format Y-m-d
        echo $journee;

===============================================================================================================*/


namespace WCS\CalendrierBundle\Service\Calendrier;

use Symfony\Component\VarDumper\VarDumper;
use WCS\CalendrierBundle\Service\PeriodesAnneeScolaire\PeriodesAnneeScolaire;


class Calendrier
{
    /*==========================================================================================================
        Méthodes
    ==========================================================================================================*/

    /**
     * @param string|integer $month numéro du mois
     * @return \WCS\CalendrierBundle\Service\Calendrier\Day[] tableau indexé de jours
     */
    public function getDays($month)
    {
        return $this->days[$month];
    }

    /**
     * Renvoit une liste de mois de l'année scolaire, dans l'ordre de '09' (sept) à '07' (juillet).
     * @return string[]
     */
    public function getMonths()
    {
        return array('09','10','11','12','01','02','03','04','05','06','07');
    }

    /**
     * Ajoute une liste de jours "fermés" (ex : des jours fériés)
     * uniquement pour la période du calendrier
     * Ainsi, un jour férié pour une autre année ne sera pas ajouté.
     * 
     * @param \DateTime[] $array_days_off dates lesquels il n'y a pas classe.
     */
    public function addDaysOff($array_days_off)
    {
        $this->addDaysWithAttribute($array_days_off, 'setOff', true);
    }


    /**
     * Ajoute une liste de jours "passés"
     * uniquement pour la période du calendrier
     * Ainsi, un jour passé pour une autre année ne sera pas ajouté.
     * 
     * @param \DateTime[] $array_days_past dates durant lesquels il n'y a pas classe.
     */
    public function addDaysPast($array_days_past)
    {
        $this->addDaysWithAttribute($array_days_past, 'setPast', true);
    }


    /**
     * Ajoute "nb jours" "fermés" (ex : des jours fériés) depuis une date de début
     * uniquement pour la période du calendrier
     * Ainsi, un jour férié pour une autre année ne sera pas ajouté.
     *
     * @param \DateTimeImmutable $dateDebut date à partir de laquelle on doit ajouter des jours "fermés" (date incluse)
     * @param \DateInterval $nbJours à ajouter
     */
    public function addDaysPastFrom(\DateTimeImmutable $dateDebut, \DateInterval $nbJours)
    {

        $oneDay     = new \DateInterval('P1D');
        $currentDay = $dateDebut;
        $dayPlus7   = $currentDay->add($nbJours);
        $list_day_past = array();

        while ($currentDay < $dayPlus7) {
            $list_day_past[] = $currentDay;
            $currentDay = $currentDay->add($oneDay);
        }
        $this->addDaysPast($list_day_past);
    }

    /**
     * @return PeriodesAnneeScolaire
     */
    public function getPeriodesScolaire()
    {
        return $this->periodesScolaire;
    }

    /**
     * @return mixed
     */
    public function getDateToday()
    {
        return $this->date_today;
    }

    /**
     * @param string $date_today au format 'Y-m-d'
     */
    public function setDateToday($date_today)
    {
        $this->date_today = $date_today;
        $this->updateDaysIsPast($date_today);
    }


    /*==========================================================================================================
        Constructeur
        methodes privées
        et attributs
    ==========================================================================================================*/

    /**
     * Calendrier constructor.
     *
     * Génère chaque jours du calendrier pour une période scolaire donnée
     *
     * @param PeriodesAnneeScolaire $periodesScolaire
     * @param string - date du jour au format 'Y-m-d'
     */
    public function __construct(PeriodesAnneeScolaire $periodesScolaire, $date_du_jour)
    {
        $this->periodesScolaire = $periodesScolaire;
        $this->date_today = $date_du_jour;
        $this->days = array();

        $annee_scolaire = $periodesScolaire->getAnneeScolaire();
        $currentDay     = $annee_scolaire->getDebut();
        $end            = new \DateTimeImmutable($annee_scolaire->getFin()->format('Y-m').'-31');
        $oneDay         = new \DateInterval('P1D');

        while ($currentDay <= $end) {
            $d = new Day($currentDay);

            if ($d->isDayOfWeek(Day::WEEK_WEDNESDAY) ||
                $d->isDayOfWeek(Day::WEEK_SATURDAY) ||
                $d->isDayOfWeek(Day::WEEK_SUNDAY)) {

                $d->setOff(true);
            }
            else {
                $d->setOff(is_null($periodesScolaire->findEnClasseFrom($d->getDateString())));
            }

            $this->days[ $d->getMonth() ][ $d->getDay() ] = $d;

            // passe au jour suivant
            $currentDay = $currentDay->add($oneDay);
        }

        $this->updateDaysIsPast($date_du_jour);
    }

    /**
     * Ajoute une liste de jours
     * uniquement pour la période du calendrier
     * Ainsi, un jour pour une autre année ne sera pas ajouté.
     *
     * @param   \DateTime[]     $array_days
     * @param   string          $methodName
     * @param   mixed           $attributeValue
     */
    private function addDaysWithAttribute($array_days, $methodName, $attributeValue)
    {
        if ($this->date_today > $this->periodesScolaire->getAnneeScolaire()->getDebut()->format('Y-m-d')) {
            foreach ($array_days as $jour) {
                $year = $jour->format('Y');
                $month = $jour->format('m');
                $day = $jour->format('d');

                $d = &$this->days[$month][$day];
                if ($d->getYear() == $year) {
                    $d->{$methodName}($attributeValue);
                }

            }
        }
    }

    /**
     * @param string $date au format 'Y-m-d'
     */
    private function updateDaysIsPast($date)
    {
        foreach($this->days as $month) {
            foreach($month as $day) {
                $day->setPast($day->__toString() < $date);
            }
        }
    }

    /**
     * @var \WCS\CalendrierBundle\Service\Calendrier\Day[][]
     */
    private $days;

    /**
     * @var PeriodesAnneeScolaire
     */
    private $periodesScolaire;

    /**
     * @var string
     */
    private $date_today;
}
