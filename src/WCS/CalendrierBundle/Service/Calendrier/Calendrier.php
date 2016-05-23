<?php
/*===============================================================================================================

    Le calendrier permet d'accéder à chaque jour de l'année scolaire par mois.

===============================================================================================================*/


namespace WCS\CalendrierBundle\Service\Calendrier;

use WCS\CalendrierBundle\Service\Calendrier\Day;
use WCS\CalendrierBundle\Service\PeriodesAnneeScolaire\PeriodesAnneeScolaire;


class Calendrier
{
    /**
     * Calendrier constructor.
     *
     * Génère chaque jours du calendrier pour une période scolaire donnée
     *
     * @param PeriodesAnneeScolaire $calScolaire
     */
    public function __construct(PeriodesAnneeScolaire $periodesScolaire)
    {
        $this->periodesScolaire = $periodesScolaire;
        $this->days = array();

        $annee_scolaire = $periodesScolaire->getAnneeScolaire();
        $currentDay     = $annee_scolaire->getDebut();
        $end            = new \DateTimeImmutable($annee_scolaire->getFin()->format('Y-m').'-31');
        $oneDay         = new \DateInterval('P1D');

        while ($currentDay <= $end) {
            // enregistre les infos sur la journée dans le calendrier
            $d = new Day($currentDay, $periodesScolaire);

            // colonne 1 : mois
            // colonne 2 : jour
            $this->days[ $d->getMonth() ][ $d->getDay() ] = $d;

            // passe au jour suivant
            $currentDay = $currentDay->add($oneDay);
        }
    }

    /**
     * @param string|integer $month numéro du mois
     * @return array tableau indexé de \WCS\CalendrierBundle\Service\Calendrier\Day par jours
     * @example
     *      $cal = ... // instance de Calendrier
     *
     *      // récupère les jours du mois de février
     *      $jours_dans_fevrier = $cal->getDays('02');
     *
     *      // récupère le 21 février, "journee" est une instance de "Day"
     *      $journee = $jours_dans_fevrier[21];
     *
     *      // récupère les informations utiles
     *      $est_jour_ferme = $journee->isOff();
     *      $jour = $journee->day();
     *      $mois = $journee->month();
     *
     *       // affiche la journée au format Y-m-d
     *      echo $journee;
     */
    public function getDays($month)
    {
        return $this->days[$month];
    }

    /**
     * Renvoit une liste de mois de l'année scolaire, dans l'ordre de '09' (sept) à '07' (juillet).
     * @return array
     */
    public function getMonths()
    {
        return array('09','10','11','12','01','02','03','04','05','06','07');
    }

    /**
     * @return PeriodesAnneeScolaire
     */
    public function getPeriodesScolaire()
    {
        return $this->periodesScolaire;
    }

    /**
     * Ajoute une liste de jours "fermés" (ex : des jours fériés)
     * uniquement pour la période du calendrier
     * Ainsi, un jour férié pour une autre année ne sera pas ajouté.
     * @param $array_days_off tableau indexés de DateTimes durant lesquels il n'y a pas classe.
     */
    public function addDaysOff($array_days_off)
    {
        $this->addDaysWithAttribute($array_days_off, 'setIsOff', true);
    }


    /**
     * Ajoute une liste de jours "fermés" (ex : des jours fériés)
     * uniquement pour la période du calendrier
     * Ainsi, un jour férié pour une autre année ne sera pas ajouté.
     * @param $array_days_off tableau indexés de DateTimes durant lesquels il n'y a pas classe.
     */
    public function addDaysPast($array_days_past)
    {
        $this->addDaysWithAttribute($array_days_past, 'setIsPast', true);
    }


    /**
     * Ajoute une liste de jours
     * uniquement pour la période du calendrier
     * Ainsi, un jour pour une autre année ne sera pas ajouté.
     * @param $array_days tableau indexés de DateTimes
     */
    private function addDaysWithAttribute($array_days, $methodName, $attributeValue)
    {
        foreach($array_days as $jour) {
            $year   = $jour->format('Y');
            $month  = $jour->format('m');
            $day    = $jour->format('d');
            $d = &$this->days[$month][$day];
            if ($d->getYear()==$year) {
                $d->{$methodName}($attributeValue);
            }
        }
    }

    /**
     * @var array indexé de \WCS\CalendrierBundle\Service\Calendrier\Day par mois et jours
     */
    private $days;
    private $periodesScolaire;
}